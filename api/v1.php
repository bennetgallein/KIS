<?php
use API\UserObject;

require('../vendor/autoload.php');

$config['settings']['displayErrorDetails'] = true;
$config['settings']['addContentLengthHeader'] = false;
$config['settings']['displayErrorDetails'] = true;

$config['settings']['db']['host']   = '46.251.239.147';
$config['settings']['db']['user']   = 'kis';
$config['settings']['db']['pass']   = 'kis';
$config['settings']['db']['dbname'] = 'kis';

$app = new \Slim\App($config);
$container = $app->getContainer();

$basePath = "/v1";

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new \PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    return $pdo;
};

$app->get($basePath . '/documentation', function ($request, $response, $args) {
   $response->withHeader('Content-Type', 'application/json');
   $response->write(json_encode(array('documentation' => 'https://kisapi.docs.apiary.io')));
   return $response;
});

$app->get($basePath . '/users', function ($request, $response, $args) {
    $userclass = new UserObject($this->db);
    $user = $userclass->getUsers();

    $response->withHeader('Content-Type', 'application/json');
    $response->write(json_encode($user));
    return $response;
});

// Define app routes
$app->get($basePath . '/users/{id}', function ($request, $response, $args) {
    $user_id = $args['id'];
    $userclass = new UserObject($this->db);
    $user = $userclass->getUserByID($user_id);

    $response->withHeader('Content-Type', 'application/json');
    $response->write(json_encode($user, JSON_FORCE_OBJECT));
    return $response;
});

$app->get($basePath . '/address/{id}', function ($request, $response, $args) {
    $user_id = $args['id'];
    $userclass = new UserObject($this->db);
    $user = $userclass->getAddress($user_id);

    $response->withHeader('Content-Type', 'application/json');
    $response->write(json_encode($user, JSON_FORCE_OBJECT));
    return $response;
});

$app->get($basePath . '/modules', function ($request, $response, $args) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "http://server/KIS/php/requestMods.php?key=aa");
    $result = curl_exec($curl);
    curl_close($curl);
    $response->withHeader('Content-Type', 'application/json');
    $response->write($result);
    return $response;
});

// Run app
$app->run();