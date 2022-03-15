<?php

require_once __DIR__ . '/vendor/autoload.php';

use Bot\Responder;

$responder = Responder::create();
$responder->handle();
