<?php

declare(strict_types=1);

namespace Bot\Handlers;

use Bot\Dto\Request;
use Bot\Dto\Response;
use Bot\Entities\Presentation;

interface HandlerInterface
{
	public function handle(Request $request, Presentation $presentation): ?Response;
}
