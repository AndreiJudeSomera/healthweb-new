@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<main class="p-4 bg-white text-[#1f2b5b] font-sans text-sm min-h-screen">
    <!-- Top Bar -->
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-4">
            <!-- Search -->
            <div class="relative">
                <input type="text" placeholder="Search Patients"
                    class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm w-64">
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                </svg>
            </div>

            <!-- Dropdown & Filter -->
            <div class="flex items-center gap-1">
                <span>Showing</span>
                <select class="border rounded px-2 py-1 text-sm">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                <button class="ml-2 px-3 py-1 border rounded text-sm hover:bg-gray-100">Filter</button>
            </div>
        </div>

        <!-- Add Appointment Button -->
        <button class="bg-[#1f2b5b] hover:bg-blue-900 text-white px-4 py-2 rounded text-sm flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>
            + Add New Appointment
        </button>
    </div>

    <!-- Section Header -->
    <div class="bg-[#1f2b5b] text-white font-semibold rounded-t-md px-4 py-2 flex justify-between items-center">
        <span>CURRENT APPOINTMENTS</span>
        <span>JAN 26, 2025</span>
    </div>

    <!-- Appointment Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4 bg-white border border-t-0 rounded-b-md">
    <!-- Card 1 -->
    <div class="border rounded-md p-3 flex gap-3 shadow-sm text-xs items-start w-full">
        <div class="text-lg font-bold text-[#1f2b5b] min-w-[20px]">1</div>
        <div class="flex flex-col gap-1 w-full">
            <div class="flex justify-between items-center w-full">
                <div class="font-semibold capitalize">Somera Andrei</div>
                <div class="text-[10px] font-semibold text-[#1f2b5b] uppercase">Prescription</div>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500 w-full">
                <div>January 26, 2025</div>
                <div>8:00 AM</div>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="border rounded-md p-3 flex gap-3 shadow-sm text-xs items-start w-full">
        <div class="text-lg font-bold text-[#1f2b5b] min-w-[20px]">2</div>
        <div class="flex flex-col gap-1 w-full">
            <div class="flex justify-between items-center w-full">
                <div class="font-semibold capitalize">Glen Philip</div>
                <div class="text-[10px] font-semibold text-[#1f2b5b] uppercase">Consultation</div>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500 w-full">
                <div>January 26, 2025</div>
                <div>9:00 AM</div>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="border rounded-md p-3 flex gap-3 shadow-sm text-xs items-start w-full">
        <div class="text-lg font-bold text-[#1f2b5b] min-w-[20px]">3</div>
        <div class="flex flex-col gap-1 w-full">
            <div class="flex justify-between items-center w-full">
                <div class="font-semibold capitalize">Ma. Ronie Boniacio</div>
                <div class="text-[10px] font-semibold text-[#1f2b5b] uppercase">Prescription</div>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500 w-full">
                <div>January 26, 2025</div>
                <div>10:00 AM</div>
            </div>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="border rounded-md p-3 flex gap-3 shadow-sm text-xs items-start w-full">
        <div class="text-lg font-bold text-[#1f2b5b] min-w-[20px]">4</div>
        <div class="flex flex-col gap-1 w-full">
            <div class="flex justify-between items-center w-full">
                <div class="font-semibold capitalize">Hanzo Boniacio</div>
                <div class="text-[10px] font-semibold text-[#1f2b5b] uppercase">Consultation</div>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500 w-full">
                <div>January 26, 2025</div>
                <div>11:00 AM</div>
            </div>
        </div>
    </div>

    <!-- Card 5 -->
    <div class="border rounded-md p-3 flex gap-3 shadow-sm text-xs items-start w-full">
        <div class="text-lg font-bold text-[#1f2b5b] min-w-[20px]">5</div>
        <div class="flex flex-col gap-1 w-full">
            <div class="flex justify-between items-center w-full">
                <div class="font-semibold capitalize">Ruffa Angeleres</div>
                <div class="text-[10px] font-semibold text-[#1f2b5b] uppercase">Consultation</div>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500 w-full">
                <div>January 26, 2025</div>
                <div>12:00 PM</div>
            </div>
        </div>
    </div>

    <!-- Continue with cards 6 to 12 as needed -->
</div>


    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4 text-sm text-gray-600">
        <span>Previous page</span>
        <div class="flex gap-1">
            <button class="px-2 py-1 rounded bg-[#1f2b5b] text-white">1</button>
            <button class="px-2 py-1 rounded bg-gray-200">2</button>
            <button class="px-2 py-1 rounded bg-gray-200">3</button>
            <button class="px-2 py-1 rounded bg-gray-200">4</button>
        </div>
        <span>Next page</span>
    </div>
</main>
@endsection
