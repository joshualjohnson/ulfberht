<?php

// require_once __DIR__ . '/src/ulfberht.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/debug.php';

use ulfberht\core\module;
use ulfberht\module\ulfberht;
use ulfberht\module\ulfberht\config;
use ulfberht\module\ulfberht\router;
use ulfberht\module\ulfberht\request;
use ulfberht\module\ulfberht\response;

class abc {
    public function __construct(router $router, response $response) {
        $this->routeVars = $router->getRouteVars();
        $this->response = $response;
    }
    
    public function action() {
        $this->response->setContent('Hello ' . $this->routeVars['name']);
        
    }
}

class xyz {
    
}

class myModule extends module {

    public function __construct() {
        $this->registerSingleton('abc');
        $this->registerSingleton('xyz');
    }

    public function config(router $a) {
        $a->when('/hello/:name', 'abc:action');
        $a->otherwise('xyz');
    }

    public function run(router $a, request $b) {
        var_dump($a->resolveRoute());
        var_dump($a->getRouteVars('name'));
        var_dump($a->getCurrentRoute());
        var_dump($a->getMatchedRoute());
        var_dump($b->attributes);
    }
}

ulfberht()->registerModule('myModule');
ulfberht()
    ->forge()
    ->getModule('ulfberht\module\ulfberht')
    ->invoke('mvc');