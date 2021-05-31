<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 21.02.17
 * Time: 12:47
 */

namespace Controller;

class MailController
{
    protected $view;
    protected $mailer;

    // constructor receives container instance
    public function __construct($container)
    {
        $this->view = $container;
        $transport = \Swift_SmtpTransport::newInstance(\MyConfig::getValue('smtpHost'), \MyConfig::getValue('smtpPort'), 'tls')
            ->setUsername(\MyConfig::getValue('smtpLogin'))
            ->setPassword(\MyConfig::getValue('smtpPass'))
        ;

        $this->mailer = \Swift_Mailer::newInstance($transport);
    }

    public function index($request, $response, $args)
    {

    }

    public function reservation($request, $response, $args)
    {
        if($request->isPost()) {
			$arrayData = $request->getParsedBody();
			$station = $request->getAttribute('station');
			
            $mailBody = $this->view->fetch('email/reservation.phtml', array_merge($arrayData, array('station' => $station)));
            $res = $this->send('Rezerwacja ' . str_replace('Stoisko', 'Stoiska', $station['station_name']) . ' - Crypto Future Expo 2018', array(
                $arrayData['company_email'] => $arrayData['company_user'] . ' ' . $arrayData['company_user2']
            ), $mailBody, false, true);

            return $response->withJson(array(
                'status' => 'success'
            ), 200);
        }

        return $response->withJson(array(
            'status' => 'error'
        ), 201);
    }

    public function visitor($request, $response, $args)
    {
        if($request->isGet()) {
            $arrayData = $request->getQueryParams();

            $title = 'Dziękujemy za rejestrację na Warsztaty Kryptoksięgowość';
            $template = 'email/visitor.phtml';

            if($arrayData['system_form'] == 'career') {
                $title = 'Dziękujemy za rejestrację na Targi IT Career Summit 2017';
                $template = 'email/visitor_career.phtml';
            }

            $mailBody = $this->view->fetch($template, $arrayData);
            $res = $this->send($title, array(
                $arrayData['visitor_email'] => $arrayData['visitor_firstname'] . ' ' . $arrayData['visitor_lastname']
            ), $mailBody, false, false, $arrayData['system_form']);

            return $response->withJson(array(
                'status' => 'success'
            ), 200);
        }

        return $response->withJson(array(
            'status' => 'error'
        ), 201);
    }

    public function visitorModerator($request, $response, $args)
    {
        if($request->isGet()) {
            $arrayData = $request->getAttribute('visitor');

            $title = 'Rejestracja Warsztaty Kryptoksięgowość';
            $name = 'System rejestracji Warsztaty Kryptoksięgowość';
            $template = 'email/visitor_moderator.phtml';

            if($arrayData['system_form'] == 'career') {
                $title = 'Rejestracja IT Career Summit 2017';
                $name = 'System rejestracji IT Career Summit 2017';
                $template = 'email/visitor_moderator_career.phtml';
            }

            $mailBody = $this->view->fetch($template, $arrayData);
            $res = $this->send($title, array(
                'aneta.budner@pureexpo.pl' => $name
            ), $mailBody);

            return $response->withJson(array(
                'status' => 'success'
            ), 200);
        }

        return $response->withJson(array(
            'status' => 'error'
        ), 201);
    }

    public function visitorAccepted($request, $response, $args)
    {
        $arrayData = $request->getAttribute('visitor');

        $title = 'Bilet Crypto Future Expo 20.09.2018 Warszawa';
        $template = 'email/visitor_accepted.phtml';
        if($arrayData['system_form'] == 'career') {
            $title = 'Twój bilet - IT Career Summit 2017';
            $template = 'email/visitor_accepted_career.phtml';
        }

        $mailBody = $this->view->fetch($template, $arrayData);

        $res = $this->send($title, array(
            $arrayData['visitor_email'] => $arrayData['visitor_firstname'] . ' ' . $arrayData['visitor_lastname']
        ), $mailBody, $arrayData['pdf_ticket'], true, 'career', $arrayData['pdf_invoice'], $arrayData['invoice_name']);

        return $response->withJson(array(
            'status' => 'success'
        ), 200);

    }
	
	public function visitorKasa($request, $response, $args)
    {
        $arrayData = $request->getAttribute('visitor');

        $title = 'Dziękujemy za Twój udział w CryptoExpo 2018';
        $template = 'email/visitor_kasa.phtml';
        $mailBody = $this->view->fetch($template, $arrayData);

        if ($arrayData['faktura'] == 1) {
            $res = $this->send($title, array(
                $arrayData['visitor_email'] => $arrayData['visitor_firstname'] . ' ' . $arrayData['visitor_lastname']
            ), $mailBody, $arrayData['pdf_ticket'], true, 'career', $arrayData['pdf_invoice'], $arrayData['invoice_name']);
        } else {
            $res = $this->send($title, array(
                $arrayData['visitor_email'] => $arrayData['visitor_firstname'] . ' ' . $arrayData['visitor_lastname']
            ), $mailBody, $arrayData['pdf_ticket'], true, 'career', false, false);
        }

        return $response->withJson(array(
            'status' => 'success'
        ), 200);

    }

    private function send($title, $to, $body, $attachment = false, $copy = false, $engine = null, $attachment2 = false, $attachment2_name = false) {

        $message = \Swift_Message::newInstance($title)
            ->setFrom(array(\MyConfig::getValue('smtpFrom_email') => \MyConfig::getValue('smtpFrom_name')))
            ->setTo($to)
            ->setBody($body)
            ->setContentType("text/html");

        if(!is_null($engine) && $engine == 'career') {
            $transport = \Swift_SmtpTransport::newInstance(\MyConfig::getValue('smtpHost_career'), \MyConfig::getValue('smtpPort_career'), 'tls')
                ->setUsername(\MyConfig::getValue('smtpLogin_career'))
                ->setPassword(\MyConfig::getValue('smtpPass_career'))
            ;
            $this->mailer = \Swift_Mailer::newInstance($transport);

            $message = \Swift_Message::newInstance($title)
                ->setFrom(array(\MyConfig::getValue('smtpFrom_email_career') => \MyConfig::getValue('smtpFrom_name_career')))
                ->setTo($to)
                ->setBody($body)
                ->setContentType("text/html");
        }
			
		if($copy) {
			$message->setBcc('tomasz.kucfir@pureconferences.pl');
		}

        if($attachment) {
			$filename = 'bilet_crypto_future_expo.pdf';
			if(!is_null($engine) && $engine == 'career') {
				$filename = 'bilet_crypto_future_expo.pdf';
			}
			if(!is_array($attachment)) {
                $attachment = array($attachment);
            }
            foreach($attachment as $att) {
                $message->attach(
                    \Swift_Attachment::fromPath($att, 'application/pdf')->setFilename($filename)
                );
            }

        }
		
		if($attachment2) {
			$filename = 'faktura_' . str_replace('/', '_', $attachment2_name) . '.pdf';
            $message->attach(
                \Swift_Attachment::fromPath($attachment2, 'application/pdf')->setFilename($filename)
            );
        }

        return $this->mailer->send($message);
    }
}