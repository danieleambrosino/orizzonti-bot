<?php

namespace Bot\Dao;

use Bot\Presentation;

class FileDao implements DaoInterface
{
	const ROOT = __DIR__ . '/../../var';

	public function __construct()
	{
		if (!file_exists(self::ROOT) && !mkdir(self::ROOT)) {
			throw new \Exception('Cannot create persistence directory!');
		}
	}

	public function find(int $id): ?Presentation
	{
		$filename = join(DIRECTORY_SEPARATOR, [self::ROOT, $id]);
		if (!file_exists($filename)) return null;
		return unserialize(file_get_contents($filename));
	}

	public function persist(Presentation $presentation)
	{
		$filename = join(DIRECTORY_SEPARATOR, [self::ROOT, $presentation->getUserId()]);
		if (!file_exists($filename)) touch($filename);
		file_put_contents($filename, serialize($presentation));
	}
}
