<?php

declare(strict_types=1);

namespace Bot\Handlers;

use Bot\Dto\Request;
use Bot\Dto\Response;
use Bot\Entities\Presentation;
use Bot\Enums\Status;

class ConversationStartedHandler implements HandlerInterface
{
	public function handle(Request $request, Presentation $presentation): ?Response
	{
		if (false === $request->isStartCommand()) {
			return null;
		}

		$presentation
			->setUsername($request->username)
			->setFirstName($request->firstName)
			->setLastName($request->lastName)
			->setStatus(Status::PresentationRequested)
		;

		return new Response(
			text: "Ciao {$request->firstName}, benvenuto! Scrivi qui la tua presentazione:",
			forceReply: true,
		);
	}
}
