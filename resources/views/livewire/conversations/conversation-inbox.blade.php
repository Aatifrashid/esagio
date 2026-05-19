<div class="flex h-[calc(100vh-64px)] bg-gray-900">
    {{-- Left: Conversation List --}}
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

        {{-- Filter Tabs: Unread | All | Recents | Starred --}}
        <div class="flex border-b border-gray-700">
            @foreach([
                'unread' => ['label' => 'Unread', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-2.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>'],
                'all' => ['label' => 'All', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>'],
                'recents' => ['label' => 'Recents', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                'starred' => ['label' => 'Starred', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>'],
            ] as $key => $tab)
                <button
                    wire:click="$set('filterTab', '{{ $key }}')"
                    class="flex-1 flex flex-col items-center gap-1 py-2.5 text-center transition-colors {{ $filterTab === $key ? 'text-green-400 border-b-2 border-green-400' : 'text-gray-400 hover:text-white' }}"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $tab['icon'] !!}</svg>
                    <span class="text-[10px] font-medium">{{ $tab['label'] }}</span>
                </button>
            @endforeach
        </div>

        {{-- Channel Sub-filter --}}
        <div class="flex border-b border-gray-700 text-xs">
            @foreach(['all' => 'All', 'whatsapp' => 'WhatsApp', 'email' => 'Email', 'sms' => 'SMS'] as $key => $label)
                <button
                    wire:click="$set('filterChannel', '{{ $key }}')"
                    class="flex-1 py-2 text-center transition-colors {{ $filterChannel === $key ? 'text-green-400 border-b-2 border-green-400 font-medium' : 'text-gray-400 hover:text-white' }}"
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
                    class="group w-full flex items-start gap-3 p-3 text-left hover:bg-gray-700/50 transition-colors relative {{ $activeConversationId === $conv->id ? 'bg-gray-700' : '' }}"
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
                            <div class="flex items-center gap-1.5 flex-shrink-0 ml-2">
                                @if($conv->last_message_at)
                                    <span class="text-[10px] text-gray-500">{{ $conv->last_message_at->diffForHumans(null, true) }}</span>
                                @endif
                            </div>
                        </div>
                        @if($conv->latestMessage)
                            <p class="text-xs text-gray-400 truncate mt-0.5">
                                @if($conv->latestMessage->direction === 'outbound')
                                    <span class="text-gray-500">You: </span>
                                @endif
                                {{ $conv->latestMessage->body ?? '[Media]' }}
                            </p>
                        @endif
                        <div class="flex items-center gap-1.5 mt-1">
                            @if($conv->channel === 'whatsapp')
                                <span class="text-[10px] text-green-400 bg-green-900/30 px-1.5 py-0.5 rounded">WhatsApp</span>
                            @elseif($conv->channel === 'email')
                                <span class="text-[10px] text-blue-400 bg-blue-900/30 px-1.5 py-0.5 rounded">Email</span>
                            @else
                                <span class="text-[10px] text-purple-400 bg-purple-900/30 px-1.5 py-0.5 rounded">SMS</span>
                            @endif

                            @if($conv->unread_count > 0)
                                <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-blue-500 rounded-full">{{ $conv->unread_count }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Star toggle --}}
                    <button
                        wire:click.stop="toggleStar({{ $conv->id }})"
                        class="absolute top-3 right-3 {{ in_array($conv->id, $starredConversations) ? 'text-yellow-400' : 'text-gray-600 opacity-0 group-hover:opacity-100' }} hover:text-yellow-400 transition-all"
                        title="{{ in_array($conv->id, $starredConversations) ? 'Unstar' : 'Star' }}"
                    >
                        @if(in_array($conv->id, $starredConversations))
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        @endif
                    </button>
                </button>
            @empty
                <div class="p-6 text-center text-gray-500">
                    <p class="text-sm">No conversations found</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Center: Chat Thread --}}
    <div class="flex-1 flex flex-col min-w-0">
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

    {{-- Right: Contact Details Panel --}}
    @if($activeConversationId && $activePatient)
    <div class="w-80 flex-shrink-0 border-l border-gray-700 bg-gray-800 overflow-y-auto">
        {{-- Patient Header --}}
        <div class="p-4 border-b border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-gray-600 flex items-center justify-center text-white font-semibold text-lg">
                    {{ strtoupper(substr($activePatient->first_name, 0, 1)) }}{{ strtoupper(substr($activePatient->last_name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-white font-semibold">{{ $activePatient->first_name }} {{ $activePatient->last_name }}</h3>
                    <a href="{{ route('filament.clinic.resources.patients.edit', $activePatient->id) }}" class="text-xs text-blue-400 hover:underline">View profile</a>
                </div>
            </div>
        </div>

        {{-- Owner / Assigned --}}
        <div class="p-4 border-b border-gray-700">
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Owner</span>
                <span class="text-white">{{ $activePatient->assignedUser?->name ?? 'Unassigned' }}</span>
            </div>
        </div>

        {{-- Tags --}}
        @if(!empty($activePatient->tags))
        <div class="p-4 border-b border-gray-700">
            <h4 class="text-xs font-medium text-gray-400 uppercase mb-2">Tags</h4>
            <div class="flex flex-wrap gap-1">
                @foreach($activePatient->tags as $tag)
                    <span class="text-xs bg-gray-700 text-gray-300 px-2 py-0.5 rounded">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Contact Info --}}
        <div class="p-4 border-b border-gray-700">
            <h4 class="text-xs font-medium text-gray-400 uppercase mb-3">Contact</h4>
            <div class="space-y-3">
                @if($activePatient->email)
                <div>
                    <label class="text-xs text-gray-500">Email</label>
                    <p class="text-sm text-white">{{ $activePatient->email }}</p>
                </div>
                @endif
                @if($activePatient->phone)
                <div>
                    <label class="text-xs text-gray-500">Phone</label>
                    <p class="text-sm text-white">{{ $activePatient->phone }}</p>
                </div>
                @endif
                @if($activePatient->whatsapp_number)
                <div>
                    <label class="text-xs text-gray-500">WhatsApp</label>
                    <p class="text-sm text-white">{{ $activePatient->whatsapp_number }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Pipeline Stage --}}
        @if($activePatient->pipelineStage)
        <div class="p-4 border-b border-gray-700">
            <h4 class="text-xs font-medium text-gray-400 uppercase mb-2">Pipeline</h4>
            <span class="text-sm px-2 py-1 rounded" style="background-color: {{ $activePatient->pipelineStage->colour ?? '#6B7280' }}20; color: {{ $activePatient->pipelineStage->colour ?? '#9CA3AF' }}">
                {{ $activePatient->pipelineStage->name }}
            </span>
        </div>
        @endif

        {{-- Deal Value --}}
        @if($activePatient->deal_value)
        <div class="p-4 border-b border-gray-700">
            <div class="flex justify-between">
                <span class="text-xs text-gray-400 uppercase">Deal Value</span>
                <span class="text-sm text-green-400 font-medium">£{{ number_format($activePatient->deal_value, 2) }}</span>
            </div>
        </div>
        @endif

        {{-- Source --}}
        @if($activePatient->source)
        <div class="p-4 border-b border-gray-700">
            <div class="flex justify-between">
                <span class="text-xs text-gray-400 uppercase">Source</span>
                <span class="text-sm text-white capitalize">{{ $activePatient->source }}</span>
            </div>
        </div>
        @endif

        {{-- Status --}}
        <div class="p-4 border-b border-gray-700">
            <div class="flex justify-between">
                <span class="text-xs text-gray-400 uppercase">Status</span>
                <span class="text-sm text-white capitalize">{{ $activePatient->status ?? 'Unknown' }}</span>
            </div>
        </div>

        {{-- Notes --}}
        <div class="p-4">
            <h4 class="text-xs font-medium text-gray-400 uppercase mb-2">Notes</h4>
            <p class="text-sm text-gray-300">{{ $activePatient->notes ?: 'No notes' }}</p>
        </div>
    </div>
    @endif
</div>
