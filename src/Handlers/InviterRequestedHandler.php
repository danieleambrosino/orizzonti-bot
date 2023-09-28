<?php

declare(strict_types=1);

namespace Bot\Handlers;

use Bot\Presentation;
use Bot\Response;
use Bot\Status;

class InviterRequestedHandler implements HandlerInterface
{
	public function handle(object $request, Presentation $presentation): ?Response
	{
		$presentation
			->setInviter($request->message->text)
			->setStatus(Status::ConversationEnded)
		;

		return new Response(
			'Grazie!',
			forceReply: false,
		);
	}
}
