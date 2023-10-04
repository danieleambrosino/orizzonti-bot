<?php

declare(strict_types=1);

namespace Bot;

readonly class Request
{
	public function __construct(
		public int $messageId = 0,
		public int $userId = 0,
		public ?string $username = null,
		public string $firstName = '',
		public ?string $lastName = null,
		public int $chatId = 0,
		public string $text = '',
		/** @var object{type: string}[] */
		public ?array $entities = null,
	) {}

	/**
	 * @param non-empty-string $input
	 *
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
				messageId: $request->message?->message_id ?? null,
				userId: $request->message?->from?->id ?? null,
				username: $request->message?->from?->username ?? null,
				firstName: $request->message?->from?->first_name ?? null,
				lastName: $request->message?->from?->last_name ?? null,
				chatId: $request->message?->chat?->id ?? null,
				text: $request->message?->text ?? null,
				entities: $request->message?->entities ?? null,
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
