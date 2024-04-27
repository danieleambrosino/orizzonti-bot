<?php

declare(strict_types=1);

namespace Bot\Dao;

use Bot\Entities\Presentation;

class ArrayDao implements DaoInterface
{
	/** @var array<int,null|Presentation> */
	private array $data = [];

	public function find(int $id): ?Presentation
	{
		if (!isset($this->data[$id])) {
			return null;
		}

		return $this->data[$id];
	}

	public function persist(Presentation $presentation): void
	{
		$this->data[$presentation->userId] = $presentation;
	}
}
