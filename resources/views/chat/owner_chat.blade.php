{{-- resources/views/chat/owner_chat.blade.php --}}
@include('head.head_doc')
<body class="bg-light">
@include('header.header', ['icon' => 'map', 'iconLink' => url('/map')])

<div class="container mt-4 mb-5">
    <div class="row">
        {{-- Main Chat Column --}}
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Чат з {{ $customer->first_name }} {{ $customer->second_name }}</h4>
                    <small>Локація: {{ $location->title }}</small>
                </div>
                <div class="card-body p-0" style="height: 500px; display: flex; flex-direction: column;">
                    <div class="messages-container flex-grow-1 p-3" id="messagesContainer" style="overflow-y: auto;">
                        @foreach($messages as $message)
                            <div class="mb-3 d-flex {{ $message->sender_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="p-2 rounded
                                    {{ $message->sender_id == Auth::id() ? 'bg-primary text-white' : 'bg-light border' }}"
                                     style="max-width: 70%;">
                                    <div class="mb-1">
                                        <strong>{{ $message->sender->first_name }}</strong>
                                    </div>
                                    <div>{{ $message->message }}</div>
                                    <div class="text-right">
                                        <small class="text-muted">{{ $message->created_at->format('H:i, d M') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <form action="{{ route('chat.owner.send', ['location' => $location->id, 'customer' => $customer->id]) }}" method="POST" class="chat-input-area d-flex border-top p-3 bg-light">
                        @csrf
                        <input type="text" name="message" class="form-control mr-2" placeholder="Введіть ваше повідомлення..." required>
                        <button type="submit" class="btn btn-primary">Відправити</button>
                    </form>
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
                    @if($location->photo_path || (is_array($location->photo_paths) && count($location->photo_paths)))
                        <img src="{{ asset('storage/' . (is_array($location->photo_paths) ? $location->photo_paths[0] : $location->photo_path)) }}"
                             class="img-fluid rounded mb-3" style="max-height: 180px; object-fit: cover;" alt="{{ $location->title }}">
                    @endif
                    <h5>{{ $location->title }}</h5>
                    <p class="text-muted mb-1">{{ $location->description }}</p>
                </div>
            </div>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Клієнт</h5>
                </div>
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->first_name . ' ' . $customer->second_name) }}&background=random&size=100" class="rounded-circle mb-3" alt="Customer Avatar">
                    <h5>{{ $customer->first_name }} {{ $customer->second_name }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>

@include('footer.footer')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const fetchMessagesUrl = @json(route('chat.fetchMessages', ['location' => $location->id, 'customer' => $customer->id]));
    const sendMessageUrl = @json(route('chat.sendMessage', ['location' => $location->id]));
    let lastMessagesJson = '';

    function loadMessages() {
        $.get(fetchMessagesUrl, function(data) {
            const newMessagesJson = JSON.stringify(data);
            console.log('Loaded messages:', data);
            if (newMessagesJson !== lastMessagesJson) {
                lastMessagesJson = newMessagesJson;
                let html = '';
                if (data.length === 0) {
                    html = '<p class="text-center text-muted">No messages yet.</p>';
                } else {
                    data.forEach(msg => {
                        html += `
                        <div class="mb-3 d-flex ${msg.is_current_user ? 'justify-content-end' : 'justify-content-start'}">
                            <div class="p-2 rounded ${msg.is_current_user ? 'bg-primary text-white' : 'bg-light border'}" style="max-width: 70%;">
                                <div class="mb-1"><strong>${msg.sender_display_name}</strong></div>
                                <div>${msg.message}</div>
                                <div class="text-right"><small class="text-white">${msg.created_at}</small></div>
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
                _token: '{{ csrf_token() }}'
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
