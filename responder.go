package main

type Responder struct {
	dao     Dao
	handler Handler
}

type Response struct {
	text       string
	forceReply bool
}

func newResponder(dao Dao) *Responder {
	responder := &Responder{dao: dao}
	return responder
}

func (r *Responder) respond(u *TelegramUpdate) *Response {
	presentation := r.dao.find(u.Message.From.Id)
	r.handler = createHandler(presentation.Status)
	response := r.handler.generateResponse(u, presentation)
	r.handler.updatePresentation(u, presentation)
	r.dao.persist(presentation)
	return response
}

func (r *Response) toTelegramResponse(u *TelegramUpdate) *TelegramResponse {
	if r == nil {
		return nil
	}
	telegramResponse := TelegramResponse{
		Method:           "sendMessage",
		ChatId:           u.Message.Chat.Id,
		Text:             r.text,
		ReplyToMessageId: u.Message.Id,
	}
	if r.forceReply {
		telegramResponse.ReplyMarkup = struct {
			ForceReply bool `json:"force_reply"`
			Selective  bool `json:"selective"`
		}{true, true}
	}
	return &telegramResponse
}
