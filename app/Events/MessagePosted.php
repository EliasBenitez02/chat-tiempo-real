<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // <-- importante
use Illuminate\Queue\SerializesModels;

class MessagePosted implements ShouldBroadcastNow
{
    use SerializesModels;

    public function __construct(public Message $message) {}

    public function broadcastOn(): Channel
    {
        return new Channel('chat');
    }

    public function broadcastAs(): string
    {
        return 'message.posted';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'user' => $this->message->user,
            'content' => $this->message->content,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
}