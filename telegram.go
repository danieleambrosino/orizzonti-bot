package main

import (
	"strings"
)

type TelegramUpdate struct {
	Message struct {
		Id   uint64 `json:"message_id"`
		From struct {
			Id        uint64 `json:"id"`
			Username  string `json:"username"`
			FirstName string `json:"first_name"`
			LastName  string `json:"last_name"`
		} `json:"from"`
		Chat struct {
			Id uint64 `json:"id"`
		} `json:"chat"`
		Text     string `json:"text"`
		Entities []struct {
			Type string `json:"type"`
		} `json:"entities"`
	}
}

func (u *TelegramUpdate) isStartCommand() bool {
	return len(u.Message.Entities) > 0 && u.Message.Entities[0].Type == "bot_command" && strings.HasPrefix(u.Message.Text, "/mipresento")
}

type TelegramResponse struct {
	Method           string `json:"method"`
	ChatId           uint64 `json:"chat_id"`
	Text             string `json:"text"`
	ReplyToMessageId uint64 `json:"reply_to_message_id"`
	ReplyMarkup      struct {
		ForceReply bool `json:"force_reply"`
		Selective  bool `json:"selective"`
	} `json:"reply_markup"`
}
