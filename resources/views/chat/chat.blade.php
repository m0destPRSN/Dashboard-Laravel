@include('head.head_doc')
<body class="bg-light">
@include('header.header', ['icon' => 'map', 'iconLink' => url('/map')])

<div class="container mt-4 mb-5">
    <div class="row">
        {{-- Main Chat Column --}}
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        @if(auth()->id() === $location->user_id)
                            Чат з {{ $customer->first_name }} {{ $customer->second_name }}
                        @else
                            Чат з локацією: {{ $location->title }}
                        @endif
                    </h4>
                </div>
                <div class="card-body p-0" style="height: 500px; display: flex; flex-direction: column;">
                    <div class="messages-container flex-grow-1 p-3" id="messagesContainer" style="overflow-y: auto;">
                        <p class="text-center text-muted">Завантаження повідомлень...</p>
                    </div>
                    <div class="chat-input-area d-flex border-top p-3 bg-light">
                        <input type="text" id="chatMessageInput" class="form-control mr-2" placeholder="Введіть ваше повідомлення...">
                        <button id="sendChatMessageButton" class="btn btn-primary">Відправити</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Sidebar --}}
        <div class="col-lg-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Інформація про локацію</h5>
                </div>
                <div class="card-body text-center">
                    @if($location->photo_path || (is_array($location->photo_paths ?? null) && count($location->photo_paths ?? [])))
                        <img src="{{ asset('storage/' . (is_array($location->photo_paths ?? null) ? $location->photo_paths[0] : $location->photo_path)) }}"
                             class="img-fluid rounded mb-3" style="max-height: 180px; object-fit: cover;" alt="{{ $location->title }}">
                    @endif
                    <h5>{{ $location->title }}</h5>
                    <p class="text-muted mb-1">{{ $location->description }}</p>
                    @if($location->user)
                        <small>Власник: {{ $location->user->first_name }} {{ $location->user->second_name }}</small>
                    @endif
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <span class="text-success">&#9679;</span>
                    <span class="text-muted">Ви спілкуєтесь як: <strong>{{ auth()->user()->first_name }} {{ auth()->user()->second_name }}</strong></span>
                </div>
            </div>
        </div>
    </div>
</div>

@include('footer.footer')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Pass PHP variables to JS
    const fetchMessagesUrl = @json(route('chat.fetchMessages', ['location' => $location->id, 'customer' => $customer->id]));
    const sendMessageUrl = @json(route('chat.sendMessage', ['location' => $location->id]));
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
                                <div>${msg.message}</div>
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
                message: message,
                _token: '{{ csrf_token() }}',
                customer_id: {{ $customer->id }} // Always pass the customer ID
            },
            success: function() {
                $('#chatMessageInput').val('');
                loadMessages();
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
