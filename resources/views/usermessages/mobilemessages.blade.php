@extends('mobilelayouts.app')

@push('styles')
<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="h-[calc(100vh-80px)] overflow-hidden">
<main x-data="messagesApp()" x-init="init()" class="flex flex-col md:flex-row h-full bg-white">

    <!-- Left Sidebar (User List / Search) -->
    <aside class="w-full md:w-1/3 lg:w-1/4 border-r p-2 sm:p-4 flex flex-col h-64 md:h-full overflow-hidden"
           :class="openChat ? 'hidden md:flex' : 'flex'">

        <!-- Search + New Conversation Button -->
        <div class="mb-3 flex-shrink-0 flex gap-1 sm:gap-2">
            <input x-model.debounce.300ms="searchQuery" @input="searchUsers"
                   type="text" placeholder="Search"
                   class="px-2 sm:px-3 h-9 sm:h-10 text-sm border rounded-md focus:outline-none focus:ring focus:ring-blue-300" style="width: calc(100% - 40px);">
            <button @click="showNewConversationModal = true" class="w-9 h-9 sm:w-10 sm:h-10 flex-shrink-0 bg-blue-900 text-white rounded-md hover:bg-blue-800 flex items-center justify-center" title="New Conversation">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </button>
        </div>

        <!-- Patients List -->
        <div class="flex-1 overflow-y-auto scrollbar-hide bg-blue-900 rounded-lg p-1 sm:p-2">
            <div class="space-y-2">
                <!-- Search results -->
                <template x-for="u in searchResults" :key="u.id">
                    <div @click="selectUser(u); openChat = true"
                         class="flex items-center bg-white p-2 sm:p-3 rounded-lg cursor-pointer hover:bg-gray-50">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0 flex items-center justify-center rounded-full mr-2 sm:mr-3"
                             :class="avatarBg(u.role_label, u.gender)">
                            <i class="text-sm sm:text-base" :class="[avatarIcon(u.role_label, u.gender), avatarColor(u.role_label, u.gender)]"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-xs sm:text-sm truncate" x-text="u.username"></p>
                            <p class="text-[10px] sm:text-xs text-gray-500 truncate" x-text="u.role_label"></p>
                        </div>
                        <span class="text-[10px] sm:text-xs text-gray-400 ml-1 flex-shrink-0" x-show="u.id == selectedRecipientId">Selected</span>
                    </div>
                </template>

                <!-- Conversation list -->
                <template x-for="c in conversations" :key="c.id">
                    <div @click="selectConversation(c); openChat = true" class="flex items-start bg-white p-2 sm:p-3 rounded-lg cursor-pointer hover:bg-gray-50">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0 flex items-center justify-center rounded-full mr-2 sm:mr-3"
                             :class="avatarBg(c.participants?.[0]?.role_label, c.participants?.[0]?.gender)">
                            <i class="text-sm sm:text-base" :class="[avatarIcon(c.participants?.[0]?.role_label, c.participants?.[0]?.gender), avatarColor(c.participants?.[0]?.role_label, c.participants?.[0]?.gender)]"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-baseline justify-between gap-2">
                                <p class="font-semibold text-xs sm:text-sm truncate flex-1" x-text="c.participants && c.participants.length > 0 ? c.participants.map(p=>p.username).join(', ') : (c.subject || 'Conversation')"></p>
                                <span class="text-[10px] text-gray-400 flex-shrink-0" x-text="c.last_at ? new Date(c.last_at).toLocaleDateString('en-US', {month: 'short', day: 'numeric'}) + ' | ' + new Date(c.last_at).toLocaleTimeString('en-US', {hour: 'numeric', minute: '2-digit', hour12: true}) : ''"></span>
                            </div>
                            <p class="text-[10px] sm:text-xs text-gray-500 truncate" x-text="c.participants && c.participants.length > 0 ? c.participants.map(p=>p.role_label).join(', ') : ''"></p>
                            <p class="text-[10px] sm:text-xs text-gray-600 truncate mt-0.5" x-text="c.last_message || 'No messages yet'"></p>
                        </div>
                    </div>
                </template>

                <!-- Empty state when no search and no conversations -->
                <div x-show="searchResults.length === 0 && conversations.length === 0" class="text-center text-gray-300 py-4">
                    Search for a user to start a conversation
                </div>
            </div>
        </div>
    </aside>

    <!-- Right Chat Section -->
    <section class="flex-1 flex flex-col h-full overflow-hidden"
             :class="openChat ? 'flex' : 'hidden md:flex'">

        <!-- Header -->
        <header class="flex items-center justify-between border-b px-2 sm:px-4 py-2 sm:py-3 flex-shrink-0">
            <div class="flex items-center min-w-0 flex-1">
                <!-- Back button (phone only) -->
                <button @click="openChat = false" class="md:hidden mr-2 flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <div class="w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0 flex items-center justify-center rounded-full mr-2"
                     :class="selectedRecipient ? avatarBg(selectedRecipient.role_label, selectedRecipient.gender) : 'bg-gray-200'">
                    <i class="text-sm sm:text-base"
                       :class="selectedRecipient ? [avatarIcon(selectedRecipient.role_label, selectedRecipient.gender), avatarColor(selectedRecipient.role_label, selectedRecipient.gender)] : 'fa-solid fa-person text-gray-400'"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="font-semibold text-xs sm:text-sm md:text-base truncate" x-text="selectedRecipient ? selectedRecipient.username : 'No recipient'"></div>
                    <div class="text-[10px] sm:text-xs text-gray-500 truncate" x-text="selectedRecipient ? selectedRecipient.role_label : ''"></div>
                </div>
            </div>
            <button class="p-1 sm:p-2 flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="5" r="1.5"/>
                    <circle cx="12" cy="12" r="1.5"/>
                    <circle cx="12" cy="19" r="1.5"/>
                </svg>
            </button>
        </header>

        <!-- Messages (Dynamic) -->
        <div class="flex-1 overflow-y-auto scrollbar-hide p-2 sm:p-4 space-y-4 min-h-0" x-show="selectedConversation || selectedRecipientId">
            <template x-for="m in messages" :key="m.id">
                <div :class="{'items-end': m.sender_id == authUserId, 'items-start': m.sender_id != authUserId }" class="flex flex-col">
                    <div class="px-3 sm:px-4 py-2 rounded-lg max-w-[85%] sm:max-w-xs text-xs sm:text-sm" :class="m.sender_id == authUserId ? 'bg-white border' : 'bg-blue-500 text-white'" x-text="m.body"></div>
                    <span class="text-[10px] sm:text-xs text-gray-400 mt-1" x-text="m.created_at"></span>
                </div>
            </template>
            <div x-show="messages.length === 0 && selectedRecipientId" class="flex items-center justify-center h-full text-gray-400 text-xs sm:text-sm">
                No messages yet. Start the conversation!
            </div>
        </div>

        <!-- Placeholder when no recipient selected -->
        <div class="flex-1 overflow-y-auto scrollbar-hide p-2 sm:p-4 space-y-4 min-h-0" x-show="!selectedConversation && !selectedRecipientId">
            <div class="flex items-center justify-center h-full text-gray-400 text-xs sm:text-sm">Please click a recipient</div>
        </div>

        <!-- Input Box -->
        <footer class="border-t p-2 sm:p-3 flex items-center gap-2 flex-shrink-0">
            <input x-model="messageText" type="text" placeholder="Type a message here"
                @keydown.enter.prevent="sendMessage"
                class="flex-1 px-3 py-2 text-xs sm:text-sm border rounded-full focus:outline-none focus:ring focus:ring-blue-300">
            <button @click="sendMessage" class="bg-blue-900 text-white p-2 rounded-full flex-shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2 21l21-9L2 3v7l15 2-15 2v7z"/>
                </svg>
            </button>
        </footer>
    </section>

    <!-- New Conversation Modal -->
    <div x-show="showNewConversationModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.self="showNewConversationModal = false">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 max-h-[80vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b">
                <h3 class="text-lg font-semibold">New Conversation</h3>
                <button @click="showNewConversationModal = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4 flex-1 overflow-hidden flex flex-col">
                <!-- Search Users -->
                <input x-model.debounce.300ms="modalSearchQuery" @input="searchUsersForModal" type="text" placeholder="Search users..."
                       class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300 mb-3">
                
                <!-- User List -->
                <div class="flex-1 overflow-y-auto scrollbar-hide space-y-2">
                    <template x-for="u in modalSearchResults" :key="u.id">
                        <div @click="startConversationWith(u)" class="flex items-center p-3 rounded-lg cursor-pointer hover:bg-gray-100 border">
                            <div class="w-10 h-10 flex items-center justify-center rounded-full mr-3"
                                 :class="avatarBg(u.role_label, u.gender)">
                                <i class="text-base" :class="[avatarIcon(u.role_label, u.gender), avatarColor(u.role_label, u.gender)]"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-sm" x-text="u.username"></p>
                                <p class="text-xs text-gray-500" x-text="u.role_label"></p>
                            </div>
                        </div>
                    </template>
                    <div x-show="modalSearchResults.length === 0 && modalSearchQuery.length > 0" class="text-center text-gray-400 py-4">
                        No users found
                    </div>
                    <div x-show="modalSearchQuery.length === 0" class="text-center text-gray-400 py-4">
                        Type to search for users
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</div>
@endsection

@include('messages._scripts')
