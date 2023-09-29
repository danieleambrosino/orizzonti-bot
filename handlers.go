package main

import (
	"fmt"
)

type Handler interface {
	generateResponse(*TelegramUpdate, *Presentation) *Response
	updatePresentation(*TelegramUpdate, *Presentation)
}

type ConversationStartedHandler struct{}

func (h *ConversationStartedHandler) generateResponse(u *TelegramUpdate, p *Presentation) *Response {
	if !u.isStartCommand() {
		return nil
	}

	return &Response{
		text:       fmt.Sprintf("Ciao %s, benvenuto! Scrivi qui la tua presentazione:", u.Message.From.FirstName),
		forceReply: true,
	}
}

func (h *ConversationStartedHandler) updatePresentation(u *TelegramUpdate, p *Presentation) {
	p.UserId = u.Message.From.Id
	p.Username = u.Message.From.Username
	p.FirstName = u.Message.From.FirstName
	p.LastName = u.Message.From.LastName
	p.Status = PresentationRequested
}

type PresentationRequestedHandler struct{}

func (h *PresentationRequestedHandler) generateResponse(u *TelegramUpdate, p *Presentation) *Response {
	return &Response{
		text:       "Da chi sei stato invitato?",
		forceReply: true,
	}
}

func (h *PresentationRequestedHandler) updatePresentation(u *TelegramUpdate, p *Presentation) {
	p.Presentation = u.Message.Text
	p.Status = InviterRequested
}

type InviterRequestedHandler struct{}

func (h *InviterRequestedHandler) generateResponse(u *TelegramUpdate, p *Presentation) *Response {
	return &Response{
		text:       "Grazie!",
		forceReply: false,
	}
}

func (h *InviterRequestedHandler) updatePresentation(u *TelegramUpdate, p *Presentation) {
	p.Inviter = u.Message.Text
	p.Status = ConversationEnded
}

type ConversationEndedHandler struct{}

func (h *ConversationEndedHandler) generateResponse(u *TelegramUpdate, p *Presentation) *Response {
	if !u.isStartCommand() {
		return nil
	}

	return &Response{
		text:       "Ti sei già presentato!",
		forceReply: false,
	}
}

func (h *ConversationEndedHandler) updatePresentation(u *TelegramUpdate, p *Presentation) {}

func createHandler(status uint8) Handler {
	switch status {
	case ConversationStarted:
		return &ConversationStartedHandler{}
	case PresentationRequested:
		return &PresentationRequestedHandler{}
	case InviterRequested:
		return &InviterRequestedHandler{}
	case ConversationEnded:
		return &ConversationEndedHandler{}
	default:
		return nil
	}
}
