<?php

declare(strict_types=1);

namespace Tests\Unit\Dao;

use Bot\Dao\FileDao;
use Bot\Presentation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(FileDao::class)]
#[UsesClass(Presentation::class)]
class FileDaoTest extends TestCase
{
	private static FileDao $dao;
	private static int $userId;
	private static string $testFilePath;

	public static function setUpBeforeClass(): void
	{
		self::$dao = new FileDao();
		self::$userId = rand();
		self::$testFilePath = join(DIRECTORY_SEPARATOR, [FileDao::ROOT, (string) self::$userId]);
	}

	protected function tearDown(): void
	{
		@unlink(self::$testFilePath);
	}

	public function testIsInstantiatedCorrectly(): void
	{
		$this->assertInstanceOf(FileDao::class, new FileDao());
	}

	public function testNonExistingFile(): void
	{
		// Given
		@unlink(self::$testFilePath);

		// When
		$presentation = self::$dao->find(self::$userId);

		// Then
		$this->assertFileDoesNotExist(self::$testFilePath);
		$this->assertNull($presentation);
	}

	public function testUnreadableFile(): void
	{
		// Given
		touch(self::$testFilePath);
		chmod(self::$testFilePath, 0);

		// When
		$presentation = self::$dao->find(self::$userId);

		// Then
		$this->assertFileExists(self::$testFilePath);
		$this->assertNull($presentation);
	}

	public function testMalformedFile(): void
	{
		// Given
		file_put_contents(self::$testFilePath, 'asdf');

		// When
		$presentation = self::$dao->find(self::$userId);

		// Then
		$this->assertFileExists(self::$testFilePath);
		$this->assertNull($presentation);
	}

	public function testFetchesPresentationCorrectly(): void
	{
		// Given
		file_put_contents(self::$testFilePath, serialize(new Presentation(self::$userId)));

		// When
		$presentation = self::$dao->find(self::$userId);

		// Then
		$this->assertFileExists(self::$testFilePath);
		$this->assertInstanceOf(Presentation::class, $presentation);
	}

	public function testPersistsPresentationCorrectly(): void
	{
		// Given
		unlink(self::$testFilePath);

		// When
		self::$dao->persist(new Presentation(self::$userId));

		// Then
		$this->assertFileExists(self::$testFilePath);
	}
}
