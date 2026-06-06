<x-layouts.marketing :title="'Contact'">

<section class="bg-navy text-white py-20">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h1 class="font-display text-5xl font-extrabold tracking-tight mb-4">Get in touch</h1>
        <p class="text-gray-300 text-lg">We reply within one business day.</p>
    </div>
</section>

<section class="py-20 bg-gray-50">
    <div class="max-w-xl mx-auto px-4">
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-[5px] text-green-800 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-[5px] border border-gray-200 p-8 shadow-sm">
            <form method="POST" action="{{ route('contact.submit') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Your name</label>
                    <input type="text" name="name" required value="{{ old('name') }}" class="input @error('name') border-red-500 @enderror" placeholder="Dr. Jane Smith">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required value="{{ old('email') }}" class="input @error('email') border-red-500 @enderror" placeholder="jane@yourclinic.com">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea rows="5" name="message" required class="input @error('message') border-red-500 @enderror" placeholder="Tell us what you need...">{{ old('message') }}</textarea>
                    @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="btn btn-dark w-full">
                    Send message
                </button>
            </form>
        </div>
        <p class="text-center text-sm text-gray-500 mt-6">
            Or email us directly: <a href="mailto:hello@esagio.com" class="text-accent hover:underline">hello@esagio.com</a>
        </p>
    </div>
</section>

</x-layouts.marketing>
