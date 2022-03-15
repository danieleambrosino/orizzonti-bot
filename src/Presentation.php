<?php

namespace Bot;

class Presentation
{
	public function __construct(
		private int $userId,
		private ?string $username = null,
		private Status $status = Status::ConversationStarted,
		private ?string $presentation = null,
		private ?string $invitor = null,
	) {
	}

	public function getUserId(): int
	{
		return $this->userId;
	}

	public function setUserId(int $userId): self
	{
		$this->userId = $userId;
		return $this;
	}

	public function setUsername(?string $username): self
	{
		$this->username = $username;
		return $this;
	}
	
	public function setPresentation(string $presentation): self
	{
		$this->presentation = $presentation;
		return $this;
	}
	
	public function setInvitor(string $invitor): self
	{
		$this->invitor = $invitor;
		return $this;
	}

	public function getStatus(): Status
	{
		return $this->status;
	}

	public function setStatus(Status $status): self
	{
		$this->status = $status;
		return $this;
	}

	public function __serialize(): array
	{
		return [
			'userId' => $this->userId,
			'username' => $this->username,
			'status' => $this->status->value,
			'presentation' => $this->presentation,
			'invitor' => $this->invitor,
		];
	}

	public function __unserialize(array $data): void
	{
		$this->userId = $data['userId'];
		$this->username = $data['username'];
		$this->status = Status::from($data['status']);
		$this->presentation = $data['presentation'];
		$this->invitor = $data['invitor'];
	}
}
