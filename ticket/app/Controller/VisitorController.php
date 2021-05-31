<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 21.02.17
 * Time: 12:47
 */

namespace Controller;
use Visitor\Model\Visitor;
use Payu\Model\Payment;
use Inpay\Service\Client;
use Payu\Model\LogPay;
use Ticket\Model\Type;
use Ticket\Model\Bon;
use Invoice\Model\Invoice;
use Visitor\Service\Ticket;

class VisitorController
{
    protected $view;
	public $warsztaty = array(
		'11' => 'TRADING (10:00-12:30)',
		'12' => 'KRYPTOKOPARKI (12:30-14:15)',
		'21' => 'PODATKI OD KRYPTOWALUT (15:00-17:15)',
		'22' => 'CRG2',
		'31' => 'CTG1',
		'32' => 'CTG2',
	);

    // constructor receives container instance
    public function __construct($container, $router)
    {
        $this->view = $container;
        $this->router = $router;
    }

    public function index($request, $response, $args)
    {
        /*$modelVisitor = new Visitor();
        $resultData = $modelVisitor->getAll(array(
            'parent_id' => 0
        ), array(
            'sort'  => 'visitor_registerdate',
            'order' => 'desc'
        ), array(
            'limit' => $args['last'],
            'start' => 0
        ));

		$modelTicketType = new Type();
		$rowTicket = $modelTicketType->getPair(array(), null, 'ticket_type_id', 'ticket_name');
		
		$modelBon = new Bon();
		$rowBon = $modelBon->getPair(array(), null, 'ticket_bon_id', 'bon_code');

        return $this->view->render($response, 'visitor.phtml', [
            'result' => $resultData,
			'ticket' => $rowTicket,
			'bon' => $rowBon,
			'warsztaty' => $this->warsztaty,
        ]);*/
		
		$modelVisitor = new Visitor();
        $resultData = $modelVisitor->getAll(array(
            'parent_id' => 0
        ), array(
            'sort'  => 'x.visitor_registerdate',
            'order' => 'desc'
        ), array(
            'limit' => $args['last'],
            'start' => 0
        ));
		
		$tmpDataParent = $modelVisitor->getAll(array(
            'x.parent_id_not' => 0
        ), array(
            'sort'  => 'x.visitor_registerdate',
            'order' => 'desc'
        ), array(
            'limit' => $args['last'],
            'start' => 0
        ));
		
		foreach($tmpDataParent as $row) {
			$resultDataParent[$row['parent_id']][] = $row;
		}

		$modelTicketType = new Type();
		$rowTicket = $modelTicketType->getPair(array(), null, 'ticket_type_id', 'ticket_name');

        return $this->view->render($response, 'visitor.phtml', [
            'result' => $resultData,
            'resultParent' => $resultDataParent,
			'ticket' => $rowTicket,
			'warsztaty' => $this->warsztaty,
        ]);
    }

    public function bonIndex($request, $response, $args)
    {
        $modelBon = new Bon();
        $resultData = $modelBon->getAll(array(), array(
            'sort'  => 'bon_start_date',
            'order' => 'desc'
        ), array(
            'limit' => $args['last'],
            'start' => 0
        ));

        return $this->view->render($response, 'bon.phtml', [
            'result' => $resultData,
        ]);
    }

    public function bonEdit($request, $response, $args)
    {
        $modelBon = new Bon();
        if(array_key_exists('id', $args)) {
            $modelBon->load((int)$args['id']);
            $data = $modelBon->getData();
        } else {
            $data['bon_start_date'] = date("Y-m-d H:i");
            $data['bon_end_date'] = date("Y-m-d H:i", time()+(7*24*60*60));
        }


        if($request->isPost()) {
            $arrayData = $request->getParsedBody();
            $tmpDate = explode(' - ', $arrayData['bon_start_date']);

            $arrayData['bon_start_date'] = $tmpDate[0];
            $arrayData['bon_end_date'] = $tmpDate[1];

            if(array_key_exists('id', $args) && (int)$args['id'] > 0) {
                $id = $args['id'];
                $modelBon->update($arrayData, $args['id']);
            } else {
               $id = $modelBon->insert($arrayData);
            }

            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->withJson(array(
                    'status' => 'success'
                ), 200);
        }
		
        return $this->view->render($response, 'bon-edit.phtml', [
            'result' => $data,
            'id' => $args['id']
        ]);
    }

    public function bonCheck($request, $response, $args)
    {
        $modelBon = new Bon();

        if($request->isPost()) {
            $arrayData = $request->getParsedBody();
            if($arrayData['bon_code']!= '') {
                $row = $modelBon->getOne(array(
                    'bon_code'          => strtoupper($arrayData['bon_code']),
                    'bon_active'        => 1,
                    'bon_start_date_do' => date("Y-m-d H:i:s"),
                    'bon_end_date_od' => date("Y-m-d H:i:s"),
                ));

                if (array_key_exists('ticket_bon_id', $row) && (int)$row['ticket_bon_id'] > 0) {
                    return $response
                        ->withHeader('Access-Control-Allow-Origin', '*')
                        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                        ->withJson(array(
                            'status'       => 'success',
                            'bon_id'       => $row['ticket_bon_id'],
                            'bon_discount' => $row['bon_discount']
                        ), 200);
                } else {
                    return $response
                        ->withHeader('Access-Control-Allow-Origin', '*')
                        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                        ->withJson(array(
                            'status' => 'error',
                            'komunikat' => 'Podany kod jest nieprawidłowy lub bon jest już nieaktywny'
                        ), 200);
                }
            } else {
                return $response
                    ->withHeader('Access-Control-Allow-Origin', '*')
                    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                    ->withJson(array(
                        'status' => 'error',
                        'komunikat' => 'Podany kod jest nieprawidłowy.'
                    ), 200);
            }
        }
    }

