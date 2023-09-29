package main

const (
	ConversationStarted uint8 = iota
	PresentationRequested
	InviterRequested
	ConversationEnded
)

type Presentation struct {
	UserId       uint64
	Username     string
	FirstName    string
	LastName     string
	Status       uint8
	Presentation string
	Inviter      string
}
