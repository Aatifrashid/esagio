<div class="flex h-[calc(100vh-64px)] bg-gray-900">
    {{-- Sidebar: Conversation List --}}
    <div class="w-80 flex-shrink-0 border-r border-gray-700 flex flex-col bg-gray-800">
        {{-- Search --}}
        <div class="p-3 border-b border-gray-700">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search conversations..."
                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500"
            >
        </div>

        {{-- Channel Filter Tabs --}}
        <div class="flex border-b border-gray-700 text-xs">
            @foreach(['all' => 'All', 'whatsapp' => 'WhatsApp', 'email' => 'Email', 'sms' => 'SMS'] as $key => $label)
                <button
                    wire:click="$set('filterChannel', '{{ $key }}')"
                    class="flex-1 py-2.5 text-center transition-colors {{ $filterChannel === $key ? 'text-green-400 border-b-2 border-green-400 font-medium' : 'text-gray-400 hover:text-white' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Conversation List --}}
        <div class="flex-1 overflow-y-auto" wire:poll.5s>
            @forelse($conversations as $conv)
                <button
                    wire:click="selectConversation({{ $conv->id }})"
                    class="w-full flex items-start gap-3 p-3 text-left hover:bg-gray-700/50 transition-colors {{ $activeConversationId === $conv->id ? 'bg-gray-700' : '' }}"
                >
                    {{-- Avatar --}}
                    <div class="flex items-center justify-center w-10 h-10 rounded-full flex-shrink-0
                        {{ $conv->channel === 'whatsapp' ? 'bg-green-600' : ($conv->channel === 'email' ? 'bg-blue-600' : 'bg-purple-600') }}
                        text-white font-semibold text-sm"
                    >
                        @if($conv->patient)
                            {{ strtoupper(substr($conv->patient->first_name, 0, 1)) }}{{ strtoupper(substr($conv->patient->last_name, 0, 1)) }}
                        @else
                            ?
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline">
                            <span class="text-sm font-medium text-white truncate">{{ $conv->display_name }}</span>
                            @if($conv->last_message_at)
                                <span class="text-[10px] text-gray-500 flex-shrink-0 ml-2">{{ $conv->last_message_at->diffForHumans(null, true) }}</span>
                            @endif
                        </div>
                        @if($conv->latestMessage)
                            <p class="text-xs text-gray-400 truncate mt-0.5">
                                @if($conv->latestMessage->direction === 'outbound')
                                    <span class="text-gray-500">You: </span>
                                @endif
                                {{ $conv->latestMessage->body ?? '[Media]' }}
                            </p>
                        @endif
                        <div class="flex items-center gap-1 mt-1">
                            @if($conv->channel === 'whatsapp')
                                <span class="text-[10px] text-green-400 bg-green-900/30 px-1.5 py-0.5 rounded">WhatsApp</span>
                            @elseif($conv->channel === 'email')
                                <span class="text-[10px] text-blue-400 bg-blue-900/30 px-1.5 py-0.5 rounded">Email</span>
                            @else
                                <span class="text-[10px] text-purple-400 bg-purple-900/30 px-1.5 py-0.5 rounded">SMS</span>
                            @endif
                        </div>
                    </div>
                </button>
            @empty
                <div class="p-6 text-center text-gray-500">
                    <p class="text-sm">No conversations found</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Chat Area --}}
    <div class="flex-1 flex flex-col">
        @if($activeConversationId)
            @livewire('conversations.conversation-thread', ['conversationId' => $activeConversationId], key('thread-' . $activeConversationId))
        @else
            <div class="flex flex-col items-center justify-center h-full text-gray-500">
                <svg class="w-20 h-20 mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-400">Conversations</h3>
                <p class="text-sm mt-1">Select a conversation to start messaging</p>
            </div>
        @endif
    </div>
</div>
