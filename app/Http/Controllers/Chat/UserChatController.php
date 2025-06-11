<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Participation;
use App\Models\Message;

class UserChatController extends Controller
{
    /**
     * Show the user-to-user chat page, creating a conversation if necessary.
     */
    public function show(User $otherUser)
    {

        $currentUser = Auth::user();


        // Prevent chatting with yourself
        if ($currentUser->id == $otherUser->id) {
            abort(403, "Cannot chat with yourself.");
        }

        // Find existing conversation between these two users with no location
        $conversation = Conversation::whereNull('location_id')
            ->whereHas('participants', function ($q) use ($currentUser) {
                $q->where('user_id', $currentUser->id);
            })
            ->whereHas('participants', function ($q) use ($otherUser) {
                $q->where('user_id', $otherUser->id);
            })
            ->withCount('participants')
            ->having('participants_count', 2)
            ->first();

        // If not found, create a new conversation and participations
        if (!$conversation) {
            $conversation = Conversation::create(['location_id' => null]);
            Participation::create(['conversation_id' => $conversation->id, 'user_id' => $currentUser->id]);
            Participation::create(['conversation_id' => $conversation->id, 'user_id' => $otherUser->id]);
        }

        return view('chat.user_chat', [
            'otherUser' => $otherUser,
            'conversation' => $conversation,
        ]);
    }

    /**
     * Fetch messages for a given conversation.
     * Returns JSON for AJAX.
     */
    public function fetchMessages(Conversation $conversation)
    {
        $currentUser = Auth::user();

        // Ensure user is a participant
        $isParticipant = $conversation->participants()->where('user_id', $currentUser->id)->exists();
        if (!$isParticipant) {
            abort(403, "You are not authorized to view this conversation.");
        }

        $messages = $conversation->messages()->with('user')->orderBy('created_at', 'asc')->get();

        $result = [];
        foreach ($messages as $msg) {
            $result[] = [
                'id' => $msg->id,
                'sender_id' => $msg->user_id,
                'sender_display_name' => $msg->user ? ($msg->user->first_name . ' ' . $msg->user->second_name) : 'Користувач',
                'body' => e($msg->body),
                'created_at' => $msg->created_at->format('d.m.Y H:i'),
                'is_current_user' => $msg->user_id == $currentUser->id,
            ];
        }

        return response()->json($result);
    }

    /**
     * Send a message in a conversation.
     * Returns 200 JSON on success.
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        $currentUser = Auth::user();

        // Ensure user is a participant
        $isParticipant = $conversation->participants()->where('user_id', $currentUser->id)->exists();
        if (!$isParticipant) {
            abort(403, "You are not authorized to send messages in this conversation.");
        }

        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $currentUser->id,
            'body' => $request->input('body'),
        ]);

        // Optionally: broadcast event for real-time updates, etc.

        return response()->json([
            'success' => true,
            'message' => 'Message sent',
            'data' => [
                'id' => $message->id,
                'body' => e($message->body),
                'created_at' => $message->created_at->format('d.m.Y H:i'),
            ]
        ]);
    }
}
