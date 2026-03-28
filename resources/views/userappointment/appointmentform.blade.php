@extends('mobilelayouts.app')

@section('content')
<div class="flex flex-col min-h-screen bg-white">
    <!-- Top Bar -->
    

        <!-- Title -->
        

         <header class="flex items-center justify-center gap-2 px-4 py-3 border-b">
            <span class="text-lg font-semibold">Create Appointment</span>
        </header>
    <!-- Main Content -->
    <main class="flex-1 p-4 space-y-6">
        <!-- Select Date -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Select Date</label>
            <div class="flex items-center justify-between border border-gray-300 rounded-lg px-3 py-2">
                <span class="text-sm text-gray-700">Apr, 09, 2023</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M8 7V3M16 7V3M4 11h16M4 19h16"/>
                </svg>
            </div>
        </div>

        <!-- Appointment Type -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Type</label>
            <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>Consultation</option>
                <option>Follow-up</option>
                <option>Prescription</option>
            </select>
        </div>

        <!-- Select Time -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Time</label>
            <div class="grid grid-cols-3 gap-2">
                @foreach([
                    ['time' => '8:00 AM', 'active' => true],
                    ['time' => '8:30 AM', 'active' => false],
                    ['time' => '9:00 AM', 'active' => false],
                    ['time' => '9:30 AM', 'active' => false],
                    ['time' => '10:30 AM', 'active' => 'green'],
                    ['time' => '11:00 AM', 'active' => true],
                    ['time' => '11:30 AM', 'active' => false],
                    ['time' => '1:00 PM', 'active' => true],
                    ['time' => '1:30 PM', 'active' => false],
                    ['time' => '2:00 PM', 'active' => 'blue'],
                    ['time' => '2:30 PM', 'active' => 'blue'],
                    ['time' => '3:30 PM', 'active' => true],
                    ['time' => '4:00 PM', 'active' => 'blue'],
                ] as $slot)
                <button
                    class="
                        text-xs px-2 py-2 rounded 
                        @if($slot['active'] === true)
                            bg-gray-200 text-gray-700
                        @elseif($slot['active'] === 'green')
                            bg-green-600 text-white
                        @elseif($slot['active'] === 'blue')
                            bg-blue-900 text-white
                        @else
                            bg-gray-100 text-gray-400
                        @endif
                    ">
                    {{ $slot['time'] }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button class="w-full bg-blue-900 text-white font-semibold py-3 rounded-lg">
                Submit
            </button>
        </div>
    </main>

    <!-- Back Button -->
    <footer class="flex justify-end p-4">
        <a href="#" class="inline-flex items-center px-3 py-2 text-gray-600 hover:text-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
    </footer>
</div>

<!-- Sidebar Toggle Script -->
<script>
    const menuButton = document.getElementById('menuButton');
    const sidebar = document.getElementById('sidebar');
    if(menuButton && sidebar){
        menuButton.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    }
</script>
@endsection
