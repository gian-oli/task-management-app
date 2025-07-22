<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel; // if you want presence, else omit
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TasksReordered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;
    public array $orderedTaskIds;

    public function __construct(int $userId, array $orderedTaskIds)
    {
        $this->userId = $userId;
        $this->orderedTaskIds = $orderedTaskIds;

        // Optional: Log constructor call
        Log::info("TasksReordered event created for user {$userId}", [
            'ordered_task_ids' => $orderedTaskIds,
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * For example, a private channel for the user:
     */
    public function broadcastOn()
    {
        // This means broadcast on channel "tasks.reorder.{userId}"
        return new PrivateChannel("tasks.reorder.{$this->userId}");
    }

    /**
     * Data to broadcast to the frontend.
     */
    public function broadcastWith()
    {
        Log::info("Broadcasting TasksReordered event for user {$this->userId}", [
            'ordered_task_ids' => $this->orderedTaskIds,
        ]);

        return [
            'ordered_task_ids' => $this->orderedTaskIds,
            'user_id' => $this->userId,
        ];
    }
}
