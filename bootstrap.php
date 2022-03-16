<?php

foreach (require __DIR__ . '/env.php' as $key => $value) {	
	if (empty($_SERVER[$key])) $_SERVER[$key] = $value;
}