    public function edit($request, $response, $args)
    {
        $modelVisitor = new Visitor();
        $modelVisitor->load((int)$args['id']);

        if($request->isPost()) {
            $arrayData = $request->getParsedBody();

			$arrayData['ticket_additional'] = array();
			if($arrayData['additional11'] == 1) {
				$arrayData['ticket_additional'][] = '11';
			}
			if($arrayData['additional12'] == 1) {
				$arrayData['ticket_additional'][] = '12';
			}
			if($arrayData['additional21'] == 1) {
				$arrayData['ticket_additional'][] = '21';
			}
			
			$arrayData['ticket_additional'] = implode(',', $arrayData['ticket_additional']);
			
            if(array_key_exists('id', $args) && (int)$args['id'] > 0) {
                $id = $args['id'];
                $modelVisitor->update($arrayData, $args['id']);
            } else {
                $arrayData['visitor_registerdate'] = date('Y-m-d H:i:s');
                $arrayData['visitor_hash'] = sha1(\MyConfig::getValue('hashSecret') . time());
                if($arrayData['place'] == 'moderator') {
                    $arrayData['visitor_accepted'] = 1;
                    $arrayData['visitor_accepted_date'] = date('Y-m-d H:i:s');
                }

                $id = $modelVisitor->insert($arrayData);

                $qrFile = "/data/qr/qr_".md5($id).".png";
                \PHPQRCode\QRcode::png($arrayData['visitor_hash'], dirname(__FILE__) . '/../../public' .$qrFile, 'H', 6, 2);
                $modelVisitor->update(array(
                    'visitor_qr' => $qrFile
                ), $id);

                if($arrayData['place'] != 'moderator') {
                    $route = $this->router->get('router')->getNamedRoute("email_visitor");
                    $request = $this->router->get('request')->withAttribute('visitor', $arrayData);
                    $route->run($request, $this->router->get('response'), $arrayData);

                    $arrayData['visitor_id'] = $id;
                    $route = $this->router->get('router')->getNamedRoute("email_visitor_moderator");
                    $request = $this->router->get('request')->withAttribute('visitor', $arrayData);
                    $route->run($request, $this->router->get('response'), $arrayData);
                }
            }

            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->withJson(array(
                    'status' => 'success'
            ), 200);
        }
		
		$modelTicketType = new Type();
		$ticketList = $modelTicketType->getAll();

        return $this->view->render($response, 'visitor-edit.phtml', [
			'warsztaty' => $this->warsztaty,
			'ticketList' => $ticketList,
            'result' => $modelVisitor->getData(),
            'id' => $args['id']
        ]);
    }

    public function add($request, $response, $args)
    {
        $modelVisitor = new Visitor();

        if($request->isGet()) {
            $arrayData = $request->getQueryParams();

            $arrayData['visitor_registerdate'] = date('Y-m-d H:i:s');
            $arrayData['visitor_hash'] = sha1(\MyConfig::getValue('hashSecret') . time());
            $arrayData['visitor_ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if($arrayData['place'] == 'moderator') {
                $arrayData['visitor_accepted'] = 1;
                $arrayData['visitor_accepted_date'] = date('Y-m-d H:i:s');
            }
			
			if($arrayData['system_form'] == 'career') {
				if(is_array($arrayData['visitor_jezyk'])) {
					$arrayData['visitor_jezyk'] = implode(",", $arrayData['visitor_jezyk']);
				}
				
				if(is_array($arrayData['visitor_obszar'])) {
					$arrayData['visitor_obszar'] = implode(",", $arrayData['visitor_obszar']);
				}
				
				if(strlen($arrayData['visitor_firstname']) < 3 ||
				strlen($arrayData['visitor_lastname']) < 3 || 
				strlen($arrayData['visitor_company']) < 3 ||
				strlen($arrayData['visitor_email2']) < 3 ||
				strlen($arrayData['visitor_email']) < 3 ||
				strlen($arrayData['visitor_tel']) < 3 ||
				strlen($arrayData['visitor_stanowisko']) < 3 || 
				strlen($arrayData['visitor_obszar']) < 3 ||
				strlen($arrayData['visitor_jezyk']) < 3) {
					return $response
					->withHeader('Access-Control-Allow-Origin', '*')
					->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
					->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
					->withJson(array(
						"novalid" => true,
						"into" => "#wpcf7-f1190-p662-o2",
						"captcha" => null,
						"message" => "Proszę uzupełnić wszystkie pola."
					), 200);
				}
			} else {
				if(strlen($arrayData['visitor_firstname']) < 3 ||
				strlen($arrayData['visitor_lastname']) < 3 || 
				strlen($arrayData['visitor_company']) < 3 ||
				strlen($arrayData['visitor_company2']) < 3 ||
				strlen($arrayData['visitor_email']) < 3 ||
				strlen($arrayData['visitor_tel']) < 3) {
					return $response
					->withHeader('Access-Control-Allow-Origin', '*')
					->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
					->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
					->withJson(array(
						"novalid" => true,
						"into" => "#wpcf7-f1190-p662-o2",
						"captcha" => null,
						"message" => "Proszę uzupełnić wszystkie pola."
					), 200);
				}
			}

            $id = $modelVisitor->insert($arrayData);

            $qrFile = "/data/qr/qr_".md5($id).".png";
            \PHPQRCode\QRcode::png($arrayData['visitor_hash'], dirname(__FILE__) . '/../../public' .$qrFile, 'H', 6, 2);
            $modelVisitor->update(array(
                'visitor_qr' => $qrFile
            ), $id);

            if($arrayData['place'] != 'moderator') {
                $route = $this->router->get('router')->getNamedRoute("email_visitor");
                $request = $this->router->get('request')->withAttribute('visitor', $arrayData);
                $route->run($request, $this->router->get('response'), $arrayData);

                $arrayData['visitor_id'] = $id;
                $route = $this->router->get('router')->getNamedRoute("email_visitor_moderator");
                $request = $this->router->get('request')->withAttribute('visitor', $arrayData);
                $route->run($request, $this->router->get('response'), $arrayData);
            }

            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->withJson(array(
					"mailSent" => true,
					"into" => "#wpcf7-f1190-p662-o2",
					"captcha" => null,
					"message" => "Dziękujemy za dokonanie rejestracji."
                ), 200);
        }

        exit();
    }

