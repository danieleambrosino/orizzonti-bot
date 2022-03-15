<?php

namespace Bot\Dao;

use Bot\Presentation;

interface DaoInterface
{
	public function find(int $id): ?Presentation;
	public function persist(Presentation $presentation);
}
