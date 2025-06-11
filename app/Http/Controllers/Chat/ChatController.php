<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Location;
use App\Models\Message;
use App\Models\Participation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Fetch messages between a customer and location (creator)
    public function fetchMessages(Location $location, User $customer)
    {
        // Find or create a conversation for this location and customer
        $conversation = Conversation::where('location_id', $location->id)
            ->whereHas('participants', function($q) use ($customer) {
                $q->where('user_id', $customer->id);
            })
            ->first();

        if (!$conversation) {
            return response()->json([]); // No messages yet
        }

        $messages = $conversation->messages()->with('user')->orderBy('created_at')->get();

        $currentUserId = Auth::id();

        return $messages->map(function ($msg) use ($currentUserId) {
            return [
                'id' => $msg->id,
                'sender_display_name' => $msg->user->first_name . ' ' . $msg->user->second_name,
                'message' => $msg->body,
                'created_at' => $msg->created_at->format('H:i d.m.Y'),
                'is_current_user' => $msg->user_id == $currentUserId,
            ];
        });
    }

    // Send a message in a location chat
    public function sendMessage(Request $request, Location $location)
    {
        $request->validate(['message' => 'required|string']);
        $currentUser = Auth::user();

        // Find or create the conversation
        $conversation = Conversation::firstOrCreate(
            ['location_id' => $location->id, 'type' => 'private'],
            []
        );

        // Ensure both participants are in the conversation (customer and location creator)
        $creator = $location->user; // adjust if your location's creator field is named differently

        foreach ([$currentUser->id, $creator->id] as $uid) {
            Participation::firstOrCreate([
                'conversation_id' => $conversation->id,
                'user_id' => $uid,
            ]);
        }

        // Create the message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $currentUser->id,
            'body' => $request->input('message'),
        ]);

        return response()->json(['status' => 'sent']);
    }

    public function startConversation(Request $request, \App\Models\Location $location)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        $user = auth()->user();

        // Find or create the conversation for this user and location
        $conversation = \App\Models\Conversation::firstOrCreate(
            [
                'location_id' => $location->id,
                'type' => 'private'
            ]
        );

        // Add participants: the current user and the location creator
        $creatorId = $location->user_id; // Adjust if your creator field is named differently
        foreach ([$user->id, $creatorId] as $uid) {
            \App\Models\Participation::firstOrCreate([
                'conversation_id' => $conversation->id,
                'user_id' => $uid,
            ]);
        }

        // Save the initial message
        \App\Models\Message::create([
            'conversation_id' => $conversation->id,
            'user_id'        => $user->id,
            'body'           => $request->message,
        ]);

        // Optionally, return the chat URL for redirection
        return response()->json([
            'redirect' => route('chat.location', ['location' => $location->id])
        ]);
    }

    public function showLocationChat(\App\Models\Location $location)
    {
        $customer = auth()->user();
        return view('chat.chat', compact('location', 'customer'));
    }

    public function showOwnerChat(Location $location, User $customer)
    {
        // Only allow the owner of the location to access this
        abort_unless($location->user_id == auth()->id(), 403);

        return view('chat.chat', [
            'location' => $location,
            'customer' => $customer,
        ]);
    }

    public function list()
    {
        $userId = auth()->id();

        // Get all conversations where the user is a participant
        $participations = \App\Models\Participation::where('user_id', $userId)
            ->with(['conversation', 'conversation.location', 'conversation.participants.user', 'conversation.messages' => function($q) {
                $q->latest();
            }])
            ->get();

        $chats = [];

        foreach ($participations as $participation) {
            $conversation = $participation->conversation;
            if (!$conversation) continue;

            // Get last message
            $lastMessage = $conversation->messages->sortByDesc('created_at')->first();
            if (!$lastMessage) continue;

            if ($conversation->location) {
                // User-location chat
                // Get the other participant (customer or owner)
                $otherParticipation = $conversation->participants()->where('user_id', '!=', $userId)->first();
                $customer = $otherParticipation ? $otherParticipation->user : null;

                if (!$customer) continue;

                $chats[] = [
                    'type' => 'location',
                    'location' => $conversation->location,
                    'customer' => $customer,
                    'last_message' => (object)[
                        'message' => $lastMessage->body,
                        'created_at' => $lastMessage->created_at,
                    ],
                ];
            } else {
                // User-user chat
                // Get the other participant
                $otherParticipation = $conversation->participants()->where('user_id', '!=', $userId)->first();
                $otherUser = $otherParticipation ? $otherParticipation->user : null;

                if (!$otherUser) continue;

                $chats[] = [
                    'type' => 'user',
                    'other_user' => $otherUser,
                    'conversation' => $conversation,
                    'last_message' => (object)[
                        'message' => $lastMessage->body,
                        'created_at' => $lastMessage->created_at,
                    ],
                ];
            }
        }

        // Sort by last_message date desc
        $chats = collect($chats)->sortByDesc(function ($chat) {
            return $chat['last_message']->created_at;
        })->values();

        return view('chat.owner_chats', [
            'chats' => $chats,
        ]);
    }
}
