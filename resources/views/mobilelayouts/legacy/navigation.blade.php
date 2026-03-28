<aside id="sidebar"
    class="fixed md:static inset-y-0 left-0 w-64 bg-[#F6F6F6] p-5 flex flex-col border-r shadow-md h-full transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-50">

    <!-- Logo Section -->
    <div class="flex flex-col items-center mb-6">
        <img src="{{ asset('assets/images/logo2.png') }}" alt="Logo" class="w-[150px] md:w-[200px] -mt-6 md:-mt-12">
        <span class="text-xl md:text-2xl font-bold text-[#000000] -mt-6 md:-mt-12">HealthWeb</span>
    </div>

   
    <!-- Navigation -->
<!-- Navigation -->
<nav class="space-y-2 flex-1 text-sm mt-16 md:mt-28 flex flex-col items-center">
    <a href="#" class="flex items-center gap-x-3 text-[#1f2b5b] font-medium p-2 rounded-md hover:bg-gray-100 transition w-full max-w-[180px]">
        <img src="{{ asset('assets/images/icons/dash.png') }}" alt="Dashboard" class="w-4 h-4">
        <span class="flex-1">Dashboard</span>
    </a>
    <a href="#" class="flex items-center gap-x-3 bg-[#0185FE] text-[#1f2b5b] bg-opacity-25 px-2 py-2 rounded-md text-sm w-full max-w-[180px]">
        <img src="{{ asset('assets/images/icons/record.png') }}" alt="Patients" class="w-7 h-7">
        <span class="flex-1">Records</span>
    </a>
    <a href="#" class="flex items-center gap-x-3 text-[#1f2b5b] font-medium p-2 rounded-md hover:bg-gray-100 transition w-full max-w-[180px]">
        <img src="{{ asset('assets/images/icons/appointmentic.png') }}" alt="Appointments" class="w-6 h-6">
        <span class="flex-1">Appointments</span>
    </a>
    <a href="#" class="flex items-center gap-x-3 text-[#1f2b5b] font-medium p-2 rounded-md hover:bg-gray-100 transition w-full max-w-[180px]">
        <img src="{{ asset('assets/images/icons/messages.png') }}" alt="Messages" class="w-6 h-6">
        <span class="flex-1">Messages</span>
    </a>
    <a href="#" class="flex items-center gap-x-3 text-[#1f2b5b] font-medium p-2 rounded-md hover:bg-gray-100 transition w-full max-w-[180px]">
        <img src="{{ asset('assets/images/icons/settingsic.png') }}" alt="Settings" class="w-6 h-6">
        <span class="flex-1">Settings</span>
    </a>
</nav>



    <!-- Logout Section -->
    <div class="mt-auto flex justify-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center gap-x-3 text-[#D42803] font-medium p-2 rounded-md hover:bg-gray-100 transition">
                <img src="{{ asset('assets/images/icons/logout.png') }}" alt="Logout" class="w-5 h-5">
                <span class="flex-1">Log Out</span>
            </button>
        </form>
    </div>
</aside>
