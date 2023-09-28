<?php

declare(strict_types=1);

namespace Bot\Dao;

use Bot\Presentation;

interface DaoInterface
{
	public function find(int $id): ?Presentation;

	public function persist(Presentation $presentation): void;
}
