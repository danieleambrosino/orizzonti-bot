<?php

declare(strict_types=1);

namespace Bot;

/**
 * @param non-empty-string $input
 *
 * @throws \InvalidArgumentException
 */
function decodeInput(string $input): object
{
	$request = json_decode($input);
	if (false === $request instanceof \stdClass) {
		throw new \InvalidArgumentException('Bad format');
	}
	if (!isset($request->message)) {
		throw new \InvalidArgumentException('Invalid schema');
	}

	return $request;
}

function isStartCommand(object $request): bool
{
	return !empty($request->message->entities)
		&& 'bot_command' === $request->message->entities[0]->type
		&& str_starts_with($request->message->text, '/mipresento');
}

function getTelegramResponse(Response $response, int $chatId, int $messageId): array
{
	$content = [
		'method' => 'sendMessage',
		'chat_id' => $chatId,
		'text' => $response->text,
		'reply_to_message_id' => $messageId,
	];
	if ($response->forceReply) {
		$content['reply_markup'] = [
			'force_reply' => true,
			'selective' => true,
		];
	}

	return $content;
}
