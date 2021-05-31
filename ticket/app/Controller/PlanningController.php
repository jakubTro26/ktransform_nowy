<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 21.02.17
 * Time: 10:43
 */

namespace Controller;

use Station\Model\Station;
use StationPosition\Model\StationPosition;
use StationReservation\Model\StationReservation;
use Company\Model\Company;


class PlanningController
{
    protected $view;

    // constructor receives container instance
    public function __construct($container, $router)
    {
        $this->view = $container;
        $this->router = $router;
    }

    public function index($request, $response, $args)
    {
        return $this->view->render($response, 'map.phtml', [
            'mapContent' => json_encode($this->mapGet())
        ]);
    }

    public function mapGet($front = false)
    {
        $modelStation = new Station();
        $modelStationPosition = new StationPosition();
        $result = $modelStation->getAll();

        $mapHtml = '';
        foreach ($result as $row) {
            //<area shape="circle" coords="413, 480, 204" station_name="test koło" station_price="3222" station_area="34,7" station_key="izkj8y8b4lgjg5gefi" />
            $mapHtml .= '<area class="free" active="' . $row['station_active'] . '" shape="' . $row['station_area_type'] . '" coords="';

            $cords = $modelStationPosition->getAll(array(
                'station_id' => $row['station_id']
            ), array(
                'sort'  => 'position_order',
                'order' => 'ASC'
            ));

            $mapHtmlCord = null;
            foreach ($cords as $cord) {
                if ($cord['position_radius'] > 0) {
                    $mapHtmlCord = $cord['position_x'] . ',' . $cord['position_y'] . ',' . $cord['position_radius'];
                } elseif ($cord['position_width'] > 0) {
                    $mapHtmlCord = $cord['position_x'] . ',' . $cord['position_y'] . ',' . ($cord['position_x']+$cord['position_width']) . ',' . ($cord['position_y']+$cord['position_height']);
                } else {
                    $mapHtmlCord[] = $cord['position_x'];
                    $mapHtmlCord[] = $cord['position_y'];
                }
            }

            if (is_array($mapHtmlCord)) {
                $mapHtmlCord = implode(',', $mapHtmlCord);
            }

            $mapHtml .= $mapHtmlCord . '"';
            $mapHtml .= ' station_name="' . $row['station_name'] . '"';
            $mapHtml .= ' station_price="' . $row['station_price'] . '"';
            $mapHtml .= ' station_area="' . $row['station_area'] . '"';
            $mapHtml .= " station_key=\"" . $row['station_key'] . "\" />\n";
        }

        return $mapHtml;
    }

    public function mapSave($request, $response, $args)
    {

        $arrayData = json_decode($request->getBody());
        $type = array(
            'circle'    => 'circle',
            'rectangle' => 'rect',
            'polygon'   => 'poly',
        );

        $modelStation = new Station();
        $modelStationPosition = new StationPosition();
        foreach ($arrayData->areas as $area) {
            $row = $modelStation->getOne(array(
                'station_key' => $area->attributes->station_key
            ));

            if ($row == null) {
                $id = $modelStation->insert(array(
                    'station_name'      => $area->attributes->station_name,
                    'station_price'     => $area->attributes->station_price,
                    'station_area'      => $area->attributes->station_area,
                    'station_key'       => $area->attributes->station_key,
                    'station_area_type' => $type[$area->type]
                ));
            } else {
                $id = $row['station_id'];
                $modelStation->update(array(
                    'station_name'  => $area->attributes->station_name,
                    'station_price' => $area->attributes->station_price,
                    'station_area'  => $area->attributes->station_area,
                ), $id);
            }

            $modelStationPosition->deleteWhere('station_id = ' . $id);

            $positionData = array();
            switch ($area->type) {
                case 'circle':
                    $positionData[] = array(
                        'station_id'      => $id,
                        'position_x'      => $area->coords->cx,
                        'position_y'      => $area->coords->cy,
                        'position_radius' => $area->coords->radius,
                    );
                    break;

                case 'rectangle':
                    $positionData[] = array(
                        'station_id'      => $id,
                        'position_x'      => $area->coords->x,
                        'position_y'      => $area->coords->y,
                        'position_width'  => $area->coords->width,
                        'position_height' => $area->coords->height
                    );
                    break;

                case 'polygon':
                    $i = 1;
                    foreach ($area->coords->points as $point) {
                        $positionData[] = array(
                            'station_id'     => $id,
                            'position_x'     => $point->x,
                            'position_y'     => $point->y,
                            'position_order' => $i
                        );

                        $i++;
                    }
                    break;
            }

            foreach ($positionData as $position) {
                $modelStationPosition->insert($position);
            }
        }

        return $response->withJson(json_decode($request->getBody()), 200);
    }

