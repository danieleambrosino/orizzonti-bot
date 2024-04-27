<?php

declare(strict_types=1);

namespace Tests\Unit\Dao;

use Bot\Dao\ArrayDao;
use Bot\Entities\Presentation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ArrayDao::class)]
#[UsesClass(Presentation::class)]
class ArrayDaoTest extends TestCase
{
	public function testNonExistingPresentation(): void
	{
		// Given
		$dao = new ArrayDao();

		// When
		$presentation = $dao->find(1);

		// Then
		$this->assertNull($presentation);
	}

	public function testFetchesPresentationCorrectly(): void
	{
		// Given
		$dao = new ArrayDao();
		$presentation = new Presentation(1);

		// When
		$dao->persist($presentation);
		$fetchedPresentation = $dao->find(1);

		// Then
		$this->assertSame($presentation, $fetchedPresentation);
	}
}
