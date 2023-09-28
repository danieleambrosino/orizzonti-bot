<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bot\Dao\ArrayDao;
use Bot\Dao\Factory;
use Bot\Dao\FileDao;
use Bot\Dao\PdoDao;
use Bot\Presentation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ArrayDao::class)]
#[CoversClass(FileDao::class)]
#[CoversClass(PdoDao::class)]
#[CoversClass(Factory::class)]
#[UsesClass(Presentation::class)]
class DaoTest extends TestCase
{
	public function testArrayDaoNullPresentation(): void
	{
		// Given
		$dao = new ArrayDao();

		// When
		$presentation = $dao->find(1);

		// Then
		$this->assertNull($presentation);
	}

	public function testArrayDaoFindPresentation(): void
	{
		// Given
		$dao = new ArrayDao();
		$presentation = new Presentation(1);

		// When
		$dao->persist($presentation);
		$retrievedPresentation = $dao->find(1);

		// Then
		$this->assertSame($presentation, $retrievedPresentation);
	}

	public function testFileDao(): void
	{
		$dao = new FileDao();
		$this->assertNull($dao->find(0)); // non-existing file
		$this->assertNull($dao->find(69420)); // unaccessible file
		$this->assertNull($dao->find(400)); // wrong format file
		$this->assertInstanceOf(Presentation::class, $dao->find(99881252));

		$dao->persist(new Presentation(1));
	}

	public function testPdoDao(): void
	{
		// Given
		$pdo = new \PDO('sqlite::memory:');
		$pdo->exec('CREATE TABLE Presentation (userId INTEGER PRIMARY KEY, username TEXT, firstName TEXT, lastName TEXT, status INTEGER NOT NULL DEFAULT 0, presentation TEXT, inviter TEXT)');
		$pdo->exec('INSERT INTO Presentation (userId) VALUES (1)');
		$dao = new PdoDao($pdo);

		$this->assertNull($dao->find(0)); // non-existing entry

		$presentation = $dao->find(1);
		$this->assertInstanceOf(Presentation::class, $presentation); // existing entry

		$presentation->setFirstName('Pippo');
		$dao->persist($presentation); // updated entry
		$presentation = $dao->find(1);
		$this->assertNotNull($presentation);
		$this->assertSame($presentation->firstName, $presentation->firstName);
	}

	public function testFactory(): void
	{
		$this->assertInstanceOf(FileDao::class, Factory::create(FileDao::class));
		$_SERVER['PDO_DSN'] = 'sqlite::memory:';
		$this->assertInstanceOf(PdoDao::class, Factory::create(PdoDao::class));
		$this->expectException(\InvalidArgumentException::class);
		Factory::create('asdf');
	}
}
