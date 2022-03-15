<?php

namespace Bot;

use Bot\Dao\DaoInterface;

class Responder
{
	private object $request;
	private Presentation $presentation;

	public static function create(DaoInterface $dao)
	{
		return new Responder($dao);
	}

	private function __construct(private DaoInterface $dao)
	{
		$this->request = json_decode(file_get_contents('php://input'));
		if (!isset($this->request->message)) exit;
		$this->presentation = $this->dao->find($this->request->message->from->id) ?? new Presentation($this->request->message->from->id);
	}

	public function handle()
	{
		$text = match ($this->presentation->getStatus()) {
			Status::ConversationStarted => $this->handleConversationStarted(),
			Status::PresentationRequested => $this->handlePresentationRequested(),
			Status::InvitorRequested => $this->handleInvitorRequested()
		};
		if (!empty($text)) $this->send($text);
	}

	private function handleConversationStarted(): ?string
	{
		if (
			empty($this->request->message->entities) ||
			$this->request->message->entities[0]->type !== 'bot_command' ||
			!str_starts_with($this->request->message->text, '/mipresento')
		) {
			return null;
		}

		$this->presentation
			->setUsername($this->request->message->from->username ?? null)
			->setStatus(Status::PresentationRequested);
		$this->dao->persist($this->presentation);
		return "Ciao {$this->request->message->from->first_name}, benvenuto! Scrivi qui la tua presentazione:";
	}

	private function handlePresentationRequested(): string
	{
		$this->presentation
			->setPresentation($this->request->message->text)
			->setStatus(Status::InvitorRequested);
		$this->dao->persist($this->presentation);
		return 'Da chi sei stato invitato?';
	}

	private function handleInvitorRequested(): string
	{
		$this->presentation
			->setInvitor($this->request->message->text)
			->setStatus(Status::ConversationStarted);
		$this->dao->persist($this->presentation);
		return 'Grazie!';
	}

	private function send(string $text)
	{
		header('Content-Type: application/json');
		echo json_encode([
			'method' => 'sendMessage',
			'chat_id' => $this->request->message->chat->id,
			'text' => $text
		]);
	}
}
