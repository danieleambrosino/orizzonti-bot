<?php

declare(strict_types=1);

namespace Bot\Dao;

use Bot\Entities\Presentation;

class PdoDao implements DaoInterface
{
	public function __construct(private \PDO $connection) {}

	public function find(int $id): ?Presentation
	{
		$stmt = $this->connection->prepare('SELECT * FROM Presentation WHERE userId = ?');
		$stmt->execute([$id]);
		$data = $stmt->fetch(\PDO::FETCH_ASSOC);
		if (false === is_array($data)) {
			return null;
		}

		return new Presentation(...$data);
	}

	public function persist(Presentation $presentation): void
	{
		$stmt = $this->connection->prepare('REPLACE INTO Presentation VALUES (?, ?, ?, ?, ?, ?, ?)');
		$stmt->execute(array_values($presentation->__serialize()));
	}
}
