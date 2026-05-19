<div wire:poll.5s="loadMessages" class="flex flex-col h-full">
    @if($conversationId)
        {{-- Chat Header --}}
        <div class="flex items-center gap-3 p-4 border-b border-gray-700 bg-gray-800/50">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-600 text-white font-semibold text-sm">
                @if($channel === 'whatsapp')
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                @elseif($channel === 'email')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                @endif
            </div>
            <div>
                <h3 class="text-sm font-semibold text-white">{{ $conversationName }}</h3>
                <span class="text-xs text-gray-400 capitalize">{{ $channel }}</span>
            </div>
        </div>

        {{-- Messages --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-3" id="message-container" x-data x-init="$nextTick(() => { $el.scrollTop = $el.scrollHeight })">
            @php $lastDate = null; @endphp
            @foreach($messages as $msg)
                @if($msg['date'] !== $lastDate)
                    <div class="flex justify-center my-3">
                        <span class="text-xs text-gray-500 bg-gray-800 px-3 py-1 rounded-full">{{ $msg['date'] }}</span>
                    </div>
                    @php $lastDate = $msg['date']; @endphp
                @endif

                <div class="flex {{ $msg['is_inbound'] ? 'justify-start' : 'justify-end' }}">
                    <div class="max-w-[75%] {{ $msg['is_inbound'] ? 'bg-gray-700' : 'bg-green-700' }} rounded-xl px-4 py-2 shadow">
                        @if($msg['type'] === 'image' && $msg['media_url'])
                            <img src="{{ $msg['media_url'] }}" alt="Image" class="max-w-full rounded-lg mb-1">
                        @endif

                        @if($msg['body'])
                            <p class="text-sm text-white whitespace-pre-wrap">{{ $msg['body'] }}</p>
                        @endif

                        <div class="flex items-center justify-end gap-1 mt-1">
                            <span class="text-[10px] text-gray-400">{{ $msg['created_at'] }}</span>
                            @if(! $msg['is_inbound'])
                                @if($msg['status'] === 'read')
                                    <svg class="w-3.5 h-3.5 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M18 7l-1.41-1.41-6.34 6.34 1.41 1.41L18 7zm4.24-1.41L11.66 16.17 7.48 12l-1.41 1.41L11.66 19l12-12-1.42-1.41zM.41 13.41L6 19l1.41-1.41L1.83 12 .41 13.41z"/></svg>
                                @elseif($msg['status'] === 'delivered')
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M18 7l-1.41-1.41-6.34 6.34 1.41 1.41L18 7zm4.24-1.41L11.66 16.17 7.48 12l-1.41 1.41L11.66 19l12-12-1.42-1.41zM.41 13.41L6 19l1.41-1.41L1.83 12 .41 13.41z"/></svg>
                                @elseif($msg['status'] === 'sent')
                                    <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                                @elseif($msg['status'] === 'failed')
                                    <svg class="w-3 h-3 text-red-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                                @else
                                    <svg class="w-3 h-3 text-gray-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                @endif
                            @endif
                        </div>

                        @if(! $msg['is_inbound'] && $msg['sender_name'])
                            <span class="text-[10px] text-gray-400">{{ $msg['sender_name'] }}</span>
                        @endif
                    </div>
                </div>
            @endforeach

            @if(empty($messages))
                <div class="flex flex-col items-center justify-center h-full text-gray-500">
                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    <p class="text-sm">No messages yet</p>
                    <p class="text-xs mt-1">Send the first message to start the conversation</p>
                </div>
            @endif
        </div>

        {{-- Message Input --}}
        <div class="p-3 border-t border-gray-700 bg-gray-800/50">
            @error('send')
                <div class="mb-2 text-xs text-red-400 bg-red-900/20 px-3 py-1.5 rounded">{{ $message }}</div>
            @enderror
            <form wire:submit="sendMessage" class="flex gap-2">
                <input
                    type="text"
                    wire:model="newMessage"
                    placeholder="Type a message..."
                    class="flex-1 bg-gray-700 border border-gray-600 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    autocomplete="off"
                >
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white rounded-xl px-4 py-2.5 transition-colors"
                    wire:loading.attr="disabled"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                </button>
            </form>
        </div>
    @else
        {{-- No conversation selected --}}
        <div class="flex flex-col items-center justify-center h-full text-gray-500">
            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            <p class="text-base font-medium">No conversation</p>
            <p class="text-sm mt-1">This patient doesn't have a WhatsApp conversation yet.</p>
            <p class="text-xs mt-2 text-gray-600">Messages will appear here when the patient messages your connected WhatsApp number.</p>
        </div>
    @endif
</div>
