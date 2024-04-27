<?php

declare(strict_types=1);

namespace Bot\Handlers;

use Bot\Dto\Request;
use Bot\Dto\Response;
use Bot\Entities\Presentation;

class ConversationEndedHandler implements HandlerInterface
{
	public function handle(Request $request, Presentation $presentation): ?Response
	{
		if (false === $request->isStartCommand()) {
			return null;
		}

		return new Response(
			'Ti sei già presentato!',
			forceReply: false,
		);
	}
}
