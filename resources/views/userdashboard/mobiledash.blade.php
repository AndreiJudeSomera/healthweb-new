@extends('mobilelayouts.app')

@section('content')
<div class="flex flex-col min-h-screen bg-white">

    <!-- Main Content -->
    <div class="flex-1 ml-0 md:ml-64 bg-white">

        <!-- Top Bar -->
        <header class="flex items-center justify-between px-4 py-3 border-b ">
            <h1 class="text-lg font-semibold">Announcements</h1>
            
        </header>

        <!-- Content -->
        <main class="p-4">
            <div class="max-w-sm mx-auto space-y-4">

                <!-- Announcement Card -->
                <div class="bg-white shadow rounded-xl p-4 border">
                    <h2 class="text-base font-semibold text-[#1f2b5b]">Vaccine Availability</h2>
                    <p class="text-sm text-gray-600 mt-1">Anti-rabies vaccines will be available starting Sept 5. Please visit the clinic early.</p>
                    <div class="flex justify-between items-center mt-3 text-xs text-gray-500">
                        <span>📅 Aug 30, 2025</span>
                        <span class="bg-[#0185FE] text-white px-2 py-1 rounded-md">Important</span>
                    </div>
                </div>

                <!-- Announcement Card -->
                <div class="bg-white shadow rounded-xl p-4 border">
                    <h2 class="text-base font-semibold text-[#1f2b5b]">Clinic Schedule Update</h2>
                    <p class="text-sm text-gray-600 mt-1">The clinic will be closed on Sept 10 due to maintenance work.</p>
                    <div class="flex justify-between items-center mt-3 text-xs text-gray-500">
                        <span>📅 Aug 28, 2025</span>
                        <span class="bg-yellow-400 text-[#1f2b5b] px-2 py-1 rounded-md">Reminder</span>
                    </div>
                </div>

                <!-- Announcement Card -->
                <div class="bg-white shadow rounded-xl p-4 border">
                    <h2 class="text-base font-semibold text-[#1f2b5b]">Free Check-up Day</h2>
                    <p class="text-sm text-gray-600 mt-1">Join our free consultation and general check-up on Sept 15, 2025.</p>
                    <div class="flex justify-between items-center mt-3 text-xs text-gray-500">
                        <span>📅 Aug 25, 2025</span>
                        <span class="bg-green-500 text-white px-2 py-1 rounded-md">Event</span>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
