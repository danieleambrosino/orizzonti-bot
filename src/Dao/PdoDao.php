<?php

namespace Bot\Dao;

use Bot\Presentation;
use Bot\Status;
use PDO;

class PdoDao implements DaoInterface
{
	private PDO $connection;

	public function __construct(string $dsn)
	{
		$this->connection = new PDO($dsn);
	}

	public function find(int $id): ?Presentation
	{
		$stmt = $this->connection->prepare('SELECT * FROM Presentation WHERE userId = ?');
		$stmt->execute([$id]);
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		return $data ? new Presentation(...$data) : null;
	}

	public function persist(Presentation $presentation)
	{
		$stmt = $this->connection->prepare('REPLACE INTO Presentation VALUES (?, ?, ?, ?, ?, ?, ?)');
		$stmt->execute(array_values($presentation->__serialize()));
	}
}
