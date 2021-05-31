<?php

$container = $app->getContainer();

$app->group('/planning', function() use ($app) {

    $app->get('[/]', PlanningController::class . ':index');
    $app->get('/map', PlanningController::class . ':index');
    $app->post('/map/save', PlanningController::class . ':mapSave');
    $app->get('/station', PlanningController::class . ':stationList');
    $app->map(['GET', 'POST'], '/station/edit/{id}', PlanningController::class . ':stationEdit');
    $app->map(['GET', 'POST'], '/station/delete/{id}', PlanningController::class . ':stationDelete');
    $app->get('/reservation', PlanningController::class . ':reservationList');
    $app->map(['GET', 'POST'], '/reservation/edit/{id}', PlanningController::class . ':reservationEdit');
    $app->map(['GET', 'POST'], '/reservation/delete/{id}', PlanningController::class . ':reservationDelete');
    $app->post('/reservation/add', PlanningController::class . ':reservationAdd');

});

$app->group('/company', function() use ($app) {

    $app->get('[/]', CompanyController::class . ':index');
    $app->map(['GET', 'POST'], '/edit[/{id}]', CompanyController::class . ':edit');
    $app->map(['GET', 'POST'], '/delete[/{id}]', CompanyController::class . ':delete');
});

$app->group('/visitor', function() use ($app) {

    $app->get('[/]', VisitorController::class . ':index');
    $app->map(['GET', 'POST'], '/edit[/{id}]', VisitorController::class . ':edit');
    $app->get('/add', VisitorController::class . ':add');
    $app->post('/add', VisitorController::class . ':edit');
    $app->map(['GET', 'POST'], '/delete/{id}', VisitorController::class . ':delete');
    $app->get('/accept/{hash}/{id}', VisitorController::class . ':accept');
    $app->get('/ticket/{id}', VisitorController::class . ':ticket');
    $app->get('/invoice/{id}', VisitorController::class . ':invoice');
    $app->get('/bon[/]', VisitorController::class . ':bonIndex');
	$app->get('/csv', VisitorController::class . ':getCsv');
	$app->get('/send-forget', VisitorController::class . ':sendForget');
	$app->map(['GET', 'POST'], '/bon/delete/{id}', VisitorController::class . ':bonDelete');
    $app->map(['GET', 'POST'], '/bon/edit[/{id}]', VisitorController::class . ':bonEdit');
    $app->map(['GET', 'POST'], '/bon/edit/', VisitorController::class . ':bonEdit');
    $app->map(['GET', 'POST'], '/bon/check', VisitorController::class . ':bonCheck');
});

$app->group('/mail', function() use ($app) {

    $app->get('[/]', MailController::class . ':index');
    $app->post('/reservation[/]', MailController::class . ':reservation')->setName('email_reservation');
    $app->post('/visitor[/]', MailController::class . ':visitor')->setName('email_visitor');
    $app->post('/visitorModerator[/]', MailController::class . ':visitorModerator')->setName('email_visitor_moderator');
    $app->post('/visitorAccepted[/]', MailController::class . ':visitorAccepted')->setName('email_visitor_accepted');
	$app->post('/visitorKasa[/]', MailController::class . ':visitorKasa')->setName('email_visitor_kasa');
});