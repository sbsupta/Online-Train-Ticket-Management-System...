(function() {
    // Create floating button
    const chatBtn = document.createElement("button");
    chatBtn.innerHTML = "💬 Chat";
    chatBtn.style.position = "fixed";
    chatBtn.style.bottom = "20px";
    chatBtn.style.right = "20px";
    chatBtn.style.zIndex = "9999";
    chatBtn.style.padding = "12px 20px";
    chatBtn.style.borderRadius = "50px";
    chatBtn.style.border = "none";
    chatBtn.style.background = "#3498db";
    chatBtn.style.color = "white";
    chatBtn.style.fontSize = "16px";
    chatBtn.style.boxShadow = "0 4px 8px rgba(0,0,0,0.2)";
    document.body.appendChild(chatBtn);

    // Create chat box
    const chatBox = document.createElement("div");
    chatBox.style.position = "fixed";
    chatBox.style.bottom = "70px";
    chatBox.style.right = "20px";
    chatBox.style.width = "300px";
    chatBox.style.height = "400px";
    chatBox.style.background = "white";
    chatBox.style.borderRadius = "10px";
    chatBox.style.boxShadow = "0 4px 12px rgba(0,0,0,0.2)";
    chatBox.style.display = "none";
    chatBox.style.flexDirection = "column";
    chatBox.style.overflow = "hidden";
    chatBox.style.zIndex = "9999";

    // Chat header
    const header = document.createElement("div");
    header.innerHTML = "🤖 OTTMS Chatbot";
    header.style.background = "#3498db";
    header.style.color = "white";
    header.style.padding = "10px";
    header.style.fontWeight = "bold";
    chatBox.appendChild(header);

    // Messages area
    const messages = document.createElement("div");
    messages.style.flex = "1";
    messages.style.padding = "10px";
    messages.style.overflowY = "auto";
    chatBox.appendChild(messages);

    // Input area
    const inputArea = document.createElement("div");
    inputArea.style.display = "flex";
    inputArea.style.borderTop = "1px solid #ddd";
    const input = document.createElement("input");
    input.type = "text";
    input.placeholder = "Ask me something...";
    input.style.flex = "1";
    input.style.border = "none";
    input.style.padding = "10px";
    const sendBtn = document.createElement("button");
    sendBtn.innerHTML = "➤";
    sendBtn.style.border = "none";
    sendBtn.style.background = "#3498db";
    sendBtn.style.color = "white";
    sendBtn.style.padding = "0 15px";
    inputArea.appendChild(input);
    inputArea.appendChild(sendBtn);
    chatBox.appendChild(inputArea);

    document.body.appendChild(chatBox);

    // Toggle chat
    chatBtn.onclick = () => {
        chatBox.style.display = chatBox.style.display === "none" ? "flex" : "none";
    };

    // Simple responses (FAQ-style)
    const responses = {
        "hello": "Hi there! 👋 How can I help you?",
        "book ticket": "To book a ticket, please log in and search trains from your dashboard.",
        "payment": "Payments are 100% secure ✅. You can pay from your dashboard.",
        "help": "Sure! You can book tickets, check announcements, and manage bookings here.",
        "bye": "Goodbye! 👋"
    };

    function addMessage(text, from = "bot") {
        const msg = document.createElement("div");
        msg.style.margin = "5px 0";
        msg.style.padding = "8px 12px";
        msg.style.borderRadius = "8px";
        msg.style.maxWidth = "80%";
        msg.style.wordWrap = "break-word";
        if (from === "bot") {
            msg.style.background = "#f1f1f1";
            msg.style.alignSelf = "flex-start";
        } else {
            msg.style.background = "#3498db";
            msg.style.color = "white";
            msg.style.alignSelf = "flex-end";
        }
        msg.innerText = text;
        messages.appendChild(msg);
        messages.scrollTop = messages.scrollHeight;
    }

    sendBtn.onclick = () => {
        const userText = input.value.trim();
        if (!userText) return;
        addMessage(userText, "user");
        input.value = "";
        let reply = "Sorry, I didn’t understand that. 🤔";
        for (const key in responses) {
            if (userText.toLowerCase().includes(key)) {
                reply = responses[key];
                break;
            }
        }
        setTimeout(() => addMessage(reply, "bot"), 500);
    };

    input.addEventListener("keypress", e => {
        if (e.key === "Enter") sendBtn.click();
    });
})();
