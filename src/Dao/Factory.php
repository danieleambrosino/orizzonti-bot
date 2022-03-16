<?php

namespace Bot\Dao;

use InvalidArgumentException;

final class Factory
{
	private static ?DaoInterface $dao = null;

	public static function create()
	{
		if (!self::$dao) {
			self::$dao = match ($_SERVER['DAO']) {
				FileDao::class => new FileDao(),
				PdoDao::class => new PdoDao($_SERVER['PDO_DSN']),
				default => throw new InvalidArgumentException('Unsupported Dao!')
			};
		}
		return self::$dao;
	}
}
