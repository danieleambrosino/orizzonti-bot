<?php

declare(strict_types=1);

namespace Tests\Unit\Dao;

use Bot\Dao\PdoDao;
use Bot\Presentation;
use Bot\Status;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(PdoDao::class)]
#[UsesClass(Presentation::class)]
class PdoDaoTest extends TestCase
{
	private static \PDO $pdo;
	private static PdoDao $dao;
	private static int $userId;

	public static function setUpBeforeClass(): void
	{
		self::$userId = rand();
		self::$pdo = new \PDO('sqlite::memory:');
		self::$pdo->exec('CREATE TABLE Presentation (userId INTEGER PRIMARY KEY, username TEXT, firstName TEXT, lastName TEXT, status INTEGER NOT NULL DEFAULT 0, presentation TEXT, inviter TEXT)');
		self::$dao = new PdoDao(self::$pdo);
	}

	protected function tearDown(): void
	{
		self::$pdo->exec('DELETE FROM Presentation');
	}

	public function testNonExistingPresentation(): void
	{
		// Given an empty table

		// When
		$presentation = self::$dao->find(self::$userId);

		// Then
		$this->assertNull($presentation);
	}

	public function testFetchesPresentationCorrectly(): void
	{
		// Given
		self::$pdo->exec('INSERT INTO Presentation (userId) VALUES ('.(string) self::$userId.')');

		// When
		$presentation = self::$dao->find(self::$userId);

		// Then
		$this->assertInstanceOf(Presentation::class, $presentation);
		$this->assertSame(self::$userId, $presentation->userId);
	}

	public function testPersistsPresentationCorrectly(): void
	{
		// Given
		$presentation = new Presentation(
			userId: self::$userId,
			username: 'pippobaudo',
			firstName: 'Pippo',
			lastName: 'Baudo',
			status: Status::ConversationEnded,
			presentation: 'presentation',
			inviter: 'inviter',
		);

		// When
		self::$dao->persist($presentation);

		// Then
		$fetchedPresentation = self::$dao->find(self::$userId);
		$this->assertEquals($presentation, $fetchedPresentation);
	}
}
