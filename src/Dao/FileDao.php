<?php

declare(strict_types=1);

namespace Bot\Dao;

use Bot\Presentation;

class FileDao implements DaoInterface
{
	public const ROOT = __DIR__.'/../../var';

	public function __construct()
	{
		if (!file_exists(self::ROOT) && !mkdir(self::ROOT)) {
			throw new \Exception('Cannot create persistence directory!');
		}
	}

	public function find(int $id): ?Presentation
	{
		$filename = join(DIRECTORY_SEPARATOR, [self::ROOT, (string) $id]);
		if (!file_exists($filename)) {
			return null;
		}
		$content = @file_get_contents($filename);
		if (false === $content) {
			return null;
		}
		$presentation = unserialize($content);
		if (false === $presentation instanceof Presentation) {
			return null;
		}

		return $presentation;
	}

	public function persist(Presentation $presentation): void
	{
		$filename = join(DIRECTORY_SEPARATOR, [self::ROOT, $presentation->userId]);
		if (!file_exists($filename) && !touch($filename)) {
			throw new \Exception('Cannot create file for data persistence!');
		}
		file_put_contents($filename, serialize($presentation));
	}
}
