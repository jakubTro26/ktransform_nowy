<?php

$app->get('/result/{last}/game/{game_id}', function ($request, $response, $args) {

    $modelResult = new \Result\Model\Result();
    $resultData = $modelResult->getAll(array(
        'game_id' => $args['game_id']
    ), array(
        'sort' => 'result_date',
        'order' => 'desc'
    ), array(
        'limit' => $args['last'],
        'start' => 0
    ));

    return $response->withJSON(
        $resultData
    );

});

$app->post('/add', function ($request, $response, $args) {

    $data = $request->getParsedBody();
    $modelNumber = new \Number\Model\Number();
    $id = $modelNumber->insert(array(
        'system_id' =>  $data['system_id'],
        'author' =>  $data['author']
    ));

    $modelNumberList = new \NumberList\Model\NumberList();
    foreach($data['number'] as $row) {
        $modelNumberList->insert(array(
            'number_id' => $id,
            'number_val' => $row
        ));
    }

    return $response->withStatus(302)->withHeader('Location', '/analysis/game/system?game_id=' . $data['game_id'] . '&system_id=' . $data['system_id']);

});

$app->get('', function ($request, $response, $args) {
    return $response->withJSON(
        array('test')
    );
});