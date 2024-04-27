<?php

declare(strict_types=1);

namespace Bot\Handlers;

use Bot\Dto\Request;
use Bot\Dto\Response;
use Bot\Entities\Presentation;
use Bot\Enums\Status;

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
