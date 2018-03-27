<?php

$app->get('/', 'app\controller\MainController:actionIndex');
$app->post('/pay', 'app\controller\MainController:actionPayDirect');

$app->get('/test', 'app\controller\MainController:actionTest');