    public function delete($request, $response, $args) {

        if($request->isPost()) {
            $arrayData = $request->getParsedBody();

            $modelVisitor = new Visitor();
            $modelVisitor->delete($arrayData['id']);

            return $response->withJson(array(
                'status' => 'success'
            ), 200);
        }

        $modelVisitor = new Visitor();
        $modelVisitor->load((int)$args['id']);

        return $this->view->render($response, 'visitor-delete.phtml', [
            'result' => $modelVisitor->getData(),
            'id' => $args['id']
        ]);
    }
	
	public function bonDelete($request, $response, $args) {

        if($request->isPost()) {
            $arrayData = $request->getParsedBody();

            $modelVisitor = new Bon();
            $modelVisitor->delete($arrayData['id']);

            return $response->withJson(array(
                'status' => 'success'
            ), 200);
        }

        $modelVisitor = new Bon();
        $modelVisitor->load((int)$args['id']);

        return $this->view->render($response, 'bon-delete.phtml', [
            'result' => $modelVisitor->getData(),
            'id' => $args['id']
        ]);
    }

    public function accept($request, $response, $args)
    {
        $modelVisitor = new Visitor();
        $modelVisitor->load((int)$args['id']);
        $visitorData = $modelVisitor->getData();

        if($visitorData['visitor_hash'] == $args['hash']) {

            $arrayData['visitor_accepted'] = 1;
            $arrayData['visitor_accepted_date'] = date('Y-m-d H:i:s');
			
			$arrayData['visitor_qr'] = "/data/qr/qr_a".md5($visitorData['visitor_id']).".png";
			\PHPQRCode\QRcode::png($visitorData['visitor_hash'], dirname(__FILE__) . '/../../public' .$arrayData['visitor_qr'], 'H', 6, 2);
			
            $modelVisitor->update($arrayData, $args['id']);

            $serviceTicket = new Ticket();
            
			$modelPayU = new Payment();			
			$rowPayment = $modelPayU->getOne(array(
				'visitor_id' => $visitorData['visitor_id']
			));
			
			$modelTicketType = new Type();
			$modelTicketType->load((int)$rowPayment['ticket_type_id']);
			$rowType = $modelTicketType->getData();
			
			$visitorData['ticket_name'] = $rowType['ticket_name'];
			$visitorData['ticket_additional'] = $rowPayment['ticket_type_additional'];
			$visitorData['ticket_price'] = $rowPayment['payment_amount']; 
			$visitorData['ticket_num'] = $rowPayment['ticket_num'];
			$visitorData['warsztaty'] = $this->warsztaty;

			$visitorData['pdf_ticket'] = $serviceTicket->generateTicket($visitorData, $this->view);
			$visitorData = $this->generateInvoice($visitorData, $this->view);
			//$visitorData['pdf_invoice'] = false;
			
			if($rowPayment['ticket_num'] > 1) {
				$additionalVisitorArray = $modelVisitor->getAll(array(
					'parent_id' => (int)$visitorData['visitor_id']
				));

				$visitorData['pdf_ticket'] = array($visitorData['pdf_ticket']);

				foreach($additionalVisitorArray as $additionalVisitor) {
					$modelVisitor->update(array(
						'visitor_accepted' => 1,
						'visitor_accepted_date' => date('Y-m-d H:i:s')
					), $additionalVisitor['visitor_id']);

					$additionalVisitor['ticket_name'] = $rowType['ticket_name'];
					$additionalVisitor['ticket_additional'] = $rowPayment['ticket_type_additional'];
					$additionalVisitor['ticket_price'] = $rowPayment['payment_amount'];
					$additionalVisitor['ticket_num'] = $rowPayment['ticket_num'];
					$additionalVisitor['warsztaty'] = $this->warsztaty;

					$visitorData['pdf_ticket'][] = $serviceTicket->generateTicket($additionalVisitor, $this->view);
				}
			}

            $route = $this->router->get('router')->getNamedRoute("email_visitor_accepted");
            $request = $this->router->get('request')->withAttribute('visitor', $visitorData);
            $route->run($request, $this->router->get('response'), $visitorData);
        }

        return $response->withJson(array(
            'status' => 'success'
        ), 200);
    }

