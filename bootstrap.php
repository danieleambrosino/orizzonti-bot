<?php

declare(strict_types=1);

$env = require __DIR__.'/env.php';
$secretEnv = @include __DIR__.'/env.secret.php';
if (!$secretEnv) {
	$secretEnv = [];
}

foreach (array_merge($env, $secretEnv) as $key => $value) {
	if (!isset($_SERVER[$key])) {
		$_SERVER[$key] = $value;
	}
}

require __DIR__.'/src/functions.php';