    public function reservationAdd($request, $response, $args)
    {
        $arrayData = $request->getParsedBody();

        $modelCompany = new Company();
        $resOne = $modelCompany->getOne(array(
            'company_name' => $arrayData['company_name']
        ));

        if (!array_key_exists('company_id', $resOne)) {
            $companyId = $modelCompany->insert($arrayData);
        } else {
            $companyId = $resOne['company_id'];
        }

        $modelStation = new Station();
        $station = $modelStation->getOne(array(
            'station_key' => $arrayData['station_key']
        ));

        $modelStationReservation = new StationReservation();
        $reservationId = $modelStationReservation->insert(array(
            'station_id'        => $station['station_id'],
            'company_id'        => $companyId,
            'reservation_price' => $arrayData['reservation_price'],
            'reservation_date'  => date("Y-m-d H:i:s"),
        ));

        $modelStation->update(array(
            'station_active' => 0
        ), $station['station_id']);

        //send email
        $route = $this->router->get('router')->getNamedRoute("email_reservation");

        $request = $this->router->get('request')->withAttribute('station', $station);
        $route->run($request, $this->router->get('response'), $station);

        $modelStationReservation->update(array(
            'reservation_notify'      => 1,
            'reservation_notify_date' => date("Y-m-d H:i:s")
        ), $reservationId);

        return $response->withStatus(200)->withHeader('Location', '/?reservation=ok');

    }

    public function reservationEdit($request, $response, $args)
    {
        $modelStationReservation = new StationReservation();
        $modelStationReservation->load((int)$args['id']);

        if($request->isPost()) {
            $arrayData = $request->getParsedBody();

            if(array_key_exists('id', $args) && (int)$args['id'] > 0) {
                $id = $args['id'];
                $modelStationReservation->update($arrayData, $args['id']);
            } else {
                $id = $modelStationReservation->insert($arrayData);
            }

            return $response->withJson(array(
                'status' => 'success'
            ), 200);
        }

        return $this->view->render($response, 'reservation-edit.phtml', [
            'result' => $modelStationReservation->getData(),
            'id' => $args['id']
        ]);
    }

    public function reservationList($request, $response, $args)
    {
        $modelStationReservation = new StationReservation();
        $resultData = $modelStationReservation->getAll(array(), array(
            'sort'  => 'x.reservation_date',
            'order' => 'desc'
        ), array(
            'limit' => $args['last'],
            'start' => 0
        ));

        return $this->view->render($response, 'reservation.phtml', [
            'result' => $resultData
        ]);
    }

    public function reservationDelete($request, $response, $args) {

        if($request->isPost()) {
            $arrayData = $request->getParsedBody();

            $modelStationReservation = new StationReservation();
            $stationReservation = $modelStationReservation->getOne(array(
                'station_reservation_id' => $arrayData['id']
            ));

            $modelStationReservation->delete($stationReservation['station_reservation_id']);

            $modelStation = new Station();
            $modelStation->update(array(
                'station_active' => 1
            ), $stationReservation['station_id']);

            return $response->withJson(array(
                'status' => 'success'
            ), 200);
        }

        $modelStationReservation = new StationReservation();
        $modelStationReservation->load((int)$args['id']);

        return $this->view->render($response, 'reservation-delete.phtml', [
            'result' => $modelStationReservation->getData(),
            'id' => $args['id']
        ]);
    }

    public function stationEdit($request, $response, $args)
    {
        $modelStation = new Station();
        $modelStation->load((int)$args['id']);

        if($request->isPost()) {
            $arrayData = $request->getParsedBody();

            if(array_key_exists('id', $args) && (int)$args['id'] > 0) {
                $id = $args['id'];
                $modelStation->update($arrayData, $args['id']);
            } else {
                $id = $modelStation->insert($arrayData);
            }

            return $response->withJson(array(
                'status' => 'success'
            ), 200);
        }

        return $this->view->render($response, 'station-edit.phtml', [
            'result' => $modelStation->getData(),
            'id' => $args['id']
        ]);
    }

    public function stationList($request, $response, $args)
    {
        $modelStation = new Station();
        $resultData = $modelStation->getAll(array(), array(
            'sort'  => 'x.station_name',
            'order' => 'asc'
        ), array(
            'limit' => $args['last'],
            'start' => 0
        ));

        return $this->view->render($response, 'station.phtml', [
            'result' => $resultData
        ]);
    }

    public function stationDelete($request, $response, $args) {

        if($request->isPost()) {
            $arrayData = $request->getParsedBody();

            $modelStation = new Station();
            $station = $modelStation->getOne(array(
                'station_id' => $arrayData['id']
            ));

            if($station['station_active'] == 0) {
                return $response->withJson(array(
                    'status' => 'error'
                ), 200);
            }

            $modelStation->delete($station['station_id']);

            return $response->withJson(array(
                'status' => 'success'
            ), 200);
        }

        $modelStation = new Station();
        $modelStation->load((int)$args['id']);

        return $this->view->render($response, 'station-delete.phtml', [
            'result' => $modelStation->getData(),
            'id' => $args['id']
        ]);
    }

    public function view($request, $response, $args)
    {
        return $this->view->render($response, 'view.phtml', [
            'mapContent'  => $this->mapGet(true),
            'reservation' => $_GET['reservation']
        ]);
    }
}