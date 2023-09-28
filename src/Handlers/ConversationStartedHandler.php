<?php

declare(strict_types=1);

namespace Bot\Handlers;

use Bot\Presentation;
use Bot\Response;
use Bot\Status;

use function Bot\isStartCommand;

class ConversationStartedHandler implements HandlerInterface
{
	public function handle(object $request, Presentation $presentation): ?Response
	{
		if (!isStartCommand($request)) {
			return null;
		}

		$presentation
			->setUsername($request->message->from->username ?? null)
			->setFirstName($request->message->from->first_name)
			->setLastName($request->message->from->last_name ?? null)
			->setStatus(Status::PresentationRequested)
		;

		return new Response(
			text: "Ciao {$request->message->from->first_name}, benvenuto! Scrivi qui la tua presentazione:",
			forceReply: true,
		);
	}
}
