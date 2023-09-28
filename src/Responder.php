<?php

declare(strict_types=1);

namespace Bot;

use Bot\Dao\DaoInterface;
use Bot\Handlers\Factory;

final class Responder
{
	public function __construct(
		private DaoInterface $dao
	) {}

	public function respond(object $request): ?Response
	{
		$presentation = $this->findPresentation($request->message->from->id);
		$handler = Factory::create($presentation->status);
		$response = $handler->handle($request, $presentation);
		$this->dao->persist($presentation);

		return $response;
	}

	private function findPresentation(int $userId): Presentation
	{
		return $this->dao->find($userId) ?? new Presentation($userId);
	}
}
