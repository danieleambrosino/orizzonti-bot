<?php

declare(strict_types=1);

namespace Bot\Handlers;

use Bot\Presentation;
use Bot\Request;
use Bot\Response;
use Bot\Status;

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
