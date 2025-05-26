<script>
let currentChatId = null;
let lastMessageId = 0;

function loadMessages(chatId) {
    currentChatId = chatId;
    const chatBox = document.getElementById('chatBox');
    chatBox.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';
    
    fetch(`chat_messages.php?chat_id=${chatId}`)
        .then(response => response.text())
        .then(html => {
            chatBox.innerHTML = html;
            chatBox.scrollTop = chatBox.scrollHeight;
            // Get the last message ID for future updates
            const messages = chatBox.getElementsByClassName('message');
            if (messages.length > 0) {
                lastMessageId = parseInt(messages[messages.length - 1].dataset.messageId);
            }
        });
}

function sendMessage() {
    if (!currentChatId) return;
    
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    if (!message) return;

    const formData = new FormData();
    formData.append('chat_id', currentChatId);
    formData.append('message', message);

    fetch('chat_send.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(() => {
        messageInput.value = '';
        loadMessages(currentChatId);
    });
}

function checkNewMessages() {
    if (!currentChatId) return;
    
    fetch(`chat_messages.php?chat_id=${currentChatId}&last_id=${lastMessageId}`)
        .then(response => response.text())
        .then(html => {
            if (html.trim()) {
                const chatBox = document.getElementById('chatBox');
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                const newMessages = tempDiv.getElementsByClassName('message');
                
                if (newMessages.length > 0) {
                    chatBox.innerHTML = html;
                    chatBox.scrollTop = chatBox.scrollHeight;
                    lastMessageId = parseInt(newMessages[newMessages.length - 1].dataset.messageId);
                }
            }
        });
}

// Check for new messages every 2 seconds
setInterval(checkNewMessages, 2000);

// Handle enter key in message input
document.getElementById('messageInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});
</script>

<style>
    body { background-color: #f8f9fa; }
    .chat-container { max-width: 600px; margin: 40px auto; }
    .chat-box { background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 0; }
    .chat-header { padding: 20px; border-bottom: 1px solid #eee; }
    .chat-messages { padding: 20px; height: 350px; overflow-y: auto; }
    .message { margin-bottom: 10px; }
    .message.admin { text-align: right; }
    .message.user, .message.employee { text-align: left; }
    .message .bubble { display: inline-block; padding: 10px 15px; border-radius: 20px; }
    .message.admin .bubble { background: #007bff; color: #fff; }
    .message.user .bubble, .message.employee .bubble { background: #e9ecef; color: #333; }
    .chat-input-area { border-top: 1px solid #eee; padding: 15px 20px; }
    .chat-selector { padding: 20px; border-bottom: 1px solid #eee; }
    @media (max-width: 600px) {
        .chat-container { margin: 0; }
        .chat-box { border-radius: 0; }
    }
</style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="chat-container">
        <div class="chat-box mt-5">
            <div class="chat-header">
                <h4 class="mb-0">Chats de Admin</h4>
            </div>
            <div class="chat-selector">
                <form method="post" class="mb-3">
                    <div class="row g-2">
                        <div class="col">
                            <select name="target_type" class="form-select" required>
                                <option value="">Nuevo chat con...</option>
                                <option value="user">Usuario</option>
                                <option value="employee">Empleado</option>
                            </select>
                        </div>
                        <div class="col">
                            <select name="target_id" class="form-select" required>
                                <option value="">Selecciona usuario/empleado</option>
                                <?php while ($u = $users->fetch_assoc()): ?>
                                    <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
                                <?php endwhile; ?>
                                <?php while ($e = $employees->fetch_assoc()): ?>
                                    <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Iniciar</button>
                        </div>
                    </div>
                </form>
                <div class="mb-3">
                    <?php while ($row = $chats->fetch_assoc()): ?>
                        <a href="?chat_id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm mb-1">Chat #<?= $row['id'] ?></a>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php if ($chat_id): ?>
                <div id="messages" class="chat-messages">
                    <?php foreach ($messages as $msg): ?>
                        <div class="message <?= $msg['sender_type'] ?>">
                            <span class="bubble"><?= htmlspecialchars($msg['message']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <form id="chatForm" class="chat-input-area d-flex">
                    <input type="text" name="message" id="messageInput" class="form-control me-2" placeholder="Escribe tu mensaje..." autocomplete="off" required>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            <?php else: ?>
                <div class="p-4 text-center">Selecciona un chat para comenzar.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 