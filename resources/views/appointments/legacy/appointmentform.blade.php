@extends('layouts.app')

@section('title', 'Add Appointment')

@section('content')
<main class="min-h-screen flex flex-col gap-8 justify-center items-center bg-white font-poppins py-6">
 <div class="bg-white rounded-xl border-2 border-blue-900 p-6 w-full max-w-md shadow-lg">
        <!-- Logo -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('assets/images/logo2.png') }}" alt="Logo" class="h-12">
        </div>

        <!-- Title -->
        <h2 class="text-center text-lg font-semibold text-[#1f2b5b] mb-6">APPOINTMENT FORM</h2>

       <form action="#" method="POST" class="space-y-4">
            @csrf

            <!-- Appointment Name -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1"> NAME</label>
                <input type="text" name="appointment_name"
                    class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm"
                    placeholder="">
            </div>

            <!-- Date -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">APPOINTMENT DATE</label>
                <input type="date" name="appointment_date"
                    class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
            </div>


            <!-- Type -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">APPOINTMENT TYPE</label>
                <select name="appointment_type"
                        class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
                    <option>Prescription</option>
                    <option>Consultation</option>
                    <option>Follow-up</option>
                </select>
            </div>

            <!-- Time -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">APPOINTMENT TIME</label>
                <input type="hidden" name="appointment_time" id="selectedTime">

                <div class="grid grid-cols-4 gap-2">
                    @php
                        $times = [
                            '8:00 AM', '8:30 AM', '9:00 AM', '9:30 AM',
                            '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM',
                            '1:00 PM', '1:30 PM', '2:00 PM', '2:30 PM',
                            '3:00 PM', '3:30 PM', '4:00 PM'
                        ];
                        $unavailable = ['8:30 AM', '9:00 AM', '9:30 AM', '10:00 AM', '11:30 AM', '1:30 PM'];
                    @endphp

                    @foreach ($times as $time)
                        @php
                            $isUnavailable = in_array($time, $unavailable);
                        @endphp
                        <button type="button"
                            class="time-btn bg-[#1f2b5b] text-white font-semibold py-1 rounded text-xs transition
                            {{ $isUnavailable ? 'opacity-50 pointer-events-none' : 'hover:bg-blue-800' }}"
                            data-time="{{ $time }}">
                            {{ $time }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between pt-2 gap-2">
                <button type="reset"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold w-full py-2 rounded">CANCEL</button>
                <button type="submit"
                        class="bg-[#1f2b5b] hover:bg-blue-900 text-white font-semibold w-full py-2 rounded">ADD</button>
            </div>
        </form>
    </div>
</main>

<!-- Inline Script -->
<script>
    document.querySelectorAll('.time-btn').forEach(button => {
        button.addEventListener('click', () => {
            if (button.classList.contains('pointer-events-none')) return;

            document.querySelectorAll('.time-btn').forEach(btn => {
                if (!btn.classList.contains('pointer-events-none')) {
                    btn.classList.remove('bg-green-700');
                    btn.classList.add('bg-[#1f2b5b]');
                }
            });

            button.classList.remove('bg-[#1f2b5b]');
            button.classList.add('bg-green-700');
            document.getElementById('selectedTime').value = button.dataset.time;
        });
    });
</script>
@endsection
