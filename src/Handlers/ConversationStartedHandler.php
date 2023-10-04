<?php

declare(strict_types=1);

namespace Bot\Handlers;

use Bot\Presentation;
use Bot\Request;
use Bot\Response;
use Bot\Status;

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
