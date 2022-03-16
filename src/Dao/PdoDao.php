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
		$stmt = $this->connection->prepare('SELECT * FROM presentations WHERE user_id = ?');
		$stmt->execute([$id]);
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		return new Presentation(
			$data['user_id'],
			$data['username'],
			$data['first_name'],
			$data['last_name'],
			Status::from($data['status']),
			$data['presentation'],
			$data['invitor'],
		);
	}

	public function persist(Presentation $presentation)
	{
		$stmt = $this->connection->prepare('REPLACE INTO presentations VALUES (?, ?, ?, ?, ?, ?, ?)');
		$stmt->execute(array_values($presentation->__serialize()));
	}
}
