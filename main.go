package main

import (
	"encoding/json"
	"net/http"
)

var dao Dao = newInMemoryDao()

func handleUpdate(w http.ResponseWriter, r *http.Request) {
	var update TelegramUpdate
	err := json.NewDecoder(r.Body).Decode(&update)
	if err != nil {
		http.Error(w, "Bad request", http.StatusBadRequest)
		return
	}
	responder := newResponder(dao)
	response := responder.respond(&update)
	if response == nil {
		return
	}
	telegramResponse := response.toTelegramResponse(&update)
	w.Header().Add("content-type", "application/json")
	json.NewEncoder(w).Encode(telegramResponse)
}

func main() {
	http.HandleFunc("/", handleUpdate)
	http.ListenAndServe("127.0.0.1:8000", nil)
}
