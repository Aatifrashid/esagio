<?php

namespace App\Services\VideoConsultation;

use Illuminate\Support\Facades\Http;

class DailyCoClient
{
    private string $apiKey;

    private string $baseUrl = 'https://api.daily.co/v1';

    public function __construct()
    {
        $this->apiKey = config('services.daily.api_key', '');
    }

    public function createRoom(array $options = []): array
    {
        $response = Http::withToken($this->apiKey)
            ->post($this->baseUrl.'/rooms', [
                'name' => $options['name'] ?? 'esagio-'.uniqid(),
                'privacy' => 'private',
                'properties' => [
                    'exp' => now()->addHours(24)->timestamp,
                    'max_participants' => $options['max_participants'] ?? 4,
                    'enable_chat' => true,
                    'enable_screenshare' => true,
                    'enable_recording' => $options['enable_recording'] ?? 'cloud',
                    'start_video_off' => false,
                    'start_audio_off' => false,
                ],
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('Failed to create Daily.co room: '.$response->body());
        }

        return $response->json();
    }

    public function generateToken(string $roomName, array $options = []): string
    {
        $response = Http::withToken($this->apiKey)
            ->post($this->baseUrl.'/meeting-tokens', [
                'properties' => [
                    'room_name' => $roomName,
                    'is_owner' => $options['is_owner'] ?? false,
                    'exp' => now()->addHours(24)->timestamp,
                    'user_name' => $options['user_name'] ?? 'Participant',
                    'enable_recording' => $options['enable_recording'] ?? false,
                ],
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('Failed to generate Daily.co token: '.$response->body());
        }

        return $response->json('token');
    }

    public function deleteRoom(string $roomName): bool
    {
        $response = Http::withToken($this->apiKey)
            ->delete($this->baseUrl.'/rooms/'.$roomName);

        return $response->successful();
    }

    public function getRoomInfo(string $roomName): array
    {
        $response = Http::withToken($this->apiKey)
            ->get($this->baseUrl.'/rooms/'.$roomName);

        if ($response->failed()) {
            throw new \RuntimeException('Failed to get room info: '.$response->body());
        }

        return $response->json();
    }

    public function getRecordings(string $roomName): array
    {
        $response = Http::withToken($this->apiKey)
            ->get($this->baseUrl.'/recordings', ['room_name' => $roomName]);

        return $response->successful() ? $response->json('data', []) : [];
    }
}
