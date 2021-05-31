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
use Ticket\Model\Type;
use Invoice\Model\Invoice;
use Visitor\Service\Ticket;

class OperatorController
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
    public function __construct($container, $router, $role)
    {
        $this->view = $container;
        $this->router = $router;
        $this->role = $role;
    }

    public function buyTicket($request, $response, $args)
    {

        if ($args['type'] == 'success') {
            return $this->view->render($response, 'buy-ticket-success.phtml');
        }

        $modelTicketType = new Type();
        $modelVisitor = new Visitor();

        if ($request->isPost()) {
            $arrayData = $request->getParsedBody();

            if ($arrayData['step'] > 1) {

                //potwierdzenie wybrania rodzaju płatności i przyjecie pieniędzy
                if ($arrayData['step'] == 3) {

                    $this->paymentRetrieve((int)$arrayData['visitor_id'], (int)$arrayData['payment_type']);
                    if ((int)$arrayData['payment_type'] == 3) {
                        header('Location: /operator');
                        exit();
                    }

                    $modelVisitor->load((int)$arrayData['visitor_id']);
                    $visitorData = $modelVisitor->getData();

                    return $this->view->render($response, 'operator/3.phtml', [
                        'visitor_id' => $arrayData['visitor_id'],
                        'email' => $visitorData['visitor_email'],
                    ]);
                }

                //potwierdzenie danych i drukowanie biletów
                if ($arrayData['step'] == 4) {

                    $arrayData = $this->generateDocuments($arrayData);
                    $opaskaArray = $this->getColor($arrayData['ticket_type_id'], $arrayData['ticket_additional']);

                    $arrayData['opaska_color'] = $opaskaArray['color'];
                    $arrayData['opaska_class'] = $opaskaArray['class'];

                    $pdfFiles = array();
                    if(array_key_exists('pdf_invoice', $arrayData)) {
                        $pdfFiles[] = $arrayData['pdf_invoice'];
                    }

                    if($arrayData['visitor_email'] == '') {
                        foreach($arrayData['pdf_ticket'] as $ticket) {
                            $pdfFiles[] = $ticket;
                        }
                    }

                    if(is_array($pdfFiles) && count($pdfFiles) > 0 && !empty($pdfFiles)) {
                        $serviceTicket = new Ticket();
                        $tmpPdf = $serviceTicket->mergePDFFiles($pdfFiles, $arrayData['visitor_id']);
                        $tmpPdf = explode('/', $tmpPdf);
                        $arrayData['pdf'] = $tmpPdf[(count($tmpPdf)-1)];
                    }

                    return $this->view->render($response, 'operator/4.phtml', [
                        'visitor_id' => $arrayData['visitor_id'],
                        'ticketData' => $arrayData
                    ]);
                }

                // wydanie opaski i przeładowanie strony
                if ($arrayData['step'] == 5) {

                    if(is_array($arrayData) && array_key_exists('visitor_id', $arrayData)) {

                        $modelVisitor->load((int)$arrayData['visitor_id']);
						
						$modelTicket = new \Ticket\Model\Ticket();
                        $insertData = array(
                            'visitor_id' => $arrayData['visitor_id'],
                            'register_id' => 125,
                            'place_id' => 1,
                            'ticket_color' => $arrayData['kolor'],
                            'company_id' => null,
                        );

                        $modelTicket->insert($insertData);

                        $parentData = $modelVisitor->getAll(array('x.parent_id' => $arrayData['visitor_id']), array(
                            'sort'  => 'x.visitor_registerdate',
                            'order' => 'desc'
                        ));
                        if(is_array($parentData) && count($parentData) > 0 && !empty($parentData)) {
                            foreach($parentData as $parent) {
                                $insertData = array(
                                    'visitor_id' => $parent['visitor_id'],
                                    'register_id' => 125,
                                    'place_id' => 1,
                                    'ticket_color' => $arrayData['kolor'],
                                    'company_id' => null,
                                );

                                $modelTicket->insert($insertData);
                            }
                        }

                        return $this->view->render($response, 'operator/5.phtml', [
                            'kolor' => $arrayData['kolor'],
                            'visitorData' => $modelVisitor->getData()
                        ]);


                    }
                }


                return $this->view->render($response, 'operator/' . $arrayData['step'] . '.phtml', [
                    'ticketdata' => $arrayData,
                ]);
            }

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

            $arrayData['visitor_registerdate'] = date('Y-m-d H:i:s');
            $arrayData['visitor_hash'] = sha1(\MyConfig::getValue('hashSecret') . time());
            $arrayData['visitor_kasa'] = $this->role;
            $arrayData['visitor_id'] = $id = $modelVisitor->insert($arrayData);

            $qrFile = "/data/qr/qr_" . md5($arrayData['visitor_id']) . ".png";
            \PHPQRCode\QRcode::png($arrayData['visitor_hash'], dirname(__FILE__) . '/../../public' . $qrFile, 'H', 6, 2);

            $modelVisitor->update(array(
                'visitor_qr' => $qrFile
            ), $arrayData['visitor_id']);

            /**
             * Dodajemy powiązane bilety
             */
            if ($arrayData['ticket_num'] > 1) {
                foreach ($arrayData['visitor2_firstname'] as $key => $visitor) {
                    $tmpId = $modelVisitor->insert(array(
                        'visitor_firstname'    => $visitor,
                        'visitor_lastname'     => $arrayData['visitor2_lastname'][$key],
                        'visitor_email'        => $arrayData['visitor_email'],
                        'visitor_tel'          => $arrayData['visitor_tel'],
                        'parent_id'            => $id,
                        'visitor_hash'         => $arrayData['visitor_hash'] . '_' . $key,
                        'visitor_registerdate' => date('Y-m-d H:i:s'),
                        'visitor_kasa' => $this->role,
                    ));

                    $qrFile = "/data/qr/qr_" . md5($tmpId) . ".png";
                    \PHPQRCode\QRcode::png($arrayData['visitor_hash'] . '_' . $key, dirname(__FILE__) . '/../../public' . $qrFile, 'H', 6, 2);

                    $modelVisitor->update(array(
                        'visitor_qr' => $qrFile
                    ), $tmpId);
                }
            }

            $ticketNameArray = $modelTicketType->getPair(array(), null, null, 'ticket_name');
            $ticketPriceArray = $modelTicketType->getPair(array(), null, null, 'ticket_price');

            $additionalPrice = 0;
            if($arrayData['ticket_type_id'] != 5 && $arrayData['ticket_additional'] == 1) {
                $additionalPrice = 200;
            }
			
            $paymentResponse = $this->processPayment($arrayData, array(
                'extOrderId'            => $id . '_' . $arrayData['ticket_type_id'],
                'ticketName'            => $ticketNameArray[$arrayData['ticket_type_id']],
                'ticketPrice'           => $ticketPriceArray[$arrayData['ticket_type_id']],
                'ticketAdditional'      => $arrayData['ticket_additional'],
                'ticketAdditionalPrice' => $additionalPrice,
                'price_amount'          => $arrayData['price_amount'],
                'bon_id'                => $arrayData['bon_id'],
                'bon_discount'          => $arrayData['bon_discount'],
                'ticket_num'            => $arrayData['ticket_num'],
            ));

            if ($paymentResponse && $arrayData['step'] == 1) {
                return $this->view->render($response, 'operator/2.phtml', [
                    'ticketdata' => $arrayData,
                ]);
            }
        }
		
		$db = \Db\Db::getInstance();
		$query = $db->query('SELECT SUM(ticket_num) as ilosc FROM planning_payu_payment WHERE (ticket_type_additional = 1 OR ticket_type_id = 5) AND payment_success = 1');
		$row = $query->fetchAll();
		
		if(is_array($row) && $row[0]['ilosc'] > 0) {
			$wolnych = 270-(int)$row[0]['ilosc'];
		} else {
			$wolnych = 270;
		}
		$wolnych = 0;
        $ticketList = $modelTicketType->getAll();

        return $this->view->render($response, 'operator/1.phtml', [
            'ticketList' => $ticketList,
			'wolnych' => $wolnych
        ]);
    }

    private function processPayment($userData, $ticketData)
    {
        $modelPayU = new Payment();
        $modelPayU->insert(array(
            'order_id'               => sha1(time()),
            'payment_date'           => date('Y-m-d H:i:s'),
            'visitor_id'             => $userData['visitor_id'],
            'payment_amount'         => $ticketData['price_amount'],
            'ticket_type_id'         => $userData['ticket_type_id'],
            'ticket_type_additional' => $ticketData['ticketAdditional'],
            'bon_id'                 => $ticketData['bon_id'],
            'bon_discount'           => $ticketData['bon_discount'],
            'ticket_num'             => $ticketData['ticket_num'],
        ));

        return true;
    }

    private function paymentRetrieve($visitorId, $paymentType)
    {
        $modelPayU = new Payment();
        $rowPayment = $modelPayU->getOne(array(
            'visitor_id' => $visitorId
        ));

        $modelVisitor = new Visitor();
        $modelVisitor->load((int)$rowPayment['visitor_id']);
        $visitorData = $modelVisitor->getData();

        if (is_array($rowPayment) && count($rowPayment) > 0) {
            if ($paymentType != 3) {
                $modelPayU->updateWhere(array(
                    'payment_success'      => 1,
                    'payment_success_date' => date('Y-m-d H:i:s'),
                    'payment_type'         => $paymentType,
                ), "payu_payment_id = '" . $rowPayment['payu_payment_id'] . "' ");

                $modelVisitor->update(array(
                    'visitor_accepted'      => 1,
                    'visitor_accepted_date' => date('Y-m-d H:i:s')
                ), $visitorData['visitor_id']);

                if ($rowPayment['ticket_num'] > 1) {
                    $additionalVisitorArray = $modelVisitor->getAll(array(
                        'parent_id' => (int)$rowPayment['visitor_id']
                    ));

                    foreach ($additionalVisitorArray as $additionalVisitor) {
                        $modelVisitor->update(array(
                            'visitor_accepted'      => 1,
                            'visitor_accepted_date' => date('Y-m-d H:i:s')
                        ), $additionalVisitor['visitor_id']);
                    }
                }
            } else {
                $modelPayU->updateWhere(array(
                    'payment_type' => $paymentType,
                ), "payu_payment_id = '" . $rowPayment['payu_payment_id'] . "' ");
            }
        }

        return true;
    }

    private function generateDocuments($postData) {

        $modelPayU = new Payment();
        $rowPayment = $modelPayU->getOne(array(
            'visitor_id' => $postData['visitor_id']
        ));

        $modelVisitor = new Visitor();
        $modelVisitor->load((int)$rowPayment['visitor_id']);
        $visitorData = $modelVisitor->getData();

        $modelTicketType = new Type();
        $modelTicketType->load((int)$rowPayment['ticket_type_id']);
        $rowType = $modelTicketType->getData();

        $visitorData['ticket_name'] = $rowType['ticket_name'];
        $visitorData['ticket_additional'] = $rowPayment['ticket_type_additional'];
        $visitorData['ticket_type_id'] = $rowPayment['ticket_type_id'];
        $visitorData['ticket_price'] = $rowPayment['payment_amount'];
        $visitorData['ticket_num'] = $rowPayment['ticket_num'];
        $visitorData['payment_type'] = $rowPayment['payment_type'];
        $visitorData['faktura'] = $postData['faktura'];
		$visitorData['warsztaty'] = $this->warsztaty;

        $serviceTicket = new Ticket();

        $visitorData['pdf_ticket'][] = $serviceTicket->generateTicket($visitorData, $this->view);
        $visitorData = $this->generateInvoice($visitorData);

        if($rowPayment['ticket_num'] > 1) {
            $additionalVisitorArray = $modelVisitor->getAll(array(
                'parent_id' => (int)$rowPayment['visitor_id']
            ));

            foreach($additionalVisitorArray as $additionalVisitor) {
                $modelVisitor->update(array(
                    'visitor_accepted' => 1,
                    'visitor_accepted_date' => date('Y-m-d H:i:s')
                ), $additionalVisitor['visitor_id']);

                $additionalVisitor['ticket_name'] = $rowType['ticket_name'];
                $additionalVisitor['ticket_additional'] = $rowPayment['ticket_type_additional'];
                $additionalVisitor['ticket_price'] = $rowPayment['payment_amount'];
                $additionalVisitor['ticket_num'] = $rowPayment['ticket_num'];

                $visitorData['pdf_ticket'][] = $serviceTicket->generateTicket($additionalVisitor, $this->view);
            }
        }

        if($visitorData['visitor_email'] != '') {
            $route = $this->router->get('router')->getNamedRoute("email_visitor_kasa");
            $request = $this->router->get('request')->withAttribute('visitor', $visitorData);
            $route->run($request, $this->router->get('response'), $visitorData);
        }

        if($postData['faktura'] == 1) {
            unset($visitorData['pdf_invoice']);
        }

        return $visitorData;
    }

    private function generateInvoice($data)
    {
        $modelInvoice = new Invoice();
        $invoiceRecord = $modelInvoice->getOne(array(
            'invoice_visitor_id' => $data['visitor_id']
        ));

        if (!array_key_exists('invoice_id', $invoiceRecord)) {
            $db = \Db\Db::getInstance();
            $query = $db->query('SELECT max(invoice_num) as max_num FROM planning_invoice WHERE invoice_place = "kasa" AND invoice_fv = ' . ($data['visitor_nip'] != '' ? 1 : 0) . ' AND MONTH(invoice_date) = "' . date('n') . '" LIMIT 1');
            $row = $query->fetchAll();

            if (is_array($row) && $row[0]['max_num'] > 0) {
                $invoiceMax = (int)$row[0]['max_num'] + 1;
            } else {
                $invoiceMax = 1;
            }

            if ($data['visitor_nip'] != '') {
                $data['invoice_name'] = $invoiceMax . '/CFE/KASA/F/' . date('m') . '/' . date('Y');
            } else {
                $data['invoice_name'] = $invoiceMax . '/CFE/KASA/P/' . date('m') . '/' . date('Y');
            }

            $modelInvoice->insert(array(
                'invoice_name'       => $data['invoice_name'],
                'invoice_num'        => $invoiceMax,
                'invoice_fv'         => ($data['visitor_nip'] != '' ? 1 : 0),
                'invoice_visitor_id' => $data['visitor_id'],
                'invoice_file'       => 'faktura_' . str_replace('/', '_', $data['invoice_name']) . '.pdf',
				'invoice_place'		 => 'kasa',
            ));
        } else {
            $data['invoice_name'] = $invoiceRecord['invoice_name'];
        }


        $serviceTicket = new Ticket();
        $data['pdf_invoice'] = $serviceTicket->generateInvoice($data, $this->view, 'invoice/kasa');

        return $data;
    }

    protected function getColor($id, $additional) {

        //$sobota = 4;
        $sobota = 6;
        $color = '';
        $colorClass = '';
        switch($id) {
			case '5':
            case '1':
                if(date('w') == $sobota) {
                    $color = '<span class="fioletowy">fioletowy</span>';
                    $colorClass = 'fioletowyBg';
                } else {
                    $color = '<span class="zielony">zielony</span>';
                    $colorClass = 'zielonyBg';
                }
                break;
            case '2':
                $color = '<span class="rozowy">różowy</span>';
                $colorClass = 'rozowyBg';
                break;
            case '3':
                if(date('w') == $sobota) {
                    $color = '<span class="niebieski">niebieski</span>';
                    $colorClass = 'niebieskiBg';
                } else {
                    $color = '<span class="zolty">żółty</span>';
                    $colorClass = 'zoltyBg';
                }
                break;
            case '4':
                $color = '<span class="pomaranczowy">pomarańczowy</span>';
                $colorClass = 'pomaranczowyBg';
                break;
        }
		
		if(($id == 5 || $additional == 1) && date('w') == $sobota) {
			$color .= ', <span class="czerwony">czerwony</span>';
			$colorClass = 'czerwonyBg';
		}

        return array(
            'color' => $color,
            'class' => $colorClass
        );
    }

    public function kasaIndex($request, $response, $args)
    {
        $db = \Db\Db::getInstance();
        $query = $db->query('SELECT v.visitor_kasa, DAYNAME(p.payment_success_date) as dzien, SUM(p.payment_amount) as suma, p.payment_type as platnosc FROM planning_visitor v JOIN planning_payu_payment p on p.visitor_id = v.visitor_id WHERE v.parent_id = 0 AND v.visitor_accepted = 1 AND v.visitor_kasa IS NOT NULL GROUP BY v.visitor_kasa, p.payment_type, DAYNAME(p.payment_success_date) ORDER BY dzien, v.visitor_kasa');
        $rowsArray= $query->fetchAll();

        return $this->view->render($response, 'kasjer.phtml', [
            'kasjerList' => $rowsArray
        ]);
    }
}