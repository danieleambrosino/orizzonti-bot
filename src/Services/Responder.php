<?php

declare(strict_types=1);

namespace Bot\Services;

use Bot\Dao\DaoInterface;
use Bot\Dto\Request;
use Bot\Dto\Response;
use Bot\Entities\Presentation;
use Bot\Handlers\Factory;

final class Responder
{
	public function __construct(
		private DaoInterface $dao
	) {}

	public function respond(Request $request): ?Response
	{
		$presentation = $this->findPresentation($request->userId);
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
