<?php

namespace App\Livewire\Conversations;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\WhatsappSession;
use App\Services\Whatsapp\WhatsappBridgeService;
use Livewire\Component;

class ConversationThread extends Component
{
    public ?int $conversationId = null;

    public ?int $patientId = null;

    public string $newMessage = '';

    public array $messages = [];

    public ?string $conversationName = null;

    public ?string $channel = null;

    public function mount(?int $conversationId = null, ?int $patientId = null): void
    {
        $this->conversationId = $conversationId;
        $this->patientId = $patientId;

        $this->loadMessages();
    }

    public function loadMessages(): void
    {
        if (! $this->conversationId && $this->patientId) {
            $conversation = Conversation::where('patient_id', $this->patientId)
                ->where('channel', 'whatsapp')
                ->first();

            if ($conversation) {
                $this->conversationId = $conversation->id;
            }
        }

        if (! $this->conversationId) {
            $this->messages = [];

            return;
        }

        $conversation = Conversation::with('patient')->find($this->conversationId);

        if (! $conversation) {
            $this->messages = [];

            return;
        }

        $this->conversationName = $conversation->display_name;
        $this->channel = $conversation->channel;

        $this->messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->limit(100)
            ->get()
            ->map(fn (Message $msg) => [
                'id' => $msg->id,
                'direction' => $msg->direction,
                'type' => $msg->type,
                'body' => $msg->body,
                'media_url' => $msg->media_url,
                'status' => $msg->status,
                'sender_name' => $msg->sender?->name,
                'created_at' => $msg->created_at->format('H:i'),
                'date' => $msg->created_at->format('d M Y'),
                'is_inbound' => $msg->isInbound(),
            ])
            ->toArray();
    }

    public function sendMessage(): void
    {
        if (empty(trim($this->newMessage))) {
            return;
        }

        if (! $this->conversationId) {
            return;
        }

        $conversation = Conversation::find($this->conversationId);

        if (! $conversation) {
            return;
        }

        // Create outbound message record
        $message = $conversation->messages()->create([
            'direction' => 'outbound',
            'type' => 'text',
            'body' => $this->newMessage,
            'status' => 'pending',
            'sent_by' => auth()->id(),
        ]);

        $conversation->update(['last_message_at' => now()]);

        // Send via WhatsApp bridge
        if ($conversation->channel === 'whatsapp' && $conversation->whatsapp_session_id) {
            $session = WhatsappSession::find($conversation->whatsapp_session_id);

            if ($session && $session->isConnected()) {
                try {
                    $bridge = app(WhatsappBridgeService::class);
                    $result = $bridge->sendMessage($session, $conversation->channel_identifier, $this->newMessage);

                    $message->update([
                        'status' => 'sent',
                        'external_id' => $result['message_id'] ?? null,
                    ]);
                } catch (\Exception $e) {
                    $message->update(['status' => 'failed']);
                    $this->addError('send', 'Failed to send message. Please try again.');
                }
            } else {
                $message->update(['status' => 'failed']);
                $this->addError('send', 'WhatsApp is not connected. Please reconnect in settings.');
            }
        }

        $this->newMessage = '';
        $this->loadMessages();

        $this->dispatch('message-sent');
    }

    public function selectConversation(int $conversationId): void
    {
        $this->conversationId = $conversationId;
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.conversations.conversation-thread');
    }
}
