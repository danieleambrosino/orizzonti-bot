<?php

declare(strict_types=1);

namespace Bot;

readonly class Response
{
	public function __construct(
		public string $text,
		public bool $forceReply,
	) {}

	// @phpstan-ignore-next-line
	public function toTelegramResponse(int $chatId, int $messageId): array
	{
		$content = [
			'method' => 'sendMessage',
			'chat_id' => $chatId,
			'text' => $this->text,
			'reply_to_message_id' => $messageId,
		];
		if ($this->forceReply) {
			$content['reply_markup'] = [
				'force_reply' => true,
				'selective' => true,
			];
		}

		return $content;
	}
}
