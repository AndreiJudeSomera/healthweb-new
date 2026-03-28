@extends('mobilelayouts.app')

@section('content')
<div class="flex flex-col min-h-screen ">

    <!-- Main Content -->
    <main class="flex-1 p-4 space-y-5">

        <!-- Section Title -->
        <div class="flex items-center justify-center text-gray-700 text-sm font-medium">
            
        </div>
   <header class="flex items-center justify-center gap-2 px-4 py-3 border-b">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path d="M12 8c-1.657 0-3 1.343-3 3v5h6v-5c0-1.657-1.343-3-3-3z"/>
        <path d="M5 20h14"/>
    </svg>
    <span class="text-lg font-semibold">All Records</span>
</header>


        <!-- Search and Filter -->
        <div class="flex items-center space-x-2">
            <div class="flex-1">
                <input type="text" placeholder="Search record..." 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm"/>
            </div>
            <button class="p-2 border border-gray-300 rounded-lg bg-white shadow-sm hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 14.414V19a1 1 0 01-.553.894l-4 2A1 1 0 019 21v-6.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
            </button>
        </div>

        <!-- Record Cards -->
        <div class="space-y-3">
            @foreach([
                ['type' => 'CONSULTATION', 'date' => 'January 25, 2025'],
                ['type' => 'CONSULTATION', 'date' => 'February 25, 2024'],
                ['type' => 'CONSULTATION', 'date' => 'January 21, 2024'],
                ['type' => 'CONSULTATION', 'date' => 'March 21, 2023'],
                ['type' => 'PRESCRIPTION', 'date' => 'March 21, 2023'],
            ] as $record)
            <div class="flex items-center justify-between bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition">
                <div>
                    <div class="text-sm font-semibold text-[#1f2b5b]">{{ $record['type'] }}</div>
                    <div class="text-xs text-gray-500">📅 {{ $record['date'] }}</div>
                </div>
                <button class="p-1 rounded-full hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M12 6h.01M12 12h.01M12 18h.01"/>
                    </svg>
                </button>
            </div>
            @endforeach
        </div>
    </main>

    <!-- Footer -->
    <footer class="flex justify-between items-center p-4 border-t bg-white">
        <a href="#" class="inline-flex items-center text-gray-600 hover:text-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M15 19l-7-7 7-7"/>
            </svg>
            Back
        </a>
        <!-- Floating Add Button -->
        <button class="bg-blue-600 text-white rounded-full p-3 shadow-lg hover:bg-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M12 4v16m8-8H4"/>
            </svg>
        </button>
    </footer>
</div>
@endsection
