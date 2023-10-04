<?php

declare(strict_types=1);

namespace Bot\Handlers;

use Bot\Presentation;
use Bot\Request;
use Bot\Response;
use Bot\Status;

class InviterRequestedHandler implements HandlerInterface
{
	public function handle(Request $request, Presentation $presentation): ?Response
	{
		$presentation
			->setInviter($request->text)
			->setStatus(Status::ConversationEnded)
		;

		return new Response(
			'Grazie!',
			forceReply: false,
		);
	}
}
