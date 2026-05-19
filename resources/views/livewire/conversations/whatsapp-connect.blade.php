<div class="max-w-xl mx-auto" wire:poll.3s="refreshQr">
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-gray-700 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-white font-semibold">WhatsApp Connection</h3>
                <p class="text-xs text-gray-400">Connect your existing WhatsApp number</p>
            </div>
        </div>

        <div class="p-6">
            @if($connectionError)
                <div class="mb-4 p-3 bg-red-900/30 border border-red-700 rounded-lg text-sm text-red-400">{{ $connectionError }}</div>
            @endif

            @if(! $sessionId)
                {{-- Not connected --}}
                <div class="text-center py-6">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-700 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    </div>
                    <h4 class="text-white font-medium mb-2">No WhatsApp Connected</h4>
                    <p class="text-sm text-gray-400 mb-6">Connect your existing WhatsApp number to start sending and receiving messages directly from Esagio.</p>
                    <button wire:click="connect" wire:loading.attr="disabled" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
                        <span wire:loading.remove wire:target="connect">Connect WhatsApp</span>
                        <span wire:loading wire:target="connect">Connecting...</span>
                    </button>
                </div>

            @elseif($sessionStatus === 'connecting' || $sessionStatus === 'qr_pending')
                {{-- QR Code --}}
                <div class="text-center py-4">
                    <h4 class="text-white font-medium mb-2">Scan QR Code</h4>
                    <p class="text-sm text-gray-400 mb-4">Open WhatsApp on your phone, go to <strong>Linked Devices</strong>, and scan this QR code.</p>

                    @if($qrCode)
                        <div class="inline-block bg-white p-4 rounded-xl mb-4">
                            <img src="data:image/png;base64,{{ $qrCode }}" alt="WhatsApp QR Code" class="w-56 h-56">
                        </div>
                    @else
                        <div class="inline-flex items-center justify-center w-64 h-64 bg-gray-700 rounded-xl mb-4">
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto text-gray-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <p class="text-xs text-gray-500 mt-2">Generating QR code...</p>
                            </div>
                        </div>
                    @endif

                    <div class="text-xs text-gray-500">
                        <p>QR code refreshes automatically</p>
                    </div>

                    <button wire:click="disconnect" class="mt-4 text-sm text-red-400 hover:text-red-300 underline">Cancel</button>
                </div>

            @elseif($sessionStatus === 'connected')
                {{-- Connected --}}
                <div class="text-center py-4">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-600/20 flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h4 class="text-white font-medium mb-1">WhatsApp Connected</h4>
                    @if($phoneNumber)
                        <p class="text-sm text-green-400 mb-1">{{ $phoneNumber }}</p>
                    @endif
                    <p class="text-sm text-gray-400 mb-6">Your WhatsApp is connected and ready to send/receive messages.</p>

                    <button wire:click="disconnect" wire:confirm="Are you sure you want to disconnect WhatsApp?" class="text-sm text-red-400 hover:text-red-300 border border-red-700 px-4 py-2 rounded-lg transition-colors hover:bg-red-900/20">
                        Disconnect
                    </button>
                </div>

            @elseif($sessionStatus === 'disconnected')
                {{-- Disconnected --}}
                <div class="text-center py-6">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-yellow-600/20 flex items-center justify-center">
                        <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h4 class="text-white font-medium mb-2">WhatsApp Disconnected</h4>
                    <p class="text-sm text-gray-400 mb-6">Your WhatsApp session was disconnected. Reconnect to continue messaging.</p>
                    <button wire:click="connect" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
                        Reconnect
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- How it works --}}
    <div class="mt-6 bg-gray-800/50 rounded-xl border border-gray-700 p-6">
        <h4 class="text-white font-medium mb-3">How it works</h4>
        <div class="space-y-3 text-sm text-gray-400">
            <div class="flex gap-3">
                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-600/20 text-green-400 flex items-center justify-center text-xs font-bold">1</span>
                <p>Click "Connect WhatsApp" to generate a QR code</p>
            </div>
            <div class="flex gap-3">
                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-600/20 text-green-400 flex items-center justify-center text-xs font-bold">2</span>
                <p>Open WhatsApp on your phone and go to <strong class="text-white">Settings &gt; Linked Devices</strong></p>
            </div>
            <div class="flex gap-3">
                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-600/20 text-green-400 flex items-center justify-center text-xs font-bold">3</span>
                <p>Tap "Link a Device" and scan the QR code shown above</p>
            </div>
            <div class="flex gap-3">
                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-600/20 text-green-400 flex items-center justify-center text-xs font-bold">4</span>
                <p>Once connected, all incoming and outgoing WhatsApp messages will sync with Esagio</p>
            </div>
        </div>
    </div>
</div>
