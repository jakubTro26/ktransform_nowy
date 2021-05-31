<?php

session_start();

if (empty($_ENV['SLIM_MODE'])) {
    $_ENV['SLIM_MODE'] = (getenv('SLIM_MODE'))
        ? getenv('SLIM_MODE') : 'development';
}

$config = array();

$configFile = dirname(__FILE__) . '/share/config/'
    . $_ENV['SLIM_MODE'] . '.php';

if (is_readable($configFile)) {
    require_once $configFile;
} else {
    require_once dirname(__FILE__) . '/share/config/default.php';
}

// Routing
$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'templates.path'      => dirname(__FILE__) . '/templates'
    ],
];
$app = new Slim\App($config);
$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "path"   => "/admin",
    "secure" => false,
    "realm"  => "Protected",
    "users"  => [
        "admin" => "adminhlx1551"
    ]
]));

$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "path"   => "/operator",
    "secure" => false,
    "realm"  => "Protected",
	"relaxed" => ["localhost", "headers"],
    "users"  => [
        "kasjer01" => "crytpoexpo011",
        "kasjer02" => "crytpoexpo021",
        "kasjer03" => "crytpoexpo031",
        "kasjer04" => "crytpoexpo041",
		"admin" => "admin"
    ],
    "callback" => function ($request, $response, $arguments) {
        $_SESSION['role'] = strtolower($arguments['user']);
    }
]));

$adminLayout = function ($request, $response, $next) {

    $response = $next($request, $response);
    return $this->view->render(new Slim\Http\Response(), 'layout/admin.phtml', [
        'content' => $response->getBody(),
    ]);
};

$operatorLayout = function ($request, $response, $next) {

    $response = $next($request, $response);
    return $this->view->render(new Slim\Http\Response(), 'operator.phtml', [
        'content' => $response->getBody(),
        'role' => $_SESSION['role']
    ]);
};


$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    return new \Slim\Views\PhpRenderer(dirname(__FILE__) . '/templates/');
};

$container['CompanyController'] = function ($c) {
    $view = $c->get("view");
    return new \Controller\CompanyController($view);
};

$container['PlanningController'] = function ($c) {
    $view = $c->get("view");
    return new \Controller\PlanningController($view, $c);
};

$container['VisitorController'] = function ($c) {
    $view = $c->get("view");
    return new \Controller\VisitorController($view, $c);
};

$container['MailController'] = function ($c) {
    $view = $c->get("view");
    return new \Controller\MailController($view);
};

$container['OperatorController'] = function ($c) {
    $view = $c->get("view");
    return new \Controller\OperatorController($view, $c, $_SESSION['role']);
};

$app->group('/admin', function () use ($app) {
    include(dirname(__FILE__) . '/app/Routing/admin.php');
})->add($adminLayout);

$app->group('/ajax', function () use ($app) {
    include(dirname(__FILE__) . '/app/Routing/admin.php');
});


$app->group('/operator/visitor', function () {
    include(dirname(__FILE__) . '/app/Routing/visitor_operator.php');
});

$app->map(['GET', 'POST'], '/operator', OperatorController::class . ':buyTicket')->add($operatorLayout);

$app->get('/', PlanningController::class . ':view');
$app->map(['GET', 'POST'], '/kup-bilet/{type}', VisitorController::class . ':buyTicket');
$app->map(['GET', 'POST'], '/kup-bilet-retrieve', VisitorController::class . ':buyTicketRetrieve');
$app->map(['GET', 'POST'], '/kup-bilet-retrieve-inpay', VisitorController::class . ':buyTicketRetrieveInpay');

$app->run();

/*
$route = $app->router()->getNamedRoute("audit_edit"); //returns Route
$route->setParams(array("jobid" => $audit->omc_id)); //Set the params

//Start a output buffer
ob_start();
$page2 = $route->dispatch(); //run the route
//Get the output
$page = ob_get_clean();
*/
