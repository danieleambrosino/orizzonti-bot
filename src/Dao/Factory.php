<?php

declare(strict_types=1);

namespace Bot\Dao;

final class Factory
{
	/**
	 * @template T
	 *
	 * @param class-string<T> $daoClassName
	 *
	 * @throws \InvalidArgumentException
	 */
	public static function create(string $daoClassName): DaoInterface
	{
		return match ($daoClassName) {
			ArrayDao::class => new ArrayDao(),
			FileDao::class => new FileDao(),
			PdoDao::class => new PdoDao(new \PDO($_SERVER['PDO_DSN'])),
			default => throw new \InvalidArgumentException('Unsupported Dao!')
		};
	}
}
