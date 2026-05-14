<div x-data="{
    dragging: null,
    showToast: false,
    toastMsg: '',
    ctxMenu: { show: false, x: 0, y: 0, tooth: null },
    flash(msg) { this.toastMsg = msg; this.showToast = true; setTimeout(() => this.showToast = false, 2000); },
    openCtx(e, tooth) {
        e.preventDefault();
        const rect = this.$el.getBoundingClientRect();
        this.ctxMenu = { show: true, x: e.clientX - rect.left, y: e.clientY - rect.top, tooth: tooth };
    },
    applyCtx(code) {
        if (this.ctxMenu.tooth) {
            $wire.toggleTooth(this.ctxMenu.tooth);
            $wire.applyConditionToTeeth(code);
            this.flash('Applied to tooth ' + this.ctxMenu.tooth);
        }
        this.ctxMenu.show = false;
    }
}" x-on:click.away="ctxMenu.show = false" x-on:keydown.escape.window="ctxMenu.show = false" class="max-w-6xl mx-auto relative">

    {{-- Toast --}}
    <div x-show="showToast" x-transition.opacity class="fixed top-4 right-4 z-50 bg-gray-900 text-white text-sm px-4 py-2.5 rounded-lg shadow-lg" x-cloak x-text="toastMsg"></div>

    <div class="mb-6">
        <h2 class="font-serif text-2xl font-semibold text-clinical">Diagnosis</h2>
        <p class="text-gray-500 text-sm mt-1">Select teeth, then drag conditions onto them — or click a condition to paint it across selected teeth.</p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ============================================================
             LEFT: Interactive Tooth Chart (2 cols)
        ============================================================ --}}
        <div class="xl:col-span-2 space-y-4">

            {{-- Quick select bar --}}
            <div class="flex items-center gap-2 text-xs">
                <span class="text-gray-400 font-medium">Quick select:</span>
                <button wire:click="selectAllUpper" class="px-2.5 py-1 rounded-md bg-gray-100 hover:bg-clinical/10 hover:text-clinical text-gray-600 transition font-medium">Upper jaw</button>
                <button wire:click="selectAllLower" class="px-2.5 py-1 rounded-md bg-gray-100 hover:bg-clinical/10 hover:text-clinical text-gray-600 transition font-medium">Lower jaw</button>
                <button wire:click="selectAll" class="px-2.5 py-1 rounded-md bg-gray-100 hover:bg-clinical/10 hover:text-clinical text-gray-600 transition font-medium">All teeth</button>
                @if(count($selectedTeeth) > 0)
                    <button wire:click="clearSelectedTeeth" class="px-2.5 py-1 rounded-md bg-red-50 text-red-500 hover:bg-red-100 transition font-medium">Clear ({{ count($selectedTeeth) }})</button>
                @endif
            </div>

            {{-- Tooth Chart Card --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm"
                 x-on:dragover.prevent
                 x-on:drop.prevent="
                    if (dragging) {
                        $wire.applyConditionToTeeth(dragging);
                        flash('Applied to ' + {{ count($selectedTeeth) }} + ' teeth');
                        dragging = null;
                    }
                 ">

                @php
                    $toothHotspots = [
                        '18' => ['left' => '9.83%', 'top' => '7.36%', 'width' => '5.20%', 'height' => '31.46%', 'clip' => 'polygon(5.4% 81.7%, 10.8% 73.2%, 16.2% 61.7%, 21.6% 57.9%, 27.0% 55.3%, 32.4% 54.9%, 37.8% 63.4%, 43.2% 59.1%, 48.6% 56.2%, 54.1% 55.7%, 59.5% 58.3%, 64.9% 57.4%, 70.3% 54.9%, 75.7% 55.7%, 81.1% 58.7%, 86.5% 65.5%, 91.9% 81.7%, 91.9% 91.1%, 86.5% 93.6%, 81.1% 94.5%, 75.7% 94.9%, 70.3% 94.9%, 64.9% 94.9%, 59.5% 94.5%, 54.1% 93.6%, 48.6% 93.2%, 43.2% 93.6%, 37.8% 94.0%, 32.4% 94.5%, 27.0% 94.9%, 21.6% 94.9%, 16.2% 94.0%, 10.8% 93.2%, 5.4% 91.1%)'],
                        '17' => ['left' => '16.43%', 'top' => '7.36%', 'width' => '5.48%', 'height' => '31.46%', 'clip' => 'polygon(5.1% 83.4%, 10.3% 77.0%, 15.4% 64.3%, 20.5% 59.1%, 25.6% 55.3%, 30.8% 53.2%, 35.9% 51.9%, 41.0% 51.9%, 46.2% 52.8%, 51.3% 53.2%, 56.4% 59.1%, 61.5% 55.7%, 66.7% 52.3%, 71.8% 52.8%, 76.9% 54.5%, 82.1% 59.6%, 87.2% 77.4%, 92.3% 83.8%, 92.3% 88.5%, 87.2% 92.8%, 82.1% 94.0%, 76.9% 94.9%, 71.8% 95.3%, 66.7% 94.9%, 61.5% 94.9%, 56.4% 94.0%, 51.3% 93.6%, 46.2% 93.6%, 41.0% 94.0%, 35.9% 94.5%, 30.8% 94.9%, 25.6% 94.9%, 20.5% 94.5%, 15.4% 94.0%, 10.3% 92.8%, 5.1% 87.7%)'],
                        '16' => ['left' => '23.31%', 'top' => '7.36%', 'width' => '5.48%', 'height' => '31.46%', 'clip' => 'polygon(5.1% 82.1%, 10.3% 60.0%, 15.4% 53.2%, 20.5% 50.2%, 25.6% 49.8%, 30.8% 52.8%, 35.9% 51.1%, 41.0% 50.2%, 46.2% 52.3%, 51.3% 58.7%, 56.4% 63.4%, 61.5% 59.6%, 66.7% 55.3%, 71.8% 51.1%, 76.9% 50.2%, 82.1% 51.5%, 87.2% 59.6%, 92.3% 81.7%, 92.3% 89.4%, 87.2% 93.2%, 82.1% 94.5%, 76.9% 94.9%, 71.8% 94.9%, 66.7% 94.9%, 61.5% 94.5%, 56.4% 94.0%, 51.3% 93.6%, 46.2% 93.6%, 41.0% 94.0%, 35.9% 94.9%, 30.8% 94.9%, 25.6% 95.3%, 20.5% 94.9%, 15.4% 94.5%, 10.3% 92.8%, 5.1% 89.8%)'],
                        '15' => ['left' => '30.06%', 'top' => '7.36%', 'width' => '3.65%', 'height' => '31.46%', 'clip' => 'polygon(7.7% 83.0%, 11.5% 79.6%, 15.4% 74.5%, 19.2% 68.9%, 23.1% 64.3%, 26.9% 60.0%, 30.8% 54.0%, 34.6% 49.4%, 38.5% 46.0%, 42.3% 43.4%, 46.2% 42.6%, 50.0% 42.6%, 53.8% 43.0%, 57.7% 43.8%, 61.5% 45.5%, 65.4% 48.5%, 69.2% 59.1%, 73.1% 66.0%, 76.9% 73.2%, 80.8% 78.3%, 84.6% 82.6%, 88.5% 86.4%, 88.5% 90.6%, 84.6% 92.8%, 80.8% 93.6%, 76.9% 94.0%, 73.1% 94.0%, 69.2% 94.5%, 65.4% 94.5%, 61.5% 94.5%, 57.7% 94.9%, 53.8% 95.3%, 50.0% 95.7%, 46.2% 95.7%, 42.3% 95.7%, 38.5% 95.3%, 34.6% 94.9%, 30.8% 94.5%, 26.9% 94.0%, 23.1% 93.6%, 19.2% 93.2%, 15.4% 92.8%, 11.5% 91.9%, 7.7% 90.2%)'],
                        '14' => ['left' => '34.69%', 'top' => '7.36%', 'width' => '3.65%', 'height' => '31.46%', 'clip' => 'polygon(7.7% 81.7%, 11.5% 77.9%, 15.4% 73.2%, 19.2% 66.0%, 23.1% 59.6%, 26.9% 48.9%, 30.8% 40.4%, 34.6% 38.7%, 38.5% 38.3%, 42.3% 38.3%, 46.2% 38.7%, 50.0% 40.4%, 53.8% 42.6%, 57.7% 44.7%, 61.5% 48.1%, 65.4% 52.8%, 69.2% 57.4%, 73.1% 63.0%, 76.9% 69.8%, 80.8% 75.3%, 84.6% 80.4%, 88.5% 85.1%, 88.5% 89.8%, 84.6% 91.5%, 80.8% 92.8%, 76.9% 93.2%, 73.1% 93.6%, 69.2% 94.0%, 65.4% 94.5%, 61.5% 94.9%, 57.7% 95.3%, 53.8% 95.7%, 50.0% 95.7%, 46.2% 95.7%, 42.3% 95.7%, 38.5% 95.3%, 34.6% 94.9%, 30.8% 94.5%, 26.9% 94.0%, 23.1% 93.6%, 19.2% 93.2%, 15.4% 92.8%, 11.5% 91.9%, 7.7% 91.1%)'],
                        '13' => ['left' => '39.04%', 'top' => '7.36%', 'width' => '3.65%', 'height' => '31.46%', 'clip' => 'polygon(7.7% 84.3%, 11.5% 78.7%, 15.4% 74.5%, 19.2% 68.5%, 23.1% 60.9%, 26.9% 51.1%, 30.8% 38.3%, 34.6% 32.3%, 38.5% 31.1%, 42.3% 31.1%, 46.2% 31.5%, 50.0% 32.3%, 53.8% 34.0%, 57.7% 37.0%, 61.5% 40.4%, 65.4% 44.3%, 69.2% 48.9%, 73.1% 54.9%, 76.9% 62.1%, 80.8% 70.6%, 84.6% 77.0%, 88.5% 83.0%, 88.5% 89.8%, 84.6% 91.5%, 80.8% 92.3%, 76.9% 92.8%, 73.1% 93.2%, 69.2% 94.0%, 65.4% 94.5%, 61.5% 94.9%, 57.7% 95.3%, 53.8% 95.7%, 50.0% 95.7%, 46.2% 95.7%, 42.3% 95.3%, 38.5% 94.9%, 34.6% 94.5%, 30.8% 94.0%, 26.9% 93.6%, 23.1% 92.8%, 19.2% 92.3%, 15.4% 91.5%, 11.5% 90.6%, 7.7% 88.9%)'],
                        '12' => ['left' => '43.54%', 'top' => '7.36%', 'width' => '3.09%', 'height' => '31.46%', 'clip' => 'polygon(9.1% 76.6%, 13.6% 69.8%, 18.2% 63.0%, 22.7% 50.6%, 27.3% 42.6%, 31.8% 39.6%, 36.4% 37.4%, 40.9% 37.0%, 45.5% 37.0%, 50.0% 37.4%, 54.5% 40.4%, 59.1% 44.3%, 63.6% 48.1%, 68.2% 53.2%, 72.7% 58.3%, 77.3% 64.7%, 81.8% 72.8%, 86.4% 79.1%, 86.4% 94.5%, 81.8% 94.9%, 77.3% 94.9%, 72.7% 94.9%, 68.2% 94.9%, 63.6% 94.9%, 59.1% 94.9%, 54.5% 94.9%, 50.0% 94.9%, 45.5% 94.9%, 40.9% 94.9%, 36.4% 94.9%, 31.8% 94.9%, 27.3% 94.9%, 22.7% 94.9%, 18.2% 94.9%, 13.6% 94.5%, 9.1% 94.5%)'],
                        '11' => ['left' => '47.33%', 'top' => '7.36%', 'width' => '3.79%', 'height' => '31.46%', 'clip' => 'polygon(7.4% 81.7%, 11.1% 75.7%, 14.8% 70.6%, 18.5% 64.3%, 22.2% 56.6%, 25.9% 45.5%, 29.6% 37.4%, 33.3% 33.6%, 37.0% 32.8%, 40.7% 32.3%, 44.4% 32.8%, 48.1% 33.6%, 51.9% 34.9%, 55.6% 36.6%, 59.3% 39.6%, 63.0% 43.4%, 66.7% 48.5%, 70.4% 54.0%, 74.1% 60.0%, 77.8% 66.8%, 81.5% 72.3%, 85.2% 76.6%, 88.9% 82.6%, 88.9% 92.3%, 85.2% 94.9%, 81.5% 95.3%, 77.8% 95.7%, 74.1% 95.7%, 70.4% 95.7%, 66.7% 95.7%, 63.0% 95.7%, 59.3% 95.7%, 55.6% 95.3%, 51.9% 95.3%, 48.1% 95.3%, 44.4% 95.3%, 40.7% 95.3%, 37.0% 95.3%, 33.3% 94.9%, 29.6% 94.9%, 25.9% 94.9%, 22.2% 94.9%, 18.5% 94.9%, 14.8% 94.5%, 11.1% 94.5%, 7.4% 93.6%)'],
                        '21' => ['left' => '51.69%', 'top' => '7.36%', 'width' => '3.79%', 'height' => '31.46%', 'clip' => 'polygon(7.4% 80.0%, 11.1% 74.9%, 14.8% 70.2%, 18.5% 65.1%, 22.2% 58.7%, 25.9% 51.9%, 29.6% 46.4%, 33.3% 41.7%, 37.0% 38.3%, 40.7% 36.2%, 44.4% 34.0%, 48.1% 32.8%, 51.9% 32.3%, 55.6% 32.3%, 59.3% 32.8%, 63.0% 34.9%, 66.7% 42.6%, 70.4% 50.6%, 74.1% 57.4%, 77.8% 65.1%, 81.5% 70.6%, 85.2% 75.7%, 88.9% 83.0%, 88.9% 90.6%, 85.2% 94.0%, 81.5% 94.5%, 77.8% 94.9%, 74.1% 94.9%, 70.4% 94.9%, 66.7% 94.9%, 63.0% 95.3%, 59.3% 95.3%, 55.6% 95.3%, 51.9% 95.3%, 48.1% 95.3%, 44.4% 95.3%, 40.7% 95.3%, 37.0% 95.3%, 33.3% 95.3%, 29.6% 95.3%, 25.9% 95.3%, 22.2% 95.3%, 18.5% 95.3%, 14.8% 95.3%, 11.1% 94.9%, 7.4% 94.5%)'],
                        '22' => ['left' => '56.04%', 'top' => '7.36%', 'width' => '3.23%', 'height' => '31.46%', 'clip' => 'polygon(8.7% 83.0%, 13.0% 76.2%, 17.4% 68.9%, 21.7% 62.1%, 26.1% 55.7%, 30.4% 50.6%, 34.8% 46.4%, 39.1% 42.6%, 43.5% 39.6%, 47.8% 37.9%, 52.2% 37.0%, 56.5% 36.6%, 60.9% 37.4%, 65.2% 38.7%, 69.6% 43.8%, 73.9% 56.6%, 78.3% 64.3%, 82.6% 71.9%, 87.0% 78.3%, 87.0% 94.0%, 82.6% 94.5%, 78.3% 94.5%, 73.9% 94.9%, 69.6% 94.9%, 65.2% 94.9%, 60.9% 94.9%, 56.5% 94.9%, 52.2% 94.9%, 47.8% 94.9%, 43.5% 94.9%, 39.1% 94.9%, 34.8% 94.9%, 30.4% 94.9%, 26.1% 94.9%, 21.7% 94.9%, 17.4% 94.9%, 13.0% 94.5%, 8.7% 94.0%)'],
                        '23' => ['left' => '60.11%', 'top' => '7.36%', 'width' => '3.51%', 'height' => '31.46%', 'clip' => 'polygon(8.0% 81.3%, 12.0% 75.7%, 16.0% 68.9%, 20.0% 60.9%, 24.0% 54.9%, 28.0% 49.4%, 32.0% 44.3%, 36.0% 40.0%, 40.0% 36.2%, 44.0% 33.6%, 48.0% 31.9%, 52.0% 31.5%, 56.0% 31.1%, 60.0% 31.9%, 64.0% 34.0%, 68.0% 42.6%, 72.0% 53.2%, 76.0% 60.4%, 80.0% 67.7%, 84.0% 75.7%, 88.0% 80.4%, 88.0% 90.2%, 84.0% 91.5%, 80.0% 92.3%, 76.0% 92.8%, 72.0% 93.2%, 68.0% 94.0%, 64.0% 94.5%, 60.0% 95.3%, 56.0% 95.7%, 52.0% 96.2%, 48.0% 96.2%, 44.0% 96.2%, 40.0% 95.7%, 36.0% 95.3%, 32.0% 94.9%, 28.0% 94.5%, 24.0% 93.6%, 20.0% 93.2%, 16.0% 92.3%, 12.0% 91.9%, 8.0% 91.1%)'],
                        '24' => ['left' => '64.47%', 'top' => '7.36%', 'width' => '3.65%', 'height' => '31.46%', 'clip' => 'polygon(7.7% 82.1%, 11.5% 78.3%, 15.4% 73.2%, 19.2% 67.7%, 23.1% 61.7%, 26.9% 56.2%, 30.8% 52.3%, 34.6% 47.7%, 38.5% 43.8%, 42.3% 40.9%, 46.2% 39.1%, 50.0% 38.3%, 53.8% 37.9%, 57.7% 38.3%, 61.5% 39.1%, 65.4% 41.7%, 69.2% 54.5%, 73.1% 63.0%, 76.9% 70.2%, 80.8% 76.2%, 84.6% 79.1%, 88.5% 81.7%, 88.5% 89.8%, 84.6% 91.5%, 80.8% 92.3%, 76.9% 92.8%, 73.1% 93.6%, 69.2% 94.0%, 65.4% 94.5%, 61.5% 94.9%, 57.7% 95.3%, 53.8% 95.7%, 50.0% 96.2%, 46.2% 96.2%, 42.3% 96.2%, 38.5% 95.7%, 34.6% 95.3%, 30.8% 94.9%, 26.9% 94.5%, 23.1% 94.0%, 19.2% 93.2%, 15.4% 92.8%, 11.5% 92.3%, 7.7% 91.1%)'],
                        '25' => ['left' => '69.10%', 'top' => '7.36%', 'width' => '3.65%', 'height' => '31.46%', 'clip' => 'polygon(7.7% 83.4%, 11.5% 79.6%, 15.4% 75.7%, 19.2% 70.2%, 23.1% 64.3%, 26.9% 57.9%, 30.8% 49.4%, 34.6% 45.5%, 38.5% 43.8%, 42.3% 43.0%, 46.2% 42.1%, 50.0% 42.6%, 53.8% 43.8%, 57.7% 46.4%, 61.5% 51.1%, 65.4% 56.2%, 69.2% 61.3%, 73.1% 66.0%, 76.9% 72.3%, 80.8% 77.4%, 84.6% 80.4%, 88.5% 83.8%, 88.5% 89.4%, 84.6% 91.5%, 80.8% 92.8%, 76.9% 93.2%, 73.1% 93.6%, 69.2% 94.0%, 65.4% 94.5%, 61.5% 94.9%, 57.7% 94.9%, 53.8% 95.3%, 50.0% 95.3%, 46.2% 95.3%, 42.3% 94.9%, 38.5% 94.9%, 34.6% 94.5%, 30.8% 94.5%, 26.9% 94.0%, 23.1% 94.0%, 19.2% 94.0%, 15.4% 94.0%, 11.5% 93.6%, 7.7% 92.8%)'],
                        '26' => ['left' => '74.02%', 'top' => '7.36%', 'width' => '5.34%', 'height' => '31.46%', 'clip' => 'polygon(5.3% 83.0%, 10.5% 57.9%, 15.8% 51.5%, 21.1% 50.2%, 26.3% 51.1%, 31.6% 55.3%, 36.8% 60.0%, 42.1% 63.4%, 47.4% 60.9%, 52.6% 52.3%, 57.9% 51.1%, 63.2% 51.5%, 68.4% 51.1%, 73.7% 49.8%, 78.9% 50.6%, 84.2% 53.2%, 89.5% 61.7%, 89.5% 92.3%, 84.2% 94.0%, 78.9% 94.9%, 73.7% 94.9%, 68.4% 94.5%, 63.2% 94.5%, 57.9% 94.0%, 52.6% 93.2%, 47.4% 93.2%, 42.1% 93.6%, 36.8% 94.5%, 31.6% 94.9%, 26.3% 95.3%, 21.1% 95.3%, 15.8% 94.5%, 10.5% 93.2%, 5.3% 90.2%)'],
                        '27' => ['left' => '80.90%', 'top' => '7.36%', 'width' => '5.34%', 'height' => '31.46%', 'clip' => 'polygon(5.3% 84.3%, 10.5% 74.0%, 15.8% 59.6%, 21.1% 54.5%, 26.3% 52.3%, 31.6% 52.3%, 36.8% 56.2%, 42.1% 57.4%, 47.4% 52.3%, 52.6% 52.8%, 57.9% 51.9%, 63.2% 52.3%, 68.4% 53.6%, 73.7% 56.2%, 78.9% 59.1%, 84.2% 65.5%, 89.5% 78.3%, 89.5% 91.5%, 84.2% 93.6%, 78.9% 94.5%, 73.7% 94.9%, 68.4% 94.5%, 63.2% 94.0%, 57.9% 93.6%, 52.6% 93.2%, 47.4% 93.6%, 42.1% 94.0%, 36.8% 94.5%, 31.6% 94.9%, 26.3% 94.9%, 21.1% 94.9%, 15.8% 94.0%, 10.5% 93.2%, 5.3% 89.8%)'],
                        '28' => ['left' => '87.78%', 'top' => '7.36%', 'width' => '5.20%', 'height' => '31.46%', 'clip' => 'polygon(5.4% 82.1%, 10.8% 66.0%, 16.2% 58.7%, 21.6% 55.3%, 27.0% 54.9%, 32.4% 57.0%, 37.8% 58.7%, 43.2% 55.7%, 48.6% 57.0%, 54.1% 60.4%, 59.5% 62.6%, 64.9% 55.3%, 70.3% 55.3%, 75.7% 57.0%, 81.1% 60.4%, 86.5% 74.9%, 91.9% 82.6%, 91.9% 88.9%, 86.5% 92.3%, 81.1% 93.6%, 75.7% 94.5%, 70.3% 94.5%, 64.9% 94.5%, 59.5% 94.0%, 54.1% 93.6%, 48.6% 93.2%, 43.2% 93.6%, 37.8% 94.0%, 32.4% 94.5%, 27.0% 94.9%, 21.6% 94.9%, 16.2% 94.5%, 10.8% 93.6%, 5.4% 91.5%)'],
                        '48' => ['left' => '9.69%', 'top' => '46.18%', 'width' => '5.62%', 'height' => '25.44%', 'clip' => 'polygon(15% 5%, 25% 5%, 30% 22%, 42% 22%, 48% 5%, 52% 5%, 58% 22%, 70% 22%, 75% 5%, 85% 5%, 92% 22%, 96% 40%, 96% 90%, 92% 96%, 8% 96%, 4% 90%, 4% 40%, 8% 22%)'],
                        '47' => ['left' => '16.43%', 'top' => '46.18%', 'width' => '5.48%', 'height' => '25.44%', 'clip' => 'polygon(15% 5%, 25% 5%, 30% 22%, 42% 22%, 48% 5%, 52% 5%, 58% 22%, 70% 22%, 75% 5%, 85% 5%, 92% 22%, 96% 40%, 96% 90%, 92% 96%, 8% 96%, 4% 90%, 4% 40%, 8% 22%)'],
                        '46' => ['left' => '23.17%', 'top' => '46.18%', 'width' => '5.62%', 'height' => '25.44%', 'clip' => 'polygon(15% 5%, 25% 5%, 30% 22%, 42% 22%, 48% 5%, 52% 5%, 58% 22%, 70% 22%, 75% 5%, 85% 5%, 92% 22%, 96% 40%, 96% 90%, 92% 96%, 8% 96%, 4% 90%, 4% 40%, 8% 22%)'],
                        '45' => ['left' => '29.92%', 'top' => '46.18%', 'width' => '3.93%', 'height' => '25.44%', 'clip' => 'polygon(25% 5%, 40% 5%, 45% 18%, 55% 18%, 60% 5%, 75% 5%, 85% 28%, 90% 48%, 90% 88%, 85% 96%, 15% 96%, 10% 88%, 10% 48%, 15% 28%)'],
                        '44' => ['left' => '34.97%', 'top' => '46.18%', 'width' => '3.65%', 'height' => '25.44%', 'clip' => 'polygon(25% 5%, 40% 5%, 45% 18%, 55% 18%, 60% 5%, 75% 5%, 85% 28%, 90% 48%, 90% 88%, 85% 96%, 15% 96%, 10% 88%, 10% 48%, 15% 28%)'],
                        '43' => ['left' => '39.61%', 'top' => '46.18%', 'width' => '3.51%', 'height' => '25.44%', 'clip' => 'polygon(35% 3%, 48% 3%, 52% 3%, 65% 3%, 75% 22%, 82% 42%, 82% 85%, 76% 94%, 24% 94%, 18% 85%, 18% 42%, 25% 22%)'],
                        '42' => ['left' => '44.10%', 'top' => '46.18%', 'width' => '2.95%', 'height' => '25.44%', 'clip' => 'polygon(28% 5%, 42% 5%, 48% 14%, 52% 14%, 58% 5%, 72% 5%, 80% 24%, 84% 48%, 84% 88%, 78% 96%, 22% 96%, 16% 88%, 16% 48%, 20% 24%)'],
                        '41' => ['left' => '47.89%', 'top' => '46.18%', 'width' => '2.81%', 'height' => '25.44%', 'clip' => 'polygon(28% 5%, 42% 5%, 48% 14%, 52% 14%, 58% 5%, 72% 5%, 80% 24%, 82% 48%, 82% 88%, 76% 96%, 24% 96%, 18% 88%, 18% 48%, 20% 24%)'],
                        '31' => ['left' => '52.11%', 'top' => '46.18%', 'width' => '2.81%', 'height' => '25.44%', 'clip' => 'polygon(28% 5%, 42% 5%, 48% 14%, 52% 14%, 58% 5%, 72% 5%, 80% 24%, 82% 48%, 82% 88%, 76% 96%, 24% 96%, 18% 88%, 18% 48%, 20% 24%)'],
                        '32' => ['left' => '55.76%', 'top' => '46.18%', 'width' => '2.95%', 'height' => '25.44%', 'clip' => 'polygon(28% 5%, 42% 5%, 48% 14%, 52% 14%, 58% 5%, 72% 5%, 80% 24%, 84% 48%, 84% 88%, 78% 96%, 22% 96%, 16% 88%, 16% 48%, 20% 24%)'],
                        '33' => ['left' => '59.69%', 'top' => '46.18%', 'width' => '3.37%', 'height' => '25.44%', 'clip' => 'polygon(35% 3%, 48% 3%, 52% 3%, 65% 3%, 75% 22%, 82% 42%, 82% 85%, 76% 94%, 24% 94%, 18% 85%, 18% 42%, 25% 22%)'],
                        '34' => ['left' => '64.04%', 'top' => '46.18%', 'width' => '3.79%', 'height' => '25.44%', 'clip' => 'polygon(25% 5%, 40% 5%, 45% 18%, 55% 18%, 60% 5%, 75% 5%, 85% 28%, 90% 48%, 90% 88%, 85% 96%, 15% 96%, 10% 88%, 10% 48%, 15% 28%)'],
                        '35' => ['left' => '68.82%', 'top' => '46.18%', 'width' => '3.93%', 'height' => '25.44%', 'clip' => 'polygon(25% 5%, 40% 5%, 45% 18%, 55% 18%, 60% 5%, 75% 5%, 85% 28%, 90% 48%, 90% 88%, 85% 96%, 15% 96%, 10% 88%, 10% 48%, 15% 28%)'],
                        '36' => ['left' => '74.02%', 'top' => '46.18%', 'width' => '5.48%', 'height' => '25.44%', 'clip' => 'polygon(15% 5%, 25% 5%, 30% 22%, 42% 22%, 48% 5%, 52% 5%, 58% 22%, 70% 22%, 75% 5%, 85% 5%, 92% 22%, 96% 40%, 96% 90%, 92% 96%, 8% 96%, 4% 90%, 4% 40%, 8% 22%)'],
                        '37' => ['left' => '80.76%', 'top' => '46.18%', 'width' => '5.48%', 'height' => '25.44%', 'clip' => 'polygon(15% 5%, 25% 5%, 30% 22%, 42% 22%, 48% 5%, 52% 5%, 58% 22%, 70% 22%, 75% 5%, 85% 5%, 92% 22%, 96% 40%, 96% 90%, 92% 96%, 8% 96%, 4% 90%, 4% 40%, 8% 22%)'],
                        '38' => ['left' => '87.50%', 'top' => '46.18%', 'width' => '5.62%', 'height' => '25.44%', 'clip' => 'polygon(15% 5%, 25% 5%, 30% 22%, 42% 22%, 48% 5%, 52% 5%, 58% 22%, 70% 22%, 75% 5%, 85% 5%, 92% 22%, 96% 40%, 96% 90%, 92% 96%, 8% 96%, 4% 90%, 4% 40%, 8% 22%)'],
                    ];
                @endphp

                <div class="relative w-full" style="max-width: 700px; margin: 0 auto;">
                    <img src="{{ asset('images/dental-chart.png') }}?v=6" alt="Dental Chart" class="w-full h-auto select-none pointer-events-none" draggable="false">

                    {{-- Right-click context menu --}}
                    <div x-show="ctxMenu.show" x-cloak x-transition.opacity
                         class="absolute z-50 w-52 bg-white border border-gray-200 rounded-xl shadow-xl py-1.5 max-h-72 overflow-y-auto"
                         :style="'left: ' + ctxMenu.x + 'px; top: ' + ctxMenu.y + 'px'"
                         x-on:click.outside="ctxMenu.show = false">
                        <div class="px-3 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-wider" x-text="'Tooth ' + ctxMenu.tooth"></div>
                        <div class="border-t border-gray-100 my-1"></div>
                        @foreach($availableConditions as $condition)
                            <button x-on:click="applyCtx('{{ $condition['code'] }}')"
                                    class="w-full flex items-center gap-2 px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50 transition text-left">
                                <span class="w-2.5 h-2.5 rounded-full flex-none" style="background-color: {{ $condition['colour'] }}"></span>
                                {{ $condition['label'] }}
                            </button>
                        @endforeach
                    </div>

                    @foreach($toothHotspots as $tooth => $pos)
                        @php
                            $conditions = $toothChartData[$tooth]['conditions'] ?? [];
                            $hasConditions = count($conditions) > 0;
                            $isSelected = in_array($tooth, $selectedTeeth);
                            $isMissing = in_array('MISSING', $conditions);
                            $condColour = null;
                            if ($hasConditions && !$isMissing) {
                                foreach ($availableConditions as $ac) {
                                    if ($ac['code'] === $conditions[0]) { $condColour = $ac['colour']; break; }
                                }
                            }
                            $clipPath = $pos['clip'] ?? '';
                        @endphp
                        <button
                            wire:click="toggleTooth('{{ $tooth }}')"
                            x-on:contextmenu="openCtx($event, '{{ $tooth }}')"
                            x-on:dragover.prevent
                            x-on:drop.prevent.stop="
                                if (dragging) {
                                    $wire.toggleTooth('{{ $tooth }}');
                                    $wire.applyConditionToTeeth(dragging);
                                    flash('Applied to tooth {{ $tooth }}');
                                    dragging = null;
                                }
                            "
                            class="absolute transition-all duration-150 group"
                            style="left: {{ $pos['left'] }}; top: {{ $pos['top'] }}; width: {{ $pos['width'] }}; height: {{ $pos['height'] }}; clip-path: {{ $clipPath }};"
                        >
                            @if($isMissing)
                                <div class="absolute inset-0 bg-white/70 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                            @elseif($hasConditions && $condColour)
                                <div class="absolute inset-0 opacity-35" style="background-color: {{ $condColour }};"></div>
                            @endif

                            @if($isSelected)
                                <div class="absolute inset-0 bg-indigo-500/20 border-2 border-indigo-500"></div>
                            @else
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-clinical/10 transition"></div>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Selected teeth detail panel --}}
            @if(count($selectedTeeth) > 0)
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">
                                {{ count($selectedTeeth) === 1 ? 'Tooth ' . $selectedTeeth[0] : count($selectedTeeth) . ' teeth selected' }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">
                                @if(count($selectedTeeth) > 1)
                                    {{ implode(', ', array_slice($selectedTeeth, 0, 8)) }}{{ count($selectedTeeth) > 8 ? '...' : '' }}
                                @else
                                    Click conditions below to add, or drag them onto the chart
                                @endif
                            </p>
                        </div>
                        <button wire:click="clearSelectedTeeth" class="text-xs text-gray-400 hover:text-gray-600 transition">Clear selection</button>
                    </div>

                    @php
                        $selectedConditions = collect($selectedTeeth)
                            ->flatMap(fn ($t) => collect($toothChartData[$t]['conditions'] ?? [])
                                ->map(fn ($c) => ['tooth' => $t, 'code' => $c]))
                            ->all();
                    @endphp
                    @if(count($selectedConditions) > 0)
                        <div class="flex flex-wrap gap-1.5 mb-4">
                            @foreach($selectedConditions as $sc)
                                @php
                                    $condLabel = $sc['code'];
                                    $condColour = '#94a3b8';
                                    foreach ($availableConditions as $ac) {
                                        if ($ac['code'] === $sc['code']) { $condLabel = $ac['label']; $condColour = $ac['colour']; break; }
                                    }
                                @endphp
                                <span class="inline-flex items-center gap-0.5 text-[9px] font-medium pl-1.5 pr-0.5 py-px rounded-full text-white leading-tight" style="background-color: {{ $condColour }}">
                                    <span class="opacity-70">#{{ $sc['tooth'] }}</span> {{ Str::limit($condLabel, 14) }}
                                    <button wire:click="removeConditionFromTooth('{{ $sc['tooth'] }}', '{{ $sc['code'] }}')" class="hover:bg-white/20 rounded-full p-0.5 transition">
                                        <svg class="h-2 w-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            {{-- Diagnosis Summary Table (BrightPlans style) --}}
            @php
                $teethWithConditions = collect($toothChartData)->filter(fn($d) => count($d['conditions'] ?? []) > 0);
            @endphp
            @if($teethWithConditions->count() > 0)
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    {{-- Upper Jaw --}}
                    @php
                        $upperTeethWithCond = $teethWithConditions->filter(fn($d, $k) => intval($k) >= 11 && intval($k) <= 28)->sortKeys();
                        $lowerTeethWithCond = $teethWithConditions->filter(fn($d, $k) => intval($k) >= 31 && intval($k) <= 48)->sortKeys();
                    @endphp
                    @if($upperTeethWithCond->count() > 0)
                        <div class="px-5 pt-4 pb-1">
                            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wide border-b border-gray-200 pb-2 mb-2">Diagnosis — Upper Jaw</h4>
                            <div class="grid grid-cols-2 gap-x-8 gap-y-1">
                                @foreach($upperTeethWithCond as $tNum => $tData)
                                    <div class="flex items-center gap-3 py-1.5 border-b border-gray-50 text-sm">
                                        <span class="text-gray-400 font-mono text-xs w-6">{{ $tNum }}.</span>
                                        <span class="text-gray-700">
                                            @foreach($tData['conditions'] as $c)
                                                @php $cl = $c; foreach($availableConditions as $ac) { if($ac['code']===$c){$cl=$ac['label'];break;} } @endphp
                                                {{ $cl }}@if(!$loop->last), @endif
                                            @endforeach
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if($lowerTeethWithCond->count() > 0)
                        <div class="px-5 pt-3 pb-4">
                            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wide border-b border-gray-200 pb-2 mb-2">Diagnosis — Lower Jaw</h4>
                            <div class="grid grid-cols-2 gap-x-8 gap-y-1">
                                @foreach($lowerTeethWithCond as $tNum => $tData)
                                    <div class="flex items-center gap-3 py-1.5 border-b border-gray-50 text-sm">
                                        <span class="text-gray-400 font-mono text-xs w-6">{{ $tNum }}.</span>
                                        <span class="text-gray-700">
                                            @foreach($tData['conditions'] as $c)
                                                @php $cl = $c; foreach($availableConditions as $ac) { if($ac['code']===$c){$cl=$ac['label'];break;} } @endphp
                                                {{ $cl }}@if(!$loop->last), @endif
                                            @endforeach
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Condition tags summary --}}
            @if(count($conditionTags) > 0)
                <div class="bg-gradient-to-r from-slate-50 to-white border border-gray-200 rounded-2xl p-4">
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Conditions Found</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($conditionTags as $tag)
                            @php
                                $tagLabel = $tag;
                                $tagColour = '#94a3b8';
                                $tagCount = 0;
                                foreach ($availableConditions as $ac) {
                                    if ($ac['code'] === $tag) { $tagLabel = $ac['label']; $tagColour = $ac['colour']; break; }
                                }
                                foreach ($toothChartData as $td) {
                                    if (in_array($tag, $td['conditions'])) $tagCount++;
                                }
                            @endphp
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full text-white" style="background-color: {{ $tagColour }}">
                                {{ $tagLabel }}
                                <span class="bg-white/20 text-[10px] px-1.5 rounded-full">{{ $tagCount }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ============================================================
             RIGHT: Conditions palette + notes (1 col)
        ============================================================ --}}
        <div class="space-y-4">

            {{-- Draggable conditions palette --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Conditions</h3>
                <p class="text-xs text-gray-400 mb-4">Drag onto teeth or click to paint on selected teeth</p>

                <div class="grid grid-cols-1 gap-1.5">
                    @foreach($availableConditions as $condition)
                        <button
                            draggable="true"
                            x-on:dragstart="dragging = '{{ $condition['code'] }}'"
                            x-on:dragend="dragging = null"
                            wire:click="selectCondition('{{ $condition['code'] }}')"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl border text-left text-xs font-medium transition-all cursor-grab active:cursor-grabbing
                                {{ $activeCondition === $condition['code']
                                    ? 'border-clinical bg-clinical/5 text-clinical ring-1 ring-clinical/20'
                                    : 'border-gray-100 text-gray-600 hover:border-gray-200 hover:bg-gray-50' }}"
                        >
                            <span class="w-3 h-3 rounded-full flex-none ring-1 ring-black/5" style="background-color: {{ $condition['colour'] }}"></span>
                            <span class="flex-1">{{ $condition['label'] }}</span>
                            @if($activeCondition === $condition['code'])
                                <svg class="w-3.5 h-3.5 text-clinical" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"/></svg>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Diagnosis notes --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <label class="block text-sm font-semibold text-gray-800 mb-1">Diagnosis Notes</label>
                <p class="text-xs text-gray-400 mb-3">Included in the patient's plan document.</p>
                <textarea
                    wire:model.blur="diagnosisText"
                    rows="6"
                    placeholder="Describe findings and recommended course of action..."
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical resize-none transition"
                ></textarea>
            </div>

            {{-- Save --}}
            <button
                wire:click="saveDiagnosis"
                x-on:click="flash('Diagnosis saved')"
                class="w-full bg-clinical hover:bg-clinical-700 text-white text-sm font-semibold rounded-xl px-4 py-3 transition flex items-center justify-center gap-2 shadow-sm"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Diagnosis
            </button>
        </div>
    </div>

</div>
