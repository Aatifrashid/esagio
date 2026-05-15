<div x-data="{
    dragging: null,
    showToast: false,
    toastMsg: '',
    ctxMenu: { show: false, x: 0, y: 0, tooth: null },
    fdiToUni: {18:1,17:2,16:3,15:4,14:5,13:6,12:7,11:8,21:9,22:10,23:11,24:12,25:13,26:14,27:15,28:16,38:17,37:18,36:19,35:20,34:21,33:22,32:23,31:24,41:25,42:26,43:27,44:28,45:29,46:30,47:31,48:32},
    toothLabel(fdi) { return this.fdiToUni[fdi] || fdi; },
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
            this.flash('Applied to tooth ' + this.toothLabel(this.ctxMenu.tooth));
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

    <div class="space-y-6">

        {{-- ============================================================
             Interactive Tooth Chart (full width)
        ============================================================ --}}
        <div class="space-y-4">

            {{-- Quick select bar --}}
            <div class="flex items-center gap-2 text-xs">
                <span class="text-gray-400 font-medium">Quick select:</span>
                <button wire:click="selectAllUpper" class="px-2.5 py-1 rounded-md bg-gray-100 hover:bg-clinical/10 hover:text-clinical text-gray-600 transition font-medium">Upper jaw</button>
                <button wire:click="selectAllLower" class="px-2.5 py-1 rounded-md bg-gray-100 hover:bg-clinical/10 hover:text-clinical text-gray-600 transition font-medium">Lower jaw</button>
                <button wire:click="selectAll" class="px-2.5 py-1 rounded-md bg-gray-100 hover:bg-clinical/10 hover:text-clinical text-gray-600 transition font-medium">All teeth</button>
                @if(count($selectedTeeth) > 0)
                    <button wire:click="clearSelectedTeeth" class="px-2.5 py-1 rounded-md bg-red-50 text-red-500 hover:bg-red-100 transition font-medium">Clear ({{ count($selectedTeeth) }})</button>
                @endif
                @if(count($toothChartData) > 0)
                    <button wire:click="clearAllConditions" class="px-2.5 py-1 rounded-md bg-red-50 text-red-500 hover:bg-red-100 transition font-medium">Reset chart</button>
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
                        '48' => ['left' => '9.69%', 'top' => '46%', 'width' => '5.62%', 'height' => '29%', 'clip' => 'polygon(10% 25.2%, 14% 24%, 18% 22.8%, 22% 21.8%, 27% 21.2%, 32% 20.5%, 37% 19.8%, 42% 19.5%, 46% 20.6%, 50% 21.5%, 54% 22%, 58% 22%, 62% 21.5%, 66% 21.2%, 70% 21%, 74% 21.2%, 78% 21.5%, 82% 22.2%, 86% 23.2%, 90% 24.5%, 90% 37%, 86% 46%, 82% 56%, 78% 63%, 74% 68%, 70% 71%, 66% 72%, 62% 70.5%, 58% 65%, 54% 60%, 50% 57%, 46% 56%, 42% 59%, 37% 64.5%, 32% 69%, 27% 71%, 22% 70%, 18% 67%, 14% 62%, 10% 52%)'],
                        '47' => ['left' => '16.43%', 'top' => '46%', 'width' => '5.48%', 'height' => '29%', 'clip' => 'polygon(10% 25%, 14% 24%, 18% 22.8%, 22% 21.8%, 27% 21.2%, 32% 20.5%, 37% 19.8%, 42% 19.5%, 46% 20%, 50% 20.5%, 54% 19.5%, 58% 19.2%, 62% 20%, 66% 20.8%, 70% 21.2%, 74% 21.4%, 78% 21.6%, 82% 22.2%, 87% 23.2%, 92% 24.5%, 92% 36%, 87% 46%, 82% 57%, 78% 65%, 74% 71%, 70% 74%, 66% 75%, 62% 71%, 57% 64%, 53% 59%, 48% 59%, 43% 64%, 38% 70%, 33% 73%, 29% 72%, 24% 68%, 19% 60%, 14% 47%, 10% 37%)'],
                        '46' => ['left' => '23.17%', 'top' => '46%', 'width' => '5.62%', 'height' => '29%', 'clip' => 'polygon(10% 25.5%, 14% 24.2%, 18% 22.8%, 22% 21.5%, 27% 20.8%, 32% 20%, 37% 19.5%, 41% 19%, 45% 18.5%, 49% 19%, 53% 19.5%, 57% 19.8%, 61% 20%, 65% 20.5%, 69% 21%, 73% 21.2%, 77% 21.5%, 81% 22%, 86% 23.2%, 91% 24.5%, 91% 50%, 86% 60%, 82% 66%, 78% 72%, 74% 75.5%, 70% 77%, 66% 74%, 61% 68%, 57% 61%, 53% 57%, 49% 57.5%, 45% 61%, 41% 66%, 37% 70%, 32% 74%, 27% 76%, 22% 75%, 18% 65%, 14% 50%, 10% 38%)'],
                        '45' => ['left' => '29.92%', 'top' => '46%', 'width' => '3.93%', 'height' => '29%', 'clip' => 'polygon(9.1% 23.4%, 12.7% 22.8%, 16.4% 22.2%, 21.8% 21.3%, 25.5% 20.8%, 29.1% 20.3%, 34.5% 19.9%, 38.2% 19.6%, 41.8% 19.4%, 47.3% 19.1%, 50.9% 19.1%, 56.4% 19.2%, 60% 19.4%, 63.6% 19.7%, 69.1% 20.1%, 72.7% 20.4%, 76.4% 20.8%, 81.8% 21.4%, 85.5% 21.9%, 90.9% 22.5%, 90.9% 34.4%, 85.5% 38.1%, 81.8% 42.4%, 76.4% 49.6%, 72.7% 54.6%, 69.1% 59.7%, 63.6% 66.8%, 60% 70.6%, 56.4% 73.9%, 50.9% 77.7%, 47.3% 79.5%, 41.8% 81.1%, 38.2% 81.4%, 34.5% 80.2%, 29.1% 72%, 25.5% 64.7%, 21.8% 56.8%, 16.4% 44%, 12.7% 38%, 9.1% 34.6%)'],
                        '44' => ['left' => '34.97%', 'top' => '46%', 'width' => '3.65%', 'height' => '29%', 'clip' => 'polygon(7.8% 23.8%, 11.8% 23.3%, 15.7% 22.5%, 19.6% 21.8%, 25.5% 20.8%, 29.4% 20.3%, 33.3% 19.8%, 37.3% 19.3%, 43.1% 18.5%, 47.1% 17.9%, 51% 17.4%, 54.9% 17%, 60.8% 17.2%, 64.7% 17.6%, 68.6% 18.5%, 72.5% 19.4%, 78.4% 20.9%, 82.4% 21.7%, 86.3% 22.5%, 92.2% 23.1%, 92.2% 35.4%, 86.3% 38.9%, 82.4% 43.1%, 78.4% 47.8%, 72.5% 55.3%, 68.6% 60.6%, 64.7% 65.7%, 60.8% 70.2%, 54.9% 75.2%, 51% 77.5%, 47.1% 79%, 43.1% 79.7%, 37.3% 78.8%, 33.3% 74%, 29.4% 67.5%, 25.5% 60.1%, 19.6% 48.1%, 15.7% 40.5%, 11.8% 36.5%, 7.8% 34.5%)'],
                        '43' => ['left' => '39.61%', 'top' => '46%', 'width' => '3.51%', 'height' => '29%', 'clip' => 'polygon(8.2% 24.6%, 12.2% 24%, 16.3% 23.2%, 20.4% 22.4%, 24.5% 21.7%, 28.6% 21%, 32.7% 20.4%, 36.7% 19.9%, 40.8% 19.5%, 44.9% 19.1%, 51% 18.8%, 55.1% 18.9%, 59.2% 19%, 63.3% 19.3%, 67.3% 19.8%, 71.4% 20.4%, 75.5% 21%, 79.6% 21.7%, 83.7% 22.4%, 89.8% 23.2%, 89.8% 35.6%, 83.7% 39.4%, 79.6% 43.5%, 75.5% 48.4%, 71.4% 53.4%, 67.3% 58.3%, 63.3% 63.1%, 59.2% 67.3%, 55.1% 70.8%, 51% 75.7%, 44.9% 81%, 40.8% 82.9%, 36.7% 84.3%, 32.7% 85.3%, 28.6% 80.5%, 24.5% 71.7%, 20.4% 63.4%, 16.3% 54.3%, 12.2% 45.2%, 8.2% 36.7%)'],
                        '42' => ['left' => '44.1%', 'top' => '46%', 'width' => '2.95%', 'height' => '29%', 'clip' => 'polygon(11.9% 21.8%, 14.3% 21.7%, 19% 21.6%, 23.8% 21.4%, 26.2% 21.4%, 31% 21.3%, 35.7% 21.3%, 38.1% 21.3%, 42.9% 21.3%, 47.6% 21.3%, 50% 21.3%, 54.8% 21.3%, 59.5% 21.3%, 61.9% 21.3%, 66.7% 21.3%, 71.4% 21.4%, 73.8% 21.4%, 78.6% 21.4%, 83.3% 21.5%, 88.1% 21.6%, 88.1% 36.7%, 83.3% 40.2%, 78.6% 45.2%, 73.8% 51.4%, 71.4% 54.6%, 66.7% 60.6%, 61.9% 66.2%, 59.5% 68.6%, 54.8% 72.9%, 50% 76.2%, 47.6% 77.5%, 42.9% 79.4%, 38.1% 80.4%, 35.7% 78.5%, 31% 72.4%, 26.2% 64.8%, 23.8% 60.5%, 19% 50.8%, 14.3% 41.2%, 11.9% 36.5%)'],
                        '41' => ['left' => '47.89%', 'top' => '46%', 'width' => '2.81%', 'height' => '29%', 'clip' => 'polygon(12.5% 22%, 15% 21.9%, 20% 21.8%, 22.5% 21.8%, 27.5% 21.6%, 32.5% 21.5%, 35% 21.5%, 40% 21.4%, 45% 21.4%, 47.5% 21.3%, 52.5% 21.3%, 55% 21.3%, 60% 21.3%, 65% 21.3%, 67.5% 21.3%, 72.5% 21.4%, 77.5% 21.5%, 80% 21.6%, 85% 21.7%, 90% 21.8%, 90% 37.9%, 85% 41.7%, 80% 48.2%, 77.5% 52%, 72.5% 60%, 67.5% 67%, 65% 70.1%, 60% 75.1%, 55% 78.2%, 52.5% 79.1%, 47.5% 79.9%, 45% 79.8%, 40% 78.1%, 35% 72.5%, 32.5% 69%, 27.5% 61.3%, 22.5% 52.3%, 20% 47.6%, 15% 38.9%, 12.5% 35.4%)'],
                        '31' => ['left' => '52.11%', 'top' => '46%', 'width' => '2.81%', 'height' => '29%', 'clip' => 'polygon(14% 21.5%, 18% 21.3%, 22% 21%, 26% 20.8%, 30% 20.5%, 34% 20.2%, 38% 20%, 42% 19.8%, 46% 19.5%, 50% 19.2%, 54% 19%, 58% 18.8%, 62% 18.5%, 66% 18.5%, 70% 19%, 74% 19.5%, 78% 20%, 80% 20.5%, 82% 20.8%, 84% 21%, 84% 37%, 80% 44%, 76% 52%, 72% 59%, 68% 65%, 64% 70%, 60% 74%, 56% 77%, 52% 78.5%, 48% 78%, 44% 76%, 40% 72%, 36% 66%, 32% 60%, 28% 54%, 24% 47%, 20% 41%, 18% 38%, 16% 36%, 14% 35%)'],
                        '32' => ['left' => '55.76%', 'top' => '46%', 'width' => '2.95%', 'height' => '29%', 'clip' => 'polygon(12% 21.8%, 16% 21.5%, 20% 21.2%, 24% 21%, 28% 20.8%, 32% 20.5%, 36% 20.2%, 40% 20%, 44% 19.6%, 48% 19.2%, 52% 19%, 56% 18.8%, 60% 18.5%, 64% 18.5%, 68% 19%, 72% 19.5%, 76% 20%, 80% 20.5%, 83% 21%, 85% 21.4%, 85% 34%, 82% 41%, 78% 50%, 74% 58%, 70% 64%, 66% 70%, 62% 74.5%, 58% 77%, 54% 77.5%, 50% 76%, 46% 73%, 42% 69%, 38% 64%, 34% 58%, 30% 52%, 26% 46%, 22% 41%, 20% 38.5%, 16% 36%, 12% 34%)'],
                        '33' => ['left' => '59.69%', 'top' => '46%', 'width' => '3.37%', 'height' => '29%', 'clip' => 'polygon(8.5% 24.1%, 12.8% 23.4%, 17% 22.6%, 21.3% 21.8%, 25.5% 21%, 29.8% 20.3%, 34% 19.7%, 38.3% 19.3%, 42.6% 19.1%, 46.8% 19%, 51.1% 19.1%, 55.3% 19.4%, 59.6% 19.7%, 63.8% 20.2%, 68.1% 20.7%, 72.3% 21.3%, 76.6% 21.9%, 80.9% 22.5%, 85.1% 23.1%, 91.5% 23.7%, 91.5% 47.3%, 85.1% 58.1%, 80.9% 66.7%, 76.6% 76.4%, 72.3% 85.2%, 68.1% 84.7%, 63.8% 83.6%, 59.6% 82%, 55.3% 79.2%, 51.1% 75%, 46.8% 71.1%, 42.6% 67.5%, 38.3% 63.4%, 34% 59%, 29.8% 54.5%, 25.5% 50%, 21.3% 45.7%, 17% 41.6%, 12.8% 38.4%, 8.5% 36.4%)'],
                        '34' => ['left' => '64.04%', 'top' => '46%', 'width' => '3.79%', 'height' => '29%', 'clip' => 'polygon(10% 23.5%, 14% 22.8%, 18% 22%, 22% 21.2%, 26% 20.5%, 30% 19.8%, 34% 19.2%, 38% 18.8%, 42% 18.2%, 46% 17.5%, 50% 17%, 54% 16.5%, 58% 16.2%, 62% 16.5%, 66% 17.5%, 70% 18.5%, 74% 19.5%, 78% 20.5%, 82% 21.2%, 88% 22%, 88% 35%, 84% 39%, 80% 45%, 76% 53%, 72% 61%, 68% 68%, 64% 74%, 60% 77.5%, 56% 78%, 52% 76.5%, 48% 73%, 44% 68%, 40% 62%, 36% 56%, 32% 50%, 28% 45%, 24% 40%, 20% 36%, 14% 32%, 10% 30%)'],
                        '35' => ['left' => '68.82%', 'top' => '46%', 'width' => '3.93%', 'height' => '29%', 'clip' => 'polygon(10.9% 23.4%, 14.5% 22.9%, 18.2% 22.3%, 23.6% 21.5%, 27.3% 21%, 32.7% 20.3%, 36.4% 19.9%, 40% 19.2%, 45.5% 18.4%, 49.1% 18%, 54.5% 17.7%, 58.2% 17.8%, 63.6% 18.6%, 67.3% 19.1%, 70.9% 19.7%, 76.4% 20.5%, 80% 21%, 85.5% 21.6%, 89.1% 22.1%, 94.5% 22.6%, 94.5% 36.2%, 89.1% 39.1%, 85.5% 43.7%, 80% 55.3%, 76.4% 63%, 70.9% 73.3%, 67.3% 78.6%, 63.6% 81%, 58.2% 80.3%, 54.5% 78.8%, 49.1% 75.6%, 45.5% 72.6%, 40% 67.1%, 36.4% 62.4%, 32.7% 57.4%, 27.3% 49.9%, 23.6% 45.2%, 18.2% 39.2%, 14.5% 36.2%, 10.9% 34%)'],
                        '36' => ['left' => '74.02%', 'top' => '46%', 'width' => '5.48%', 'height' => '29%', 'clip' => 'polygon(8% 24.5%, 12% 23.5%, 16% 22.2%, 20% 21.2%, 24% 20.5%, 28% 20%, 32% 19.5%, 36% 19%, 40% 19.2%, 44% 20.5%, 48% 21.5%, 52% 22%, 56% 21.8%, 60% 21.2%, 64% 21%, 68% 21%, 72% 21%, 76% 21.2%, 80% 22%, 86% 23.5%, 86% 42%, 82% 55%, 78% 65%, 74% 72%, 70% 75%, 66% 73%, 62% 68%, 58% 63%, 54% 59%, 50% 57%, 46% 58%, 42% 63%, 38% 69%, 34% 74%, 30% 76%, 26% 75%, 22% 71%, 18% 63%, 14% 52%, 8% 38%)'],
                        '37' => ['left' => '80.76%', 'top' => '46%', 'width' => '5.48%', 'height' => '29%', 'clip' => 'polygon(5.1% 25.1%, 9% 24.3%, 14.1% 22.9%, 17.9% 22.1%, 23.1% 21.4%, 28.2% 20.8%, 32.1% 20.1%, 37.2% 19.4%, 42.3% 18.9%, 46.2% 19.3%, 51.3% 19.4%, 55.1% 20%, 60.3% 20.3%, 65.4% 21%, 69.2% 21.2%, 74.4% 21.4%, 79.5% 21.7%, 83.3% 22.3%, 88.5% 23.6%, 93.6% 24.8%, 93.6% 35%, 88.5% 46%, 83.3% 58.5%, 79.5% 67.1%, 74.4% 71.6%, 69.2% 73.8%, 65.4% 74.2%, 60.3% 70.3%, 55.1% 63.1%, 51.3% 58.5%, 46.2% 59.8%, 42.3% 65.3%, 37.2% 72.6%, 32.1% 75.3%, 28.2% 74.3%, 23.1% 71.3%, 17.9% 64.8%, 14.1% 55.9%, 9% 43.7%, 5.1% 36.2%)'],
                        '38' => ['left' => '87.5%', 'top' => '46%', 'width' => '5.62%', 'height' => '29%', 'clip' => 'polygon(8% 25%, 12% 24%, 16% 22.8%, 20% 21.8%, 24% 21.2%, 28% 20.8%, 32% 20.2%, 36% 19.8%, 40% 19.5%, 44% 20.5%, 48% 21.2%, 52% 22%, 56% 22%, 60% 21.5%, 64% 21%, 68% 21%, 72% 21.2%, 76% 21.5%, 80% 22.2%, 86% 23.5%, 86% 37%, 82% 46%, 78% 56%, 74% 63%, 70% 68%, 66% 71%, 62% 72%, 58% 70%, 54% 65%, 50% 60%, 46% 57%, 42% 56%, 38% 59%, 34% 64.5%, 30% 69%, 26% 71%, 22% 70%, 18% 67%, 14% 62%, 8% 45%)'],
                    ];
                @endphp

                <div class="relative w-full" style="max-width: 960px; margin: 0 auto;">
                    <img src="{{ asset('images/dental-chart.png') }}?v=6" alt="Dental Chart" class="w-full h-auto select-none pointer-events-none" draggable="false">

                    {{-- Right-click context menu --}}
                    <div x-show="ctxMenu.show" x-cloak x-transition.opacity
                         class="absolute z-50 w-52 bg-white border border-gray-200 rounded-xl shadow-xl py-1.5 max-h-72 overflow-y-auto"
                         :style="'left: ' + ctxMenu.x + 'px; top: ' + ctxMenu.y + 'px'"
                         x-on:click.outside="ctxMenu.show = false">
                        <div class="px-3 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-wider" x-text="'Tooth ' + toothLabel(ctxMenu.tooth)"></div>
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
                            $isMissing = in_array('missing', $conditions);
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
                                <div class="absolute inset-0" style="background-color: {{ $condColour }}; opacity: 0.45;"></div>
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
                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach($selectedConditions as $sc)
                                @php
                                    $condLabel = $sc['code'];
                                    $condColour = '#94a3b8';
                                    foreach ($availableConditions as $ac) {
                                        if ($ac['code'] === $sc['code']) { $condLabel = $ac['label']; $condColour = $ac['colour']; break; }
                                    }
                                    $fdiToUni = [18=>1,17=>2,16=>3,15=>4,14=>5,13=>6,12=>7,11=>8,21=>9,22=>10,23=>11,24=>12,25=>13,26=>14,27=>15,28=>16,38=>17,37=>18,36=>19,35=>20,34=>21,33=>22,32=>23,31=>24,41=>25,42=>26,43=>27,44=>28,45=>29,46=>30,47=>31,48=>32];
                                    $uniNum = $fdiToUni[(int)$sc['tooth']] ?? $sc['tooth'];
                                @endphp
                                <span class="inline-flex items-center gap-1 text-xs font-medium pl-1.5 pr-1 py-0.5 rounded-md text-white" style="background-color: {{ $condColour }}">
                                    <span class="opacity-70 font-bold">{{ $uniNum }}</span>
                                    <span class="truncate max-w-[4rem]">{{ $condLabel }}</span>
                                    <button wire:click="removeConditionFromTooth('{{ $sc['tooth'] }}', '{{ $sc['code'] }}')" class="hover:bg-white/20 rounded p-0.5 transition -mr-0.5">
                                        <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
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

        </div>

        {{-- ============================================================
             Notes + Save
        ============================================================ --}}
        <div class="space-y-4">

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
