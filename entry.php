<?php

require_once __DIR__ . '/vendor/autoload.php';

use Bot\Dao\FileDao;
use Bot\Responder;

$responder = Responder::create(new FileDao());
$responder->handle();
