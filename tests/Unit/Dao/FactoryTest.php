<?php

declare(strict_types=1);

namespace Tests\Unit\Dao;

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
#[CoversClass(Factory::class)]
#[UsesClass(Presentation::class)]
#[UsesClass(ArrayDao::class)]
#[UsesClass(FileDao::class)]
#[UsesClass(PdoDao::class)]
class FactoryTest extends TestCase
{
	public function testInstantiatesArrayDao(): void
	{
		$this->assertInstanceOf(ArrayDao::class, Factory::create(ArrayDao::class));
	}

	public function testInstantiatesFileDao(): void
	{
		$this->assertInstanceOf(FileDao::class, Factory::create(FileDao::class));
	}

	public function testInstantiatesPdoDao(): void
	{
		$_SERVER['PDO_DSN'] = 'sqlite::memory:';
		$this->assertInstanceOf(PdoDao::class, Factory::create(PdoDao::class));
		unset($_SERVER['PDO_DSN']);
	}

	public function testInvalidDao(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		Factory::create(\stdClass::class);
	}
}
