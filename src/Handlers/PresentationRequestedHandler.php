<?php

declare(strict_types=1);

namespace Bot\Handlers;

use Bot\Dto\Request;
use Bot\Dto\Response;
use Bot\Entities\Presentation;
use Bot\Enums\Status;

class PresentationRequestedHandler implements HandlerInterface
{
	public function handle(Request $request, Presentation $presentation): ?Response
	{
		$presentation
			->setPresentation($request->text)
			->setStatus(Status::InviterRequested)
		;

		return new Response(
			'Da chi sei stato invitato?',
			forceReply: true,
		);
	}
}
