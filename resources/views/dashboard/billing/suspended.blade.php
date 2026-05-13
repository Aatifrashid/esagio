<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Suspended - Esagio</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-lg mx-auto px-4 text-center">
        <div class="bg-white rounded-2xl border border-red-100 p-10 shadow-sm">
            <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-3">Account suspended</h1>

            <p class="text-gray-600 mb-2">
                Your account has been suspended, usually because of a failed payment.
            </p>
            <p class="text-gray-600 mb-8">
                Update your payment details to reactivate it. Takes less than a minute.
            </p>

            <a href="{{ route('billing.portal') }}"
               class="inline-block py-3 px-6 rounded-lg bg-[#0A2540] text-white font-medium hover:bg-[#0a2540]/90 transition">
                Update payment details
            </a>

            <p class="text-sm text-gray-500 mt-6">
                Questions? Email us at <a href="mailto:support@esagio.com" class="text-[#E8663D] hover:underline">support@esagio.com</a>
            </p>
        </div>
    </div>
</body>
</html>
