<x-layouts.marketing :title="'Contact'">

<section class="bg-[#0A2540] text-white py-20">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h1 class="font-['Fraunces'] text-5xl font-bold mb-4">Get in touch</h1>
        <p class="text-gray-300 text-lg">We reply within one business day.</p>
    </div>
</section>

<section class="py-20 bg-gray-50">
    <div class="max-w-xl mx-auto px-4">
        <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
            <form class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Your name</label>
                    <input type="text" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:border-[#0A2540] text-sm" placeholder="Dr. Jane Smith">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:border-[#0A2540] text-sm" placeholder="jane@yourclinic.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea rows="5" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:border-[#0A2540] text-sm" placeholder="Tell us what you need..."></textarea>
                </div>
                <button type="submit" class="w-full py-3 px-6 bg-[#0A2540] text-white font-medium rounded-xl hover:bg-[#0A2540]/90 transition">
                    Send message
                </button>
            </form>
        </div>
        <p class="text-center text-sm text-gray-500 mt-6">
            Or email us directly: <a href="mailto:hello@esagio.com" class="text-[#E8663D] hover:underline">hello@esagio.com</a>
        </p>
    </div>
</section>

</x-layouts.marketing>