    public function ticket($request, $response, $args)
    {
        $modelVisitor = new Visitor();
        $modelVisitor->load((int)$args['id']);
		$visitorData = $modelVisitor->getData();

		
		$visitor_id = $visitorData['visitor_id'];
		if($visitorData['parent_id'] > 1) {
			$additionalVisitorArray = $modelVisitor->getOne(array(
				'visitor_id' => (int)$visitorData['parent_id']
			));
			$visitorData['visitor_id'] = $additionalVisitorArray['visitor_id'];
		}
		
		$modelPayU = new Payment();
		$rowPayment = $modelPayU->getOne(array(
			'visitor_id' => $visitorData['visitor_id']
		));
		
		$modelTicketType = new Type();
		$modelTicketType->load((int)$rowPayment['ticket_type_id']);
		$rowType = $modelTicketType->getData();
		
		$visitorData['ticket_name'] = $rowType['ticket_name'];
		$visitorData['ticket_additional'] = $rowPayment['ticket_type_additional'];
		$visitorData['ticket_price'] = $rowPayment['payment_amount']; 
		$visitorData['ticket_num'] = $rowPayment['ticket_num'];
		$visitorData['warsztaty'] = $this->warsztaty;
		
		if($visitorData['visitor_qr'] == '') {
			$visitorData['visitor_qr'] = "/data/qr/qr_a".md5($visitor_id).".png";
		}
		\PHPQRCode\QRcode::png($visitorData['visitor_hash'], dirname(__FILE__) . '/../../public' .$visitorData['visitor_qr'], 'H', 6, 2);

        $serviceTicket = new Ticket();
        $ticketFile = $serviceTicket->generateTicket($visitorData, $this->view);

        $quoted = sprintf('"%s"', addcslashes(basename($ticketFile), '"\\'));
        $size   = filesize($ticketFile);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $quoted);
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . $size);
        readfile($ticketFile);
        exit();

    }
	
	public function invoice($request, $response, $args)
    {
       		
		$modelVisitor = new Visitor();
        $modelVisitor->load((int)$args['id']);
		
		$visitorData = $modelVisitor->getData();
		
		$modelPayU = new Payment();
		$rowPayment = $modelPayU->getOne(array(
			'visitor_id' => $visitorData['visitor_id']
		));

		$modelTicketType = new Type();
		$modelTicketType->load((int)$rowPayment['ticket_type_id']);
		$rowType = $modelTicketType->getData();
		
		$visitorData['ticket_name'] = $rowType['ticket_name'];
		$visitorData['ticket_additional'] = $rowPayment['ticket_type_additional'];
		$visitorData['ticket_price'] = $rowPayment['payment_amount']; 
		$visitorData['ticket_num'] = $rowPayment['ticket_num'];

        $ticketFile = $this->generateInvoice($visitorData);

        $quoted = sprintf('"%s"', addcslashes(basename($ticketFile['pdf_invoice']), '"\\'));
        $size   = filesize($ticketFile['pdf_invoice']);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $quoted);
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . $size);
        readfile($ticketFile['pdf_invoice']);
        exit();
    }

    public function buyTicket($request, $response, $args) {

        if($args['type'] == 'success') {
            return $this->view->render($response, 'buy-ticket-success.phtml');
        }

		$db = \Db\Db::getInstance();
		$query = $db->query('SELECT COUNT(*) as ilosc FROM planning_payu_payment WHERE ticket_type_additional LIKE ("%11%") AND payment_success = 1');
		$row = $query->fetchAll();
		
		if(is_array($row) && $row[0]['ilosc'] > 0) {
			$additional11 = 50-(int)$row[0]['ilosc'];
		} else {
			$additional11 = 50;
		}
		
		$query = $db->query('SELECT COUNT(*) as ilosc FROM planning_payu_payment WHERE ticket_type_additional LIKE ("%12%") AND payment_success = 1');
		$row = $query->fetchAll();
		
		if(is_array($row) && $row[0]['ilosc'] > 0) {
			$additional12 = 50-(int)$row[0]['ilosc'];
		} else {
			$additional12 = 50;
		}
		
		$query = $db->query('SELECT COUNT(*) as ilosc FROM planning_payu_payment WHERE ticket_type_additional LIKE ("%21%") AND payment_success = 1');
		$row = $query->fetchAll();
		
		if(is_array($row) && $row[0]['ilosc'] > 0) {
			$additional21 = 50-(int)$row[0]['ilosc'];
		} else {
			$additional21 = 50;
		}
		
		$query = $db->query('SELECT COUNT(*) as ilosc FROM planning_payu_payment WHERE ticket_type_additional = 22 AND payment_success = 1');
		$row = $query->fetchAll();
		
		if(is_array($row) && $row[0]['ilosc'] > 0) {
			$additional22 = 50-(int)$row[0]['ilosc'];
		} else {
			$additional22 = 50;
		}
		
		$query = $db->query('SELECT COUNT(*) as ilosc FROM planning_payu_payment WHERE ticket_type_additional = 31 AND payment_success = 1');
		$row = $query->fetchAll();
		
		if(is_array($row) && $row[0]['ilosc'] > 0) {
			$additional31 = 50-(int)$row[0]['ilosc'];
		} else {
			$additional31 = 50;
		}
		
		$query = $db->query('SELECT COUNT(*) as ilosc FROM planning_payu_payment WHERE ticket_type_additional = 32 AND payment_success = 1');
		$row = $query->fetchAll();
		
		if(is_array($row) && $row[0]['ilosc'] > 0) {
			$additional32 = 50-(int)$row[0]['ilosc'];
		} else {
			$additional32 = 50;
		}
		
        $modelTicketType = new Type();

        if($request->isPost()) {
            $arrayData = $request->getParsedBody();
			
			if($arrayData['visitor_lastname'] == '' || $arrayData['visitor_firstname'] == '' || $arrayData['visitor_email'] == '' || $arrayData['visitor_tel'] == '') {
				header('Location: /?error=1');
			}

            $modelVisitor = new Visitor();
            $arrayData['visitor_registerdate'] = date('Y-m-d H:i:s');
            $arrayData['visitor_hash'] = sha1(\MyConfig::getValue('hashSecret') . time());
            $arrayData['visitor_id'] = $id = $modelVisitor->insert($arrayData);
			
			$qrFile = "/data/qr/qr_".md5($arrayData['visitor_id']).".png";
			\PHPQRCode\QRcode::png($arrayData['visitor_hash'], dirname(__FILE__) . '/../../public' .$qrFile, 'H', 6, 2);
			
			$modelVisitor->update(array(
				'visitor_qr' => $qrFile
			), $arrayData['visitor_id']);

            /**
             * Dodajemy powiązane bilety
             */
			if($arrayData['ticket_num'] > 1) {
                foreach($arrayData['visitor2_firstname'] as $key => $visitor) {
                    $tmpId = $modelVisitor->insert(array(
                        'visitor_firstname' => $visitor,
                        'visitor_lastname' => $arrayData['visitor2_lastname'][$key],
                        'visitor_email' => $arrayData['visitor_email'],
                        'visitor_tel' => $arrayData['visitor_tel'],
                        'parent_id' => $id,
                        'visitor_hash' => $arrayData['visitor_hash'] . '_' . $key,
                        'visitor_registerdate' => date('Y-m-d H:i:s'),
                    ));

                    $qrFile = "/data/qr/qr_".md5($tmpId).".png";
                    \PHPQRCode\QRcode::png($arrayData['visitor_hash'], dirname(__FILE__) . '/../../public' .$qrFile, 'H', 6, 2);

                    $modelVisitor->update(array(
                        'visitor_qr' => $qrFile
                    ), $tmpId);
                }
            }
			
			$additionalPrice = 0;
			//if($arrayData['ticket_type_id'] != 5 && $arrayData['ticket_additional'] == 1) {
			//	$additionalPrice = 150;
			//}
			$arrayData['ticket_additional'] = array();
			if($arrayData['additional11'] == 1) {
				$arrayData['ticket_additional'][] = '11';
			}
			if($arrayData['additional12'] == 1) {
				$arrayData['ticket_additional'][] = '12';
			}
			if($arrayData['additional21'] == 1) {
				$arrayData['ticket_additional'][] = '21';
			}
			if($arrayData['additional22'] == 1) {
				$arrayData['ticket_additional'][] = '22';
			}
			if($arrayData['additional31'] == 1) {
				$arrayData['ticket_additional'][] = '31';
			}
			if($arrayData['additional32'] == 1) {
				$arrayData['ticket_additional'][] = '32';
			}
			
			$arrayData['ticket_additional'] = implode(',', $arrayData['ticket_additional']);
			
			if($arrayData['bitcoin'] == 1) {
				
				$modelInpay = new Client();
				$modelInpay->init(array(
					'apiKey' => 'f99f88005a03847a09880471e5ef7f90',
					'callbackUrl' => 'https://ticket.cryptoexpo.pl/kup-bilet-retrieve-inpay',
					'successUrl' => 'https://ticket.cryptoexpo.pl/kup-bilet/success',
					'customerEmail' => $arrayData['visitor_email'],
					'description' => 'Bilet wstępu na Targi Crypto Future Expo 2018',
				));
				

				$res = $modelInpay->invoiceCreate((float)$arrayData['price_amount']);
				if($res['messageType'] == 'success' && $res['success']) {
					$modelPayU = new Payment();
					$modelPayU->insert(array(
						'order_id' => $res['invoiceCode'],
						'payment_date' => date('Y-m-d H:i:s'),
						'visitor_id' => $id,
						'payment_amount' => $arrayData['price_amount'],
						'ticket_type_id' => $arrayData['ticket_type_id'],
						'ticket_type_additional' => $arrayData['ticket_additional'],
						'bon_id' => $arrayData['bon_id'],
						'bon_discount' => $arrayData['bon_discount'],
						'ticket_num' => $arrayData['ticket_num'],
					));
					
					header('Location:' . $res['redirectUrl']);
				}
				
				
			} else {

				\OpenPayU_Configuration::setEnvironment('secure');

				//set POS ID and Second MD5 Key (from merchant admin panel)
				\OpenPayU_Configuration::setMerchantPosId(\MyConfig::getValue('payU_merchant'));
				\OpenPayU_Configuration::setSignatureKey(\MyConfig::getValue('payU_signature'));

				//set Oauth Client Id and Oauth Client Secret (from merchant admin panel)
				\OpenPayU_Configuration::setOauthClientId(\MyConfig::getValue('payU_oAuthId'));
				\OpenPayU_Configuration::setOauthClientSecret(\MyConfig::getValue('payU_oAuthSecret'));

				$ticketNameArray = $modelTicketType->getPair(array(), null, null, 'ticket_name');
				$ticketPriceArray = $modelTicketType->getPair(array(), null, null, 'ticket_price');

				$paymentResponse = $this->processPayment($arrayData, array(
					'extOrderId' => $id . '_' . $arrayData['ticket_type_id'] . '_KK2',
					'ticketName' => $ticketNameArray[$arrayData['ticket_type_id']],
					'ticketPrice' => $ticketPriceArray[$arrayData['ticket_type_id']],
					'ticketAdditional' => $arrayData['ticket_additional'],
					'ticketAdditionalPrice' => $additionalPrice,
					'price_amount' => $arrayData['price_amount'],
					'bon_id' => $arrayData['bon_id'],
					'bon_discount' => $arrayData['bon_discount'],
					'ticket_num' => $arrayData['ticket_num'],
				));

				if($paymentResponse) {
					header('Location:' . $paymentResponse->getResponse()->redirectUri);
				}
			}
        }

		$args['type'] = array_key_exists('type', $args) ? $args['type'] : 1;
		
		$ticketList = $modelTicketType->getAll();
		foreach($ticketList as $row) { if($row['ticket_type_id'] == $args['type']) {$ticketName = $row['ticket_name']; $ticketPrice = $row['ticket_price'];}}

        return $this->view->render($response, 'buy-ticket.phtml', [
            'ticketType' => $args['type'],
            'ticketPrice' => $ticketPrice,
            'ticketList' => $ticketList,
			'additional11' => $additional11,
			'additional12' => $additional12,
			'additional21' => $additional21,
			'additional22' => $additional22,
			'additional31' => $additional31,
			'additional32' => $additional32,
        ]);
	
    }

    private function processPayment($userData, $ticketData) {

        $order['continueUrl'] = 'https://ticket.cryptoexpo.pl/kup-bilet/success'; //customer will be redirected to this page after successfull payment
        $order['notifyUrl'] = 'https://ticket.cryptoexpo.pl/kup-bilet-retrieve';
        $order['customerIp'] = $_SERVER['REMOTE_ADDR'];
        $order['merchantPosId'] = \OpenPayU_Configuration::getMerchantPosId();
        $order['description'] = 'Biletu wstepu na Targi Crypto Future Expo 2018';
        $order['currencyCode'] = 'PLN';
        $order['totalAmount'] = $ticketData['price_amount']*100;
        $order['extOrderId'] = $ticketData['extOrderId']; //must be unique!

        $order['settings']['invoiceDisabled'] = true;
		
        $order['products'][0]['name'] = $ticketData['ticketName'];
        $order['products'][0]['unitPrice'] = $ticketData['price_amount']*100;
        $order['products'][0]['quantity'] = 1;


        //optional section buyer
        $order['buyer']['email'] = $userData['visitor_email'];
        $order['buyer']['phone'] = $userData['visitor_tel'];
        $order['buyer']['firstName'] = $userData['visitor_firstname'];
        $order['buyer']['lastName'] = $userData['visitor_lastname'];

        $response = \OpenPayU_Order::create($order);

        if($response->getStatus() == 'SUCCESS') {
            
            $modelPayU = new Payment();
            $modelPayU->insert(array(
                'order_id' => $response->getResponse()->orderId,
                'payment_date' => date('Y-m-d H:i:s'),
                'visitor_id' => $userData['visitor_id'],
                'payment_amount' => $ticketData['price_amount'],
                'ticket_type_id' => $userData['ticket_type_id'],
                'ticket_type_additional' => $ticketData['ticketAdditional'],
                'bon_id' => $ticketData['bon_id'],
                'bon_discount' => $ticketData['bon_discount'],
                'ticket_num' => $ticketData['ticket_num'],
            ));

			header('Location:' . $response->getResponse()->redirectUri);
			exit();
            return $response;
        }

        return false;
    }

    public function buyTicketRetrieve($request, $response, $args) {
		
		if($request->isPost()) { 
            $orderPost = json_decode(trim($request->getBody()));
	
			\OpenPayU_Configuration::setEnvironment('secure');

            //set POS ID and Second MD5 Key (from merchant admin panel)
            \OpenPayU_Configuration::setMerchantPosId(\MyConfig::getValue('payU_merchant'));
            \OpenPayU_Configuration::setSignatureKey(\MyConfig::getValue('payU_signature'));

            //set Oauth Client Id and Oauth Client Secret (from merchant admin panel)
            \OpenPayU_Configuration::setOauthClientId(\MyConfig::getValue('payU_oAuthId'));
            \OpenPayU_Configuration::setOauthClientSecret(\MyConfig::getValue('payU_oAuthSecret'));
	
			try {
				if (!empty($orderPost)) {
					//$result = \OpenPayU_Order::consumeNotification($arrayData);
				
					if ($orderPost->order->orderId) {
						
						$orderId = $orderPost->order->orderId;
						$order = \OpenPayU_Order::retrieve($orderId);

						$modelPayULog = new LogPay();
						$row = $modelPayULog->getOne(array(
							'order_id' => $orderId,
							'order_status' => 'COMPLETED'
						), array(
							'order' => 'DESC',
							'sort'=> 'log_date',
						));
						
						$modelPayULog->insert(array(
							'log_date' => date('Y-m-d H:i:s'),
							'order_id' => $orderId,
							'order_status' => $orderPost->order->status,
							'order_amount' => (int)($orderPost->order->totalAmount)/100
						));
							
						if(!is_array($row) || count($row) < 1 || !array_key_exists('order_status', $row)) {

							if($orderPost->order->status == 'COMPLETED'){
								
								$modelPayU = new Payment();
								$modelPayU->updateWhere(array(
									'payment_success' => 1,
									'payment_success_date' => date('Y-m-d H:i:s'),
								), "order_id = '" . $orderId . "' ");
								
								$rowPayment = $modelPayU->getOne(array(
									'order_id' => $orderId
								));
								
								$modelVisitor = new Visitor();
								$modelVisitor->load((int)$rowPayment['visitor_id']);
								$visitorData = $modelVisitor->getData();
								
								$modelVisitor->update(array(
									'visitor_accepted' => 1,
									'visitor_accepted_date' => date('Y-m-d H:i:s')
								), $visitorData['visitor_id']);

								$modelTicketType = new Type();
								$modelTicketType->load((int)$rowPayment['ticket_type_id']);
								$rowType = $modelTicketType->getData();
								
								$visitorData['ticket_name'] = $rowType['ticket_name'];
								$visitorData['ticket_additional'] = $rowPayment['ticket_type_additional'];
								$visitorData['ticket_price'] = $rowPayment['payment_amount']; 
								$visitorData['ticket_num'] = $rowPayment['ticket_num'];
								$visitorData['warsztaty'] = $this->warsztaty;

								$serviceTicket = new Ticket();
								$visitorData['pdf_ticket'] = $serviceTicket->generateTicket($visitorData, $this->view);
								$visitorData = $this->generateInvoice($visitorData, $this->view);

								if($rowPayment['ticket_num'] > 1) {
                                    $additionalVisitorArray = $modelVisitor->getAll(array(
                                        'parent_id' => (int)$rowPayment['visitor_id']
                                    ));

                                    $visitorData['pdf_ticket'] = array($visitorData['pdf_ticket']);

                                    foreach($additionalVisitorArray as $additionalVisitor) {
                                        $modelVisitor->update(array(
                                            'visitor_accepted' => 1,
                                            'visitor_accepted_date' => date('Y-m-d H:i:s')
                                        ), $additionalVisitor['visitor_id']);

                                        $additionalVisitor['ticket_name'] = $rowType['ticket_name'];
                                        $additionalVisitor['ticket_additional'] = $rowPayment['ticket_type_additional'];
                                        $additionalVisitor['ticket_price'] = $rowPayment['payment_amount'];
                                        $additionalVisitor['ticket_num'] = $rowPayment['ticket_num'];
										$additionalVisitor['warsztaty'] = $this->warsztaty;

                                        $visitorData['pdf_ticket'][] = $serviceTicket->generateTicket($additionalVisitor, $this->view);
                                    }
                                }

								$route = $this->router->get('router')->getNamedRoute("email_visitor_accepted");
								$request = $this->router->get('request')->withAttribute('visitor', $visitorData);
								$route->run($request, $this->router->get('response'), $visitorData);
							}
						}
						
						header("HTTP/1.1 200 OK");
					}
				}
			} catch (\OpenPayU_Exception $e) {
				echo $e->getMessage();
			}
		}
    }
	
	public function buyTicketRetrieveInpay($request, $response, $args) {
		
		if($request->isPost()) { 
            $orderPost = $request->getParsedBody();

			try {
				if (!empty($orderPost)) {
					//$result = \OpenPayU_Order::consumeNotification($arrayData);
				
					if ($orderPost['invoiceCode']) {

						$modelPayULog = new LogPay();
						$row = $modelPayULog->getOne(array(
							'order_id' => $orderPost['invoiceCode'],
							'order_status' => 'confirmed'
						), array(
							'order' => 'DESC',
							'sort'=> 'log_date',
						));
						
						$modelPayULog->insert(array(
							'log_date' => date('Y-m-d H:i:s'),
							'order_id' => $orderPost['invoiceCode'],
							'order_status' => $orderPost['status'],
							'order_amount' => $orderPost['amount']
						));
							
						if(!is_array($row) || count($row) < 1 || !array_key_exists('order_status', $row)) {

							if($orderPost['status'] == 'confirmed'){
								
								$modelPayU = new Payment();
								$modelPayU->updateWhere(array(
									'payment_success' => 1,
									'payment_success_date' => date('Y-m-d H:i:s'),
								), "order_id = '" . $orderPost['invoiceCode'] . "' ");
								
								$rowPayment = $modelPayU->getOne(array(
									'order_id' => $orderPost['invoiceCode']
								));
								
								$modelVisitor = new Visitor();
								$modelVisitor->load((int)$rowPayment['visitor_id']);
								$visitorData = $modelVisitor->getData();
								
								$modelVisitor->update(array(
									'visitor_accepted' => 1,
									'visitor_accepted_date' => date('Y-m-d H:i:s')
								), $visitorData['visitor_id']);

								$modelTicketType = new Type();
								$modelTicketType->load((int)$rowPayment['ticket_type_id']);
								$rowType = $modelTicketType->getData();
								
								$visitorData['ticket_name'] = $rowType['ticket_name'];
								$visitorData['ticket_additional'] = $rowPayment['ticket_type_additional'];
								$visitorData['ticket_price'] = $rowPayment['payment_amount']; 
								$visitorData['ticket_num'] = $rowPayment['ticket_num'];
								$visitorData['warsztaty'] = $this->warsztaty;

								$serviceTicket = new Ticket();
								$visitorData['pdf_ticket'] = $serviceTicket->generateTicket($visitorData, $this->view);
								$visitorData = $this->generateInvoice($visitorData, $this->view);

								if($rowPayment['ticket_num'] > 1) {
                                    $additionalVisitorArray = $modelVisitor->getAll(array(
                                        'parent_id' => (int)$rowPayment['visitor_id']
                                    ));

                                    $visitorData['pdf_ticket'] = array($visitorData['pdf_ticket']);

                                    foreach($additionalVisitorArray as $additionalVisitor) {
                                        $modelVisitor->update(array(
                                            'visitor_accepted' => 1,
                                            'visitor_accepted_date' => date('Y-m-d H:i:s')
                                        ), $additionalVisitor['visitor_id']);

                                        $additionalVisitor['ticket_name'] = $rowType['ticket_name'];
                                        $additionalVisitor['ticket_additional'] = $rowPayment['ticket_type_additional'];
                                        $additionalVisitor['ticket_price'] = $rowPayment['payment_amount'];
                                        $additionalVisitor['ticket_num'] = $rowPayment['ticket_num'];
										$additionalVisitor['warsztaty'] = $this->warsztaty;

                                        $visitorData['pdf_ticket'][] = $serviceTicket->generateTicket($additionalVisitor, $this->view);
                                    }
                                }

								$route = $this->router->get('router')->getNamedRoute("email_visitor_accepted");
								$request = $this->router->get('request')->withAttribute('visitor', $visitorData);
								$route->run($request, $this->router->get('response'), $visitorData);
							}
						}
						
						header("HTTP/1.1 200 OK");
					}
				}
			} catch (\OpenPayU_Exception $e) {
				echo $e->getMessage();
			}
		}
    }
	
	private function generateInvoice($data)
    {
        $modelInvoice = new Invoice();
        $invoiceRecord = $modelInvoice->getOne(array(
            'invoice_visitor_id' => $data['visitor_id']
        ));

        if(!array_key_exists('invoice_id', $invoiceRecord)) {
            $db = \Db\Db::getInstance();
            $query = $db->query('SELECT max(invoice_num) as max_num FROM planning_invoice WHERE invoice_fv = ' . ($data['visitor_nip'] != '' ? 1 : 0) . ' AND MONTH(invoice_date) = "'.date('n').'" LIMIT 1');
            $row = $query->fetchAll();

			if(is_array($row) && $row[0]['max_num'] > 0) {
				$invoiceMax = (int)$row[0]['max_num']+1;
			} else {
				$invoiceMax = 1;
			}

            if($data['visitor_nip'] != '') {
                $data['invoice_name'] = $invoiceMax . '/CFEF/' . date('m') . '/' . date('Y');
            } else {
                $data['invoice_name'] = $invoiceMax . '/CFEP/' . date('m') . '/' . date('Y');
            }

            $modelInvoice->insert(array(
                'invoice_name' => $data['invoice_name'],
                'invoice_num' => $invoiceMax,
                'invoice_fv' => ($data['visitor_nip'] != '' ? 1 : 0),
                'invoice_visitor_id' => $data['visitor_id'],
                'invoice_file' => 'faktura_' . str_replace('/', '_', $data['invoice_name']) . '.pdf',
            ));
        } else {
            $data['invoice_name'] = $invoiceRecord['invoice_name']; 
        }

        $serviceTicket = new Ticket();
		$data['pdf_invoice'] = $serviceTicket->generateInvoice($data, $this->view);
		
		return $data;
	}
	
	public function sendForget($request, $response, $args) {

		$filter = array(
			'visitor_type' => 1,
			'visitor_accepted' => 1
		);
				
		$modelVisitor = new Visitor();
		$resultData = $modelVisitor->getAll($filter, array(
            'sort'  => 'visitor_lastname',
            'order' => 'asc'
        ), array(
            'limit' => $args['last'],
            'start' => 0
        ));
		
		$warsztaty = array();
		foreach($resultData as $visitorData) {
			if($visitorData['payment']['payment_success'] == 1 && $visitorData['payment']['ticket_type_id'] == 2 && $visitorData['payment']['ticket_type_additional'] != '') {
				$tmp = explode(',', $visitorData['payment']['ticket_type_additional']);
				foreach($tmp as $row) {
					$warsztaty[$row][] = array(
						'imie' => $visitorData['visitor_firstname'],
						'nazwisko' => $visitorData['visitor_lastname'],
						'email' => $visitorData['visitor_email']
					);
				}
			}
		}
		
		$file = 'warsztaty_' . date("Y-m-d_H:i:s") . '.csv';
        $filename = dirname(__FILE__) . '/../../public/data/csv/' . $file;
		$fp = fopen($filename, 'w');
		
		fputcsv($fp, array(
			'warsztaty',
			'nazwisko',
			'imie',
			'email'
		),';');
		
		foreach($warsztaty as $key => $row) {
			foreach($row as $row2) {
				
				$fields = array(); 
				$fields['warsztaty'] = $this->warsztaty[$key];
				$fields['nazwisko'] = $row2['nazwisko'];
				$fields['imie'] = $row2['imie'];
				$fields['email'] = $row2['email'];

				fputcsv($fp, $fields,';');
			}
		}
	
		fclose($fp);

        $size   = filesize($filename);
        $quoted = sprintf('"%s"', addcslashes(basename($filename), '"\\'));

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $quoted);
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . $size);
        readfile($filename);
        exit();
	
		/*
		$serviceTicket = new Ticket();
		$modelTicketType = new Type();
		$modelTicketType->load((int)$rowPayment['ticket_type_id']);
		$rowType = $modelTicketType->getData();
				
		
		foreach($resultData as $visitorData) {
			if($visitorData['payment']['payment_success'] == 1) { $visitorData['visitor_email'] = 't.cisowski@gmail.com';

				$visitorData['ticket_name'] = $rowType['ticket_name'];
				$visitorData['ticket_additional'] = $visitorData['payment']['ticket_type_additional'];
				$visitorData['ticket_price'] = $visitorData['payment']['payment_amount']; 
				$visitorData['ticket_num'] = $visitorData['payment']['ticket_num'];
				$visitorData['warsztaty'] = $this->warsztaty;

				$visitorData['pdf_ticket'] = $serviceTicket->generateTicket($visitorData, $this->view);
				$visitorData = $this->generateInvoice($visitorData, $this->view);
				//$visitorData['pdf_invoice'] = false;
				
				if($rowPayment['ticket_num'] > 1) {
					$additionalVisitorArray = $modelVisitor->getAll(array(
						'parent_id' => (int)$visitorData['visitor_id']
					));

					$visitorData['pdf_ticket'] = array($visitorData['pdf_ticket']);

					foreach($additionalVisitorArray as $additionalVisitor) {
						$modelVisitor->update(array(
							'visitor_accepted' => 1,
							'visitor_accepted_date' => date('Y-m-d H:i:s')
						), $additionalVisitor['visitor_id']);

						$additionalVisitor['ticket_name'] = $rowType['ticket_name'];
						$additionalVisitor['ticket_additional'] = $visitorData['payment']['ticket_type_additional'];
						$additionalVisitor['ticket_price'] = $visitorData['payment']['payment_amount'];
						$additionalVisitor['ticket_num'] = $visitorData['payment']['ticket_num'];
						$additionalVisitor['warsztaty'] = $this->warsztaty;

						$visitorData['pdf_ticket'][] = $serviceTicket->generateTicket($additionalVisitor, $this->view);
					}
				}

				$route = $this->router->get('router')->getNamedRoute("email_visitor_accepted");
				$request = $this->router->get('request')->withAttribute('visitor', $visitorData);
				$route->run($request, $this->router->get('response'), $visitorData); exit();
			}
		}
		*/
	}
	
	public function getCsv($request, $response, $args) {


		$modelVisitor = new Visitor();
	
        $file = 'file_' . date("Y-m-d_H:i:s") . '.csv';
        $filename = dirname(__FILE__) . '/../../public/data/csv/' . $file;
        $fp = fopen($filename, 'w');
		
		$modelTicketType = new Type();
		$rowTicket = $modelTicketType->getPair(array(), null, 'ticket_type_id', 'ticket_name');
		
		$resultData = $modelVisitor->getAll(array('visitor_accepted' > 1), array(
            'sort'  => 'visitor_registerdate',
            'order' => 'desc'
        ), array(
            'limit' => 9999,
            'start' => 0
        ));
		
		fputcsv($fp, array(
			'email',
			'imie',
			'nazwisko',
			'telefon',
			'data_rejestracji',
			'aktywny',
			'firma',
			'typ_usera',
			'bilet',
			'warsztaty',
		),';');

        foreach ($resultData as $row) {
					
			$fields = array();
			$fields['email'] = $row['visitor_email'];
			$fields['imie'] = $row['visitor_firstname'];
			$fields['nazwisko'] = $row['visitor_lastname'];
			$fields['telefon'] = $row['visitor_tel'];
			$fields['data_rejestracji'] = $row['visitor_registerdate'];	
			$fields['aktywny'] = $row['visitor_accepted'];
			$fields['firma'] = $row['visitor_company'];
			$fields['typ_usera'] = $row['visitor_type'];
			$fields['bilet'] = $rowTicket[$row['payment']['ticket_type_id']];
			$warString = '';
			if($row['payment']['ticket_type_additional'] != '') { 
				$tmpWarsztaty = explode(',', $row['payment']['ticket_type_additional']); 
				foreach($tmpWarsztaty as $war) { 
					$warString .= $this->warsztaty[$war] . ' '; 
				}
			}
			$fields['warsztaty'] = $warString;
			
            fputcsv($fp, $fields,';');
        }

        fclose($fp);

        $size   = filesize($filename);
        $quoted = sprintf('"%s"', addcslashes(basename($filename), '"\\'));

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $quoted);
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . $size);
        readfile($filename);
        exit();

    }

}