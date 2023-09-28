<?php

declare(strict_types=1);

namespace Bot;

readonly class Response
{
	public function __construct(
		public string $text,
		public bool $forceReply,
	) {}
}
