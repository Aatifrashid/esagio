@extends('layouts.patient', ['title' => 'Protected Treatment Plan'])

@section('content')
    <style>
        body { background: #FAFAF8; }
        .font-display { font-family: 'Fraunces', Georgia, serif; }
    </style>

    <div class="min-h-screen flex items-center justify-center px-4 py-16">
        <div class="w-full max-w-sm">

            <div class="text-center mb-8">
                <div class="inline-flex w-16 h-16 rounded-2xl bg-[#0A2540] items-center justify-center mb-6 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="font-display text-2xl font-semibold text-[#0A2540] mb-2">Protected Plan</h1>
                <p class="text-slate-500 text-sm leading-relaxed">
                    This treatment plan is password protected.<br>
                    Please enter the password provided by your clinic.
                </p>
            </div>

            <div class="bg-white border border-slate-100 rounded-2xl p-8 shadow-sm">
                <form method="POST" action="{{ route('patient.plan.password.submit', ['token' => $publicToken]) }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                autofocus
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition
                                       {{ $errors->has('password') ? 'border-red-300 focus:ring-red-300' : 'focus:ring-[#E8663D]' }}"
                                placeholder="Enter plan password"
                            />
                            @error('password')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-[#0A2540] hover:opacity-90 text-white font-semibold py-3.5 rounded-xl text-sm transition shadow-md">
                            View Treatment Plan
                        </button>
                    </div>
                </form>
            </div>

            <p class="text-center text-xs text-slate-400 mt-6">
                Having trouble? Contact your clinic directly for assistance.
            </p>
        </div>
    </div>
@endsection
