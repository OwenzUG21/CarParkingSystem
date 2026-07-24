<?php

namespace App\Http\Controllers\API;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    public function index()
    {
        $messages = ChatMessage::query()
            ->latest()
            ->take(100)
            ->get()
            ->map(function (ChatMessage $message) {
                return $this->transformMessage($message);
            })
            ->values();

        return response()->json(['data' => $messages]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'recipient_id' => ['nullable', 'integer', 'exists:users,id'],
            'recipient_email' => ['nullable', 'email', 'max:255'],
        ]);

        $user = $request->user() ?? auth('sanctum')->user();
        $isAdmin = $user && ((bool) ($user->is_admin ?? false));
        $senderRole = $isAdmin ? 'admin' : ($user ? 'user' : 'guest');
        $message = ChatMessage::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? $request->input('name', 'Guest'),
            'user_email' => $user?->email ?? $request->input('email'),
            'sender_role' => $senderRole,
            'recipient_id' => $isAdmin ? $request->input('recipient_id') : null,
            'recipient_email' => $isAdmin ? $request->input('recipient_email') : null,
            'message' => $request->input('message'),
        ]);

        try {
            broadcast(new ChatMessageSent($message))->toOthers();
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json(['data' => $this->transformMessage($message)], 201);
    }

    public function userIndex(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['data' => []]);
        }

        $messages = ChatMessage::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('recipient_id', $user->id)
                    ->orWhere('user_email', $user->email)
                    ->orWhere('recipient_email', $user->email);
            })
            ->latest()
            ->take(100)
            ->get()
            ->map(function (ChatMessage $message) {
                return $this->transformMessage($message);
            })
            ->values();

        return response()->json(['data' => $messages]);
    }

    protected function transformMessage(ChatMessage $message): array
    {
        return [
            'id' => $message->id,
            'message' => $message->message,
            'sender_role' => $message->sender_role ?? ($message->user_id ? 'user' : 'guest'),
            'user' => [
                'id' => $message->user_id,
                'name' => $message->user_name ?? $message->user?->name,
                'email' => $message->user_email ?? $message->user?->email,
            ],
            'recipient' => [
                'id' => $message->recipient_id,
                'email' => $message->recipient_email,
            ],
            'created_at' => $message->created_at?->toISOString(),
        ];
    }
}
