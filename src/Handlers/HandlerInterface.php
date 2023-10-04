<?php

declare(strict_types=1);

namespace Bot\Handlers;

use Bot\Presentation;
use Bot\Request;
use Bot\Response;

interface HandlerInterface
{
	public function handle(Request $request, Presentation $presentation): ?Response;
}
