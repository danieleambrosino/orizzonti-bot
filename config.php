<?php

return [
	'DAO' => \Bot\Dao\PdoDao::class,
	'PDO_DSN' => 'sqlite:' . __DIR__ . '/data.db',
];
