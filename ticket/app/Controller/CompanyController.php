<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 21.02.17
 * Time: 10:43
 */

namespace Controller;
use \Company\Model\Company;
use \StationReservation\Model\StationReservation;

class CompanyController
{
    protected $view;

    // constructor receives container instance
    public function __construct($container) {
        $this->view = $container;
    }

    public function index($request, $response, $args) {

        $modelResult = new Company();
        $resultData = $modelResult->getAll(array(
        ), array(
            'sort' => 'company_date_register',
            'order' => 'desc'
        ), array(
            'limit' => $args['last'],
            'start' => 0
        ));

        foreach($resultData as $key => $row) {
            $resultData[$key]['company_name'] = $row['company_name'] . '<br />' . $row['company_nip'];
            $resultData[$key]['company_address'] = $row['company_address'] . ' ' . $row['company_address2'] . '<br />' . $row['company_zipcode'] . ' ' . $row['company_city'];
        }

        return $this->view->render($response, 'company.phtml', [
            'result' => $resultData
        ]);
    }

    public function edit($request, $response, $args) {

        $modelCompany = new Company();
        $modelCompany->load((int)$args['id']);

        if($request->isPost()) {
            $arrayData = $request->getParsedBody();

            if(array_key_exists('id', $args) && (int)$args['id'] > 0) {
                $id = $args['id'];
                $modelCompany->update($arrayData, $args['id']);
            } else {
                $arrayData['company_date_register'] = date('Y-m-d H:i:s');
                $id = $modelCompany->insert($arrayData);
            }

            return $response->withJson(array(
                'status' => 'success'
            ), 200);
        }

        return $this->view->render($response, 'company-edit.phtml', [
            'result' => $modelCompany->getData(),
            'id' => $args['id']
        ]);
    }

    public function delete($request, $response, $args) {

        if($request->isPost()) {
            $arrayData = $request->getParsedBody();

            $modelCompany = new Company();
            $company = $modelCompany->getOne(array(
                'company_id' => $arrayData['id']
            ));

            $modelStation = new StationReservation();
            $all = $modelStation->getAll(array(
                'x.company_id' => $company['company_id']
            ));

            if(is_array($all) && count($all) > 0 && !empty($all)) {
                return $response->withJson(array(
                    'status' => 'error'
                ), 200);
            }

            $modelCompany->delete($company['company_id']);

            return $response->withJson(array(
                'status' => 'success'
            ), 200);
        }

        $modelCompany = new Company();
        $modelCompany->load((int)$args['id']);

        return $this->view->render($response, 'company-delete.phtml', [
            'result' => $modelCompany->getData(),
            'id' => $args['id']
        ]);
    }
}