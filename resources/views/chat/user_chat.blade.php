@include('head.head_doc')
<body class="bg-light">
@include('header.header', ['icon' => 'map', 'iconLink' => url('/map')])

<div class="container mt-4 mb-5">
    <div class="row">
        {{-- Main Chat Column --}}
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Чат з {{ $otherUser->first_name }} {{ $otherUser->second_name }}</h4>
                </div>
                <div class="card-body p-0" style="height: 500px; display: flex; flex-direction: column;">
                    <div class="messages-container flex-grow-1 p-3" id="messagesContainer" style="overflow-y: auto;">
                        <p class="text-center text-muted">Завантаження повідомлень...</p>
                    </div>
                    <form id="chatForm" class="chat-input-area d-flex border-top p-3 bg-light" autocomplete="off" onsubmit="return false;">
                        @csrf
                        <input type="text" id="chatMessageInput" class="form-control mr-2" placeholder="Введіть ваше повідомлення..." required>
                        <button type="button" id="sendChatMessageButton" class="btn btn-primary">Відправити</button>
                    </form>
                </div>
            </div>
        </div>
        {{-- Sidebar --}}
        <div class="col-lg-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Користувач</h5>
                </div>
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($otherUser->first_name . ' ' . $otherUser->second_name) }}&background=random&size=100" class="rounded-circle mb-3" alt="User Avatar">
                    <h5>{{ $otherUser->first_name }} {{ $otherUser->second_name }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>

@include('footer.footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Use conversation id for AJAX endpoints
    const fetchMessagesUrl = '/chat/conversation/{{ $conversation->id }}/messages';
    const sendMessageUrl = '/chat/conversation/{{ $conversation->id }}/messages/send';
    let lastMessagesJson = '';

    function loadMessages() {
        $.get(fetchMessagesUrl, function(data) {
            const newMessagesJson = JSON.stringify(data);
            if (newMessagesJson !== lastMessagesJson) {
                lastMessagesJson = newMessagesJson;
                let html = '';
                if (data.length === 0) {
                    html = '<p class="text-center text-muted">Немає повідомлень.</p>';
                } else {
                    data.forEach(msg => {
                        html += `
                        <div class="mb-3 d-flex ${msg.is_current_user ? 'justify-content-end' : 'justify-content-start'}">
                            <div class="p-2 rounded ${msg.is_current_user ? 'bg-primary text-white' : 'bg-light border'}" style="max-width: 70%;">
                                <div class="mb-1"><strong>${msg.sender_display_name}</strong></div>
                                <div>${msg.body}</div> <!-- CHANGED from msg.message to msg.body -->
                                <div class="text-right"><small class="${msg.is_current_user ? 'text-white' : 'text-muted'}">${msg.created_at}</small></div>
                            </div>
                        </div>
                        `;
                    });
                }
                $('#messagesContainer').html(html);
                $('#messagesContainer').scrollTop($('#messagesContainer')[0].scrollHeight);
            }
        });
    }

    function sendMessage() {
        const message = $('#chatMessageInput').val().trim();
        if (!message) return;

        $.ajax({
            url: sendMessageUrl,
            method: 'POST',
            data: {
                body: message,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Якщо сервер повернув успішну відповідь
                $('#chatMessageInput').val(''); // Очищуємо поле
                loadMessages(); // Негайно завантажуємо повідомлення, щоб побачити своє
            },
            error: function(xhr, status, error) {
                // ЯКЩО СЕРВЕР ПОВЕРНУВ ПОМИЛКУ
                console.error("AJAX Error:", xhr.responseText);
                alert("Не вдалося відправити повідомлення. Перевірте консоль розробника (F12) для деталей.");
            }
        });
    }

    $(document).ready(function() {
        loadMessages();
        setInterval(loadMessages, 3000);
        $('#sendChatMessageButton').on('click', sendMessage);
        $('#chatMessageInput').on('keypress', function(e) {
            if (e.which === 13) sendMessage();
        });
    });
</script>
</body>
