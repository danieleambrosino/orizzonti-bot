<?php

declare(strict_types=1);

namespace Bot\Handlers;

use Bot\Enums\Status;

class Factory
{
	public static function create(Status $status): HandlerInterface
	{
		return match ($status) {
			Status::ConversationStarted => new ConversationStartedHandler(),
			Status::PresentationRequested => new PresentationRequestedHandler(),
			Status::InviterRequested => new InviterRequestedHandler(),
			Status::ConversationEnded => new ConversationEndedHandler(),
		};
	}
}
