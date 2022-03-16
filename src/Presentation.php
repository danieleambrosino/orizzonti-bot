<?php

namespace Bot;

class Presentation
{
	public function __construct(
		private int $userId,
		private ?string $username = null,
		private ?string $firstName = null,
		private ?string $lastName = null,
		private Status|int $status = Status::ConversationStarted,
		private ?string $presentation = null,
		private ?string $invitor = null,
	) {
		if (is_int($status)) {
			$this->status = Status::from($status);
		}
	}

	public function getUserId(): int
	{
		return $this->userId;
	}

	public function setUsername(?string $username): self
	{
		$this->username = $username;
		return $this;
	}

	public function setFirstName(?string $firstName): self
	{
		$this->firstName = $firstName;
		return $this;
	}

	public function setLastName(?string $lastName): self
	{
		$this->lastName = $lastName;
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
			'firstName' => $this->firstName,
			'lastName' => $this->lastName,
			'status' => $this->status->value,
			'presentation' => $this->presentation,
			'invitor' => $this->invitor,
		];
	}

	public function __unserialize(array $data): void
	{
		$this->userId = $data['userId'];
		$this->username = $data['username'];
		$this->firstName = $data['firstName'];
		$this->lastName = $data['lastName'];
		$this->status = Status::from($data['status']);
		$this->presentation = $data['presentation'];
		$this->invitor = $data['invitor'];
	}
}
