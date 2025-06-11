{{-- resources/views/chat/owner_chats.blade.php --}}
@include('head.head_doc')
<body class="bg-light">
@include('header.header', ['icon' => 'map', 'iconLink' => url('/map')])

<div class="container mt-4 mb-5">
    <div class="row">
        {{-- Main Chats List --}}
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Ваші чати</h4>
                </div>
                <div class="card-body p-0">
                    @if($chats->isEmpty())
                        <p class="p-4 text-center text-muted mb-0">Поки немає активних чатів.</p>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($chats as $chat)
                                @if($chat['type'] === 'location')
                                    @php
                                        $isOwner = auth()->id() === $chat['location']->user_id;
                                        $chatUrl = $isOwner
                                            ? route('chat.owner', ['location' => $chat['location']->id, 'customer' => $chat['customer']->id])
                                            : route('chat.location', ['location' => $chat['location']->id]);
                                    @endphp
                                    <a href="{{ $chatUrl }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">{{ $chat['customer']->first_name }} {{ $chat['customer']->second_name }}</h5>
                                            <small>{{ $chat['last_message']->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1">Location: {{ $chat['location']->title }}</p>
                                        <small class="text-muted">{{ Str::limit($chat['last_message']->message, 50) }}</small>
                                    </a>
                                @elseif($chat['type'] === 'user')
                                    @php
                                        $chatUrl = route('user-chat.show', ['otherUser' => $chat['other_user']->id]);
                                    @endphp
                                    <a href="{{ $chatUrl }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">{{ $chat['other_user']->first_name }} {{ $chat['other_user']->second_name }}</h5>
                                            <small>{{ $chat['last_message']->created_at->diffForHumans() }}</small>
                                        </div>
                                        <small class="text-muted">{{ Str::limit($chat['last_message']->message, 50) }}</small>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- Sidebar --}}
        <div class="col-lg-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Порада</h5>
                </div>
                <div class="card-body">
                    <p>Відповідайте на повідомлення клієнтів швидко для кращого сервісу!</p>
                </div>
            </div>
        </div>
    </div>
</div>

@include('footer.footer')
</body>
</html>
