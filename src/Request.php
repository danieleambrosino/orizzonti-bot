<?php

declare(strict_types=1);

namespace Bot;

readonly class Request
{
	public function __construct(
		public int $userId = 0,
		public ?int $username = null,
		public string $firstName = '',
		public ?string $lastName = null,
		public int $chatId = 0,
		public string $text = '',
		/** @var array<string,stdClass> */
		public ?array $entities = null,
	) {}

	/**
	 * @throws \JsonException
	 * @throws \InvalidArgumentException
	 */
	public static function fromJsonString(string $input): self
	{
		/** @var object */
		$request = json_decode(
			$input,
			flags: JSON_THROW_ON_ERROR,
		);

		try {
			return new self(
				$request->message?->from?->id ?? null,
				$request->message?->from?->username ?? null,
				$request->message?->from?->first_name ?? null,
				$request->message?->from?->last_name ?? null,
				$request->message?->chat?->id ?? null,
				$request->message?->text ?? null,
				$request->message?->entities ?? null,
			);
		} catch (\TypeError) {
			throw new \InvalidArgumentException('Invalid schema');
		}
	}

	public function isStartCommand(): bool
	{
		return !empty($this->entities)
			&& 'bot_command' === $this->entities[0]->type
			&& str_starts_with($this->text, '/mipresento');
	}
}
