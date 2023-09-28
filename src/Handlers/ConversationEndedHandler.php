<?php

declare(strict_types=1);

namespace Bot\Handlers;

use Bot\Presentation;
use Bot\Response;

use function Bot\isStartCommand;

class ConversationEndedHandler implements HandlerInterface
{
	public function handle(object $request, Presentation $presentation): ?Response
	{
		if (!isStartCommand($request)) {
			return null;
		}

		return new Response(
			'Ti sei già presentato!',
			forceReply: false,
		);
	}
}
