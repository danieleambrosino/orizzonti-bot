<?php

declare(strict_types=1);

namespace Bot;

enum Status: int
{
	case ConversationStarted = 0;
	case PresentationRequested = 1;
	case InviterRequested = 2;
	case ConversationEnded = 3;
}
