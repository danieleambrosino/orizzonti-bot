<?php

declare(strict_types=1);

namespace Bot\Handlers;

use Bot\Presentation;
use Bot\Response;

interface HandlerInterface
{
	public function handle(object $request, Presentation $presentation): ?Response;
}
