<?php

declare(strict_types=1);

namespace Bot;

class Presentation
{
	public Status $status;

	public function __construct(
		public int $userId,
		public ?string $username = null,
		public ?string $firstName = null,
		public ?string $lastName = null,
		Status|int $status = Status::ConversationStarted,
		public ?string $presentation = null,
		public ?string $inviter = null,
	) {
		if (is_int($status)) {
			$this->status = Status::from($status);
		} else {
			$this->status = $status;
		}
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
			'inviter' => $this->inviter,
		];
	}

	// @phpstan-ignore-next-line
	public function __unserialize(array $data): void
	{
		$this->userId = $data['userId'];
		$this->username = $data['username'];
		$this->firstName = $data['firstName'];
		$this->lastName = $data['lastName'];
		$this->status = Status::from($data['status']);
		$this->presentation = $data['presentation'];
		$this->inviter = $data['inviter'];
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

	public function setInviter(string $inviter): self
	{
		$this->inviter = $inviter;

		return $this;
	}

	public function setStatus(Status $status): self
	{
		$this->status = $status;

		return $this;
	}
}
