<?php

namespace Bot;

use Bot\Dao\DaoInterface;

final class Responder
{
	private object $request;
	private Presentation $presentation;

	private static ?self $instance = null;

	public static function create(DaoInterface $dao): self
	{
		if (!self::$instance) {
			self::$instance = new self($dao);
		}
		return self::$instance;
	}

	private function __construct(private DaoInterface $dao)
	{
		$this->request = json_decode(file_get_contents('php://input'));
		if (!isset($this->request->message)) exit;
		$this->presentation = $this->dao->find($this->request->message->from->id) ?? new Presentation($this->request->message->from->id);
	}

	public function handle(): void
	{
		[$text, $forceReply] = match ($this->presentation->getStatus()) {
			Status::ConversationStarted => $this->handleConversationStarted(),
			Status::PresentationRequested => $this->handlePresentationRequested(),
			Status::InviterRequested => $this->handleInviterRequested(),
			Status::ConversationEnded => $this->handleConversationEnded(),
		};
		$this->dao->persist($this->presentation);
		$this->send($text, $forceReply);
	}

	private function handleConversationStarted(): array
	{
		if (!$this->startCommandSent()) exit;

		$this->presentation
			->setUsername($this->request->message->from->username ?? null)
			->setFirstName($this->request->message->from->first_name)
			->setLastName($this->request->message->from->last_name ?? null)
			->setStatus(Status::PresentationRequested);

		return [
			"Ciao {$this->request->message->from->first_name}, benvenuto! Scrivi qui la tua presentazione:",
			true,
		];
	}

	private function handlePresentationRequested(): array
	{
		$this->presentation
			->setPresentation($this->request->message->text)
			->setStatus(Status::InviterRequested);

		return [
			'Da chi sei stato invitato?',
			true,
		];
	}

	private function handleInviterRequested(): array
	{
		$this->presentation
			->setInviter($this->request->message->text)
			->setStatus(Status::ConversationEnded);

		return [
			'Grazie!',
			false,
		];
	}

	private function handleConversationEnded(): array
	{
		if (!$this->startCommandSent()) exit;

		return [
			'Ti sei già presentato!',
			false,
		];
	}

	private function startCommandSent(): bool
	{
		return !empty($this->request->message->entities) &&
			$this->request->message->entities[0]->type === 'bot_command' &&
			str_starts_with($this->request->message->text, '/mipresento');
	}

	private function send(string $text, bool $forceReply): void
	{
		header('Content-Type: application/json');
		$response = [
			'method' => 'sendMessage',
			'chat_id' => $this->request->message->chat->id,
			'text' => $text,
			'reply_to_message_id' => $this->request->message->message_id,
		];
		if ($forceReply) {
			$response['reply_markup'] = [
				'force_reply' => true,
				'selective' => true,
			];
		}
		echo json_encode($response);
	}
}
