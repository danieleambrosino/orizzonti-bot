<?php

declare(strict_types=1);

namespace Tests\Unit\Dao;

use Bot\Dao\PdoDao;
use Bot\Presentation;
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
	private static PdoDao $dao;

	public static function setUpBeforeClass(): void
	{
		$pdo = new \PDO('sqlite::memory:');
		$pdo->exec('CREATE TABLE Presentation (userId INTEGER PRIMARY KEY, username TEXT, firstName TEXT, lastName TEXT, status INTEGER NOT NULL DEFAULT 0, presentation TEXT, inviter TEXT)');
		$pdo->exec('INSERT INTO Presentation (userId) VALUES (1)');
		self::$dao = new PdoDao($pdo);
	}

	public function testPdoDao(): void
	{
		$this->assertNull(self::$dao->find(0)); // non-existing entry

		$presentation = self::$dao->find(1);
		$this->assertInstanceOf(Presentation::class, $presentation); // existing entry

		$presentation->setFirstName('Pippo');
		self::$dao->persist($presentation); // updated entry
		$presentation = self::$dao->find(1);
		$this->assertNotNull($presentation);
		$this->assertSame($presentation->firstName, $presentation->firstName);
	}
}
