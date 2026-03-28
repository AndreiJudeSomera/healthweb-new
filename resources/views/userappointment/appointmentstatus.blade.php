@extends('mobilelayouts.app')

@section('content')
<div class="flex flex-col min-h-screen bg-white">
    <!-- Top Bar -->
    

        <!-- Title -->
        <h1 class="text-base font-semibold text-gray-800"> Appointment</h1>

        
    <!-- Main Content -->
      <main class="flex flex-col items-center justify-center flex-1 text-center p-6">
        <h1 class="text-lg font-bold mb-1">Appointment</h1>
        <p class="text-sm text-gray-500 mb-6">Wait for further response about your appointments</p>

        <!-- Icon -->
        <div class="mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32 text-blue-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 2v4M16 2v4M8 13l3 3 5-5"></path>
            </svg>
        </div>

        <!-- Status -->
        <p class="text-lg font-bold mb-6">PENDING</p>

        <!-- Back Button -->
        <a href="{{ route('dashboard') }}" 
           class="px-6 py-2 border border-blue-900 text-blue-900 rounded-lg hover:bg-blue-900 hover:text-white transition">
           Back To Dashboard
        </a>
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

@endsection
