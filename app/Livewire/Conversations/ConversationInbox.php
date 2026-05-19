<?php

namespace App\Livewire\Conversations;

use App\Models\Conversation;
use Livewire\Component;

class ConversationInbox extends Component
{
    public ?int $activeConversationId = null;

    public string $search = '';

    public string $filterChannel = 'all';

    public string $filterStatus = 'open';

    public function mount(): void
    {
        // Auto-select first conversation
        $first = $this->getConversationsQuery()->first();
        if ($first) {
            $this->activeConversationId = $first->id;
        }
    }

    public function selectConversation(int $id): void
    {
        $this->activeConversationId = $id;
    }

    public function updatedSearch(): void
    {
        $this->activeConversationId = null;
    }

    private function getConversationsQuery()
    {
        $query = Conversation::with(['patient', 'latestMessage'])
            ->where('status', $this->filterStatus);

        if ($this->filterChannel !== 'all') {
            $query->where('channel', $this->filterChannel);
        }

        if ($this->search) {
            $term = '%' . $this->search . '%';
            $query->where(function ($q) use ($term) {
                $q->where('channel_identifier', 'like', $term)
                    ->orWhereHas('patient', function ($pq) use ($term) {
                        $pq->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$term]);
                    });
            });
        }

        return $query->orderByDesc('last_message_at');
    }

    public function render()
    {
        return view('livewire.conversations.conversation-inbox', [
            'conversations' => $this->getConversationsQuery()->limit(50)->get(),
        ]);
    }
}
