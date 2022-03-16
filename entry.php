<?php

require_once __DIR__ . '/vendor/autoload.php';

use Bot\Dao\Factory;
use Bot\Responder;

$dao = Factory::create();
$responder = Responder::create($dao);
$responder->handle();
