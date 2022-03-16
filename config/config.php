<?php

return [
	'DAO' => \Bot\Dao\PdoDao::class,
	'PDO_DSN' => 'sqlite:' . dirname(__DIR__) . '/data.db',
];
