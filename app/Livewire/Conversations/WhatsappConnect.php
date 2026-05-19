<?php

namespace App\Livewire\Conversations;

use App\Models\WhatsappSession;
use App\Services\Whatsapp\WhatsappBridgeService;
use Livewire\Component;

class WhatsappConnect extends Component
{
    public ?string $sessionStatus = null;

    public ?string $qrCode = null;

    public ?string $phoneNumber = null;

    public ?int $sessionId = null;

    public function mount(): void
    {
        $this->loadSession();
    }

    public function loadSession(): void
    {
        $session = WhatsappSession::where('clinic_id', auth()->user()->clinic_id)->first();

        if ($session) {
            $this->sessionId = $session->id;
            $this->sessionStatus = $session->status;
            $this->qrCode = $session->qr_code;
            $this->phoneNumber = $session->phone_number;
        }
    }

    public function connect(): void
    {
        try {
            $bridge = app(WhatsappBridgeService::class);
            $clinic = auth()->user()->clinic;
            $session = $bridge->createSession($clinic);

            $this->sessionId = $session->id;
            $this->sessionStatus = 'connecting';
        } catch (\Exception $e) {
            $this->addError('connection', 'Failed to connect to WhatsApp bridge: ' . $e->getMessage());
        }
    }

    public function refreshQr(): void
    {
        if (! $this->sessionId) {
            return;
        }

        $session = WhatsappSession::find($this->sessionId);

        if ($session) {
            $this->sessionStatus = $session->status;
            $this->qrCode = $session->qr_code;
            $this->phoneNumber = $session->phone_number;
        }
    }

    public function disconnect(): void
    {
        $session = WhatsappSession::find($this->sessionId);

        if ($session) {
            try {
                $bridge = app(WhatsappBridgeService::class);
                $bridge->disconnectSession($session);
            } catch (\Exception $e) {
                // Ignore if bridge is unreachable
            }

            $session->delete();
        }

        $this->reset(['sessionId', 'sessionStatus', 'qrCode', 'phoneNumber']);
    }

    public function render()
    {
        return view('livewire.conversations.whatsapp-connect');
    }
}
