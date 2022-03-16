<?php

foreach (require __DIR__ . '/config.php' as $key => $value) {	
	if (empty($_SERVER[$key])) $_SERVER[$key] = $value;
}
