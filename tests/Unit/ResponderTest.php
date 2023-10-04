<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bot\Dao\ArrayDao;
use Bot\Dao\DaoInterface;
use Bot\Handlers\ConversationEndedHandler;
use Bot\Handlers\ConversationStartedHandler;
use Bot\Handlers\Factory;
use Bot\Handlers\InviterRequestedHandler;
use Bot\Handlers\PresentationRequestedHandler;
use Bot\Presentation;
use Bot\Request;
use Bot\Responder;
use Bot\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Responder::class)]
#[UsesClass(ArrayDao::class)]
#[UsesClass(ConversationEndedHandler::class)]
#[UsesClass(ConversationStartedHandler::class)]
#[UsesClass(Factory::class)]
#[UsesClass(InviterRequestedHandler::class)]
#[UsesClass(Presentation::class)]
#[UsesClass(PresentationRequestedHandler::class)]
#[UsesClass(Request::class)]
#[UsesClass(Response::class)]
class ResponderTest extends TestCase
{
	private static DaoInterface $dao;
	private static Responder $responder;

	public static function setUpBeforeClass(): void
	{
		self::$dao = new ArrayDao();
		self::$responder = new Responder(self::$dao);
	}

	public function testIsInstantiatedCorrectly(): void
	{
		$this->assertInstanceOf(Responder::class, new Responder(new ArrayDao()));
	}

	public function testInteraction(): void
	{
		// Given
		$request = new Request(
			messageId: 1,
			userId: 1,
			username: 'pippobaudo',
			firstName: 'Pippo',
			lastName: 'Baudo',
			text: '/mipresento',
			entities: [(object) ['type' => 'bot_command']],
		);

		// When
		/** @var Response */
		$response = self::$responder->respond($request);

		// Then
		$this->assertSame('Ciao Pippo, benvenuto! Scrivi qui la tua presentazione:', $response->text);
		$this->assertTrue($response->forceReply);

		$presentation = self::$dao->find(1);
		$this->assertInstanceOf(Presentation::class, $presentation);

		$this->assertSame('pippobaudo', $presentation->username);
		$this->assertSame('Pippo', $presentation->firstName);
		$this->assertSame('Baudo', $presentation->lastName);

		// Given
		$request = new Request(
			userId: 1,
			text: 'presentation',
		);

		// When
		/** @var Response */
		$response = self::$responder->respond($request);

		// Then
		$this->assertSame('Da chi sei stato invitato?', $response->text);
		$this->assertTrue($response->forceReply);

		$presentation = self::$dao->find(1);
		$this->assertInstanceOf(Presentation::class, $presentation);
		$this->assertSame('presentation', $presentation->presentation);

		// Given
		$request = new Request(
			userId: 1,
			text: 'inviter',
		);

		// When
		/** @var Response */
		$response = self::$responder->respond($request);

		// Then
		$this->assertSame('Grazie!', $response->text);
		$this->assertFalse($response->forceReply);

		$presentation = self::$dao->find(1);
		$this->assertInstanceOf(Presentation::class, $presentation);
		$this->assertSame('inviter', $presentation->inviter);

		// Given
		$request = new Request(
			userId: 1,
			text: '/mipresento',
			entities: [(object) ['type' => 'bot_command']],
		);

		// When
		/** @var Response */
		$response = self::$responder->respond($request);

		// Then
		$this->assertSame('Ti sei già presentato!', $response->text);
		$this->assertFalse($response->forceReply);

		// Given
		$request = new Request(
			userId: 1,
			text: 'test',
		);

		// When
		/** @var null */
		$response = self::$responder->respond($request);

		// Then
		$this->assertNull($response);
	}
}
