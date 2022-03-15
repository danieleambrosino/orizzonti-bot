<?php

namespace Bot\Dao;

use Bot\Presentation;

class FileDao implements DaoInterface
{
	const ROOT = __DIR__ . '/../../var';

	public function find(int $id): ?Presentation
	{
		$path = realpath(FileDao::ROOT . '/' . $id);
		if ($path === false) return null;
		return unserialize(file_get_contents($path));
	}

	public function persist(Presentation $presentation)
	{
		$path = realpath(FileDao::ROOT . '/' . $presentation->getUserId());
		if ($path === false) {
			touch(FileDao::ROOT . '/' . $presentation->getUserId());
			$path = realpath(FileDao::ROOT . '/' . $presentation->getUserId());
		}
		file_put_contents($path, serialize($presentation));
	}
}
