<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(public ChatMessage $message)
    {
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('admin.chat');
    }

    public function broadcastAs(): string
    {
        return 'chat.message';
    }

    public function broadcastWith(): array
    {
        $this->message->loadMissing('user');

        return [
            'message' => [
                'id' => $this->message->id,
                'message' => $this->message->message,
                'sender_role' => $this->message->sender_role ?? ($this->message->user_id ? 'user' : 'guest'),
                'user' => [
                    'id' => $this->message->user_id,
                    'name' => $this->message->user_name ?? $this->message->user?->name,
                    'email' => $this->message->user_email ?? $this->message->user?->email,
                ],
                'recipient' => [
                    'id' => $this->message->recipient_id,
                    'email' => $this->message->recipient_email,
                ],
                'created_at' => $this->message->created_at?->toISOString(),
            ],
        ];
    }
}
