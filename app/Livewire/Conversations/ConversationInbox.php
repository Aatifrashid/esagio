<?php

namespace App\Livewire\Conversations;

use App\Models\Conversation;
use App\Models\Patient;
use Livewire\Component;

class ConversationInbox extends Component
{
    public ?int $activeConversationId = null;

    public string $search = '';

    public string $filterChannel = 'all';

    public string $filterStatus = 'open';

    public string $filterTab = 'all';

    public array $starredConversations = [];

    public function mount(): void
    {
        $this->starredConversations = session('starred_conversations', []);

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

    public function toggleStar(int $conversationId): void
    {
        if (in_array($conversationId, $this->starredConversations)) {
            $this->starredConversations = array_values(array_diff($this->starredConversations, [$conversationId]));
        } else {
            $this->starredConversations[] = $conversationId;
        }

        session(['starred_conversations' => $this->starredConversations]);
    }

    public function getActivePatientProperty(): ?Patient
    {
        if (! $this->activeConversationId) {
            return null;
        }

        $conversation = Conversation::find($this->activeConversationId);

        if (! $conversation || ! $conversation->patient_id) {
            return null;
        }

        return Patient::with(['pipelineStage', 'assignedUser'])
            ->find($conversation->patient_id);
    }

    private function getConversationsQuery()
    {
        $query = Conversation::with(['patient', 'latestMessage'])
            ->where('status', $this->filterStatus);

        // Apply tab filter
        switch ($this->filterTab) {
            case 'unread':
                $query->whereHas('latestMessage', function ($q) {
                    $q->where('direction', 'inbound')
                        ->where('status', '!=', 'read');
                });
                break;

            case 'recents':
                $query->where('last_message_at', '>=', now()->subDays(7));
                break;

            case 'starred':
                if (! empty($this->starredConversations)) {
                    $query->whereIn('id', $this->starredConversations);
                } else {
                    $query->whereRaw('1 = 0');
                }
                break;

            case 'all':
            default:
                break;
        }

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

    private function getUnreadCount(Conversation $conversation): int
    {
        return $conversation->messages()
            ->where('direction', 'inbound')
            ->where('status', '!=', 'read')
            ->count();
    }

    public function render()
    {
        $conversations = $this->getConversationsQuery()->limit(50)->get();

        // Attach unread counts
        $conversations->each(function ($conv) {
            $conv->unread_count = $this->getUnreadCount($conv);
        });

        return view('livewire.conversations.conversation-inbox', [
            'conversations' => $conversations,
            'activePatient' => $this->activePatient,
        ]);
    }
}
