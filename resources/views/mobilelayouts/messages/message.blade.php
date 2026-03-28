{{-- resources/views/messages.blade.php --}}
@extends("mobilelayouts.app")

@push("styles")
  <style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    /* Messages page: remove main padding and disable its scroll so internal panels scroll instead */
    body:has(#messages-root) > div > div > main {
      padding: 0 !important;
      overflow: hidden !important;
    }
  </style>
@endpush

@section("content")
  <div id="messages-root" class="h-full flex flex-col overflow-hidden">
    <main class="flex flex-col md:flex-row h-full bg-white" x-data="adminMessagesApp()" x-init="init()">

      <!-- Left Sidebar -->
      <aside class="w-full md:w-1/3 lg:w-1/4 border-r p-2 sm:p-4 flex-col h-full overflow-hidden"
        :class="mobilePanel === 'list' ? 'flex' : 'hidden md:flex'">
        <!-- Header -->
        <div class="mb-3 flex-shrink-0">
          <p class="text-sm font-semibold text-blue-950">Conversations</p>
        </div>

        <!-- Staff contacts list (always shows all doctors + secretaries) -->
        <div class="flex-1 overflow-y-auto scrollbar-hide bg-blue-950 rounded-lg p-1 sm:p-2">
          <div class="space-y-2">
            <template x-for="s in staffList" :key="s.id">
              <div class="flex items-start p-2 sm:p-3 rounded-lg cursor-pointer transition-colors"
                :class="selectedRecipientId == s.id ? 'bg-blue-100' : 'bg-white hover:bg-gray-50'"
                @click="selectContact(s)">
                <div
                  class="w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0 flex items-center justify-center rounded-full mr-2 sm:mr-3"
                  :class="avatarBg(s.role_label, s.gender)">
                  <i class="text-sm sm:text-base" :class="[avatarIcon(s.role_label, s.gender), avatarColor(s.role_label, s.gender)]"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex items-baseline justify-between gap-2">
                    <p class="font-semibold text-xs sm:text-sm truncate flex-1" x-text="s.display_name"></p>
                    <span class="text-[10px] text-gray-400 flex-shrink-0"
                      x-text="s.conversation?.last_at ? new Date(s.conversation.last_at).toLocaleDateString('en-US', {month: 'short', day: 'numeric'}) + ' | ' + new Date(s.conversation.last_at).toLocaleTimeString('en-US', {hour: 'numeric', minute: '2-digit', hour12: true}) : ''"></span>
                  </div>
                  <p class="text-[10px] sm:text-xs text-gray-500 truncate" x-text="s.role_label"></p>
                  <p class="text-[10px] sm:text-xs text-gray-600 truncate mt-0.5"
                    x-text="s.conversation?.last_message ?? ''"></p>
                </div>
              </div>
            </template>
            <div class="text-center text-blue-300 text-xs py-4" x-show="staffList.length === 0">
              No staff available
            </div>
          </div>
        </div>
      </aside>

      <!-- Right Chat Section -->
      <section class="flex-1 flex-col h-full overflow-hidden"
        :class="mobilePanel === 'chat' ? 'flex' : 'hidden md:flex'">
        <!-- Header -->
        <header class="flex items-center justify-between border-b px-2 sm:px-4 py-2 sm:py-3 flex-shrink-0">
          <div class="flex items-center min-w-0 flex-1">
            <button @click="mobilePanel = 'list'"
              class="md:hidden flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-md hover:bg-gray-100 text-gray-600 mr-1">
              <i class="fa-solid fa-arrow-left fa-sm"></i>
            </button>
            <div
              class="w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0 flex items-center justify-center rounded-full mr-2"
              :class="selectedRecipient ? avatarBg(selectedRecipient.role_label, selectedRecipient.gender) : 'bg-gray-200'">
              <i class="text-sm sm:text-base"
                :class="selectedRecipient ? [avatarIcon(selectedRecipient.role_label, selectedRecipient.gender), avatarColor(selectedRecipient.role_label, selectedRecipient.gender)] : 'fa-solid fa-person text-gray-400'"></i>
            </div>
            <div class="min-w-0 flex-1">
              <div class="font-semibold text-xs sm:text-sm md:text-base truncate"
                x-text="selectedRecipient ? selectedRecipient.display_name : 'No recipient'"></div>
              <div class="text-[10px] sm:text-xs text-gray-500 truncate"
                x-text="selectedRecipient ? selectedRecipient.role_label : ''"></div>
            </div>
          </div>
          <div class="relative flex-shrink-0" @click.outside="showDotsMenu = false">
            <button class="p-1 sm:p-2" @click="showDotsMenu = !showDotsMenu" title="More options">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="5" r="1.5" />
                <circle cx="12" cy="12" r="1.5" />
                <circle cx="12" cy="19" r="1.5" />
              </svg>
            </button>
            <div class="absolute right-0 top-full mt-1 bg-white border rounded-md shadow-lg z-20 w-52"
              x-show="showDotsMenu" x-cloak>
              <button
                class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50 text-gray-700"
                @click="showSearch = !showSearch; conversationSearch = ''; showDotsMenu = false">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                </svg>
                Search in conversation
              </button>
              <button
                class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50 text-red-600"
                @click="confirmDeleteConversation(); showDotsMenu = false">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                </svg>
                Delete conversation
              </button>
            </div>
          </div>
        </header>

        <!-- Search bar -->
        <div class="border-b px-2 sm:px-4 py-2 flex-shrink-0 bg-gray-50" x-show="showSearch" x-cloak>
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
            </svg>
            <input
              class="flex-1 px-2 py-1 text-xs sm:text-sm bg-transparent focus:outline-none"
              x-model="conversationSearch" type="text" placeholder="Search messages..."
              x-ref="searchInput"
              @keydown.escape="showSearch = false; conversationSearch = ''"
              x-effect="if (showSearch) $nextTick(() => $refs.searchInput?.focus())">
            <span class="text-[10px] text-gray-400 flex-shrink-0 whitespace-nowrap"
              x-show="conversationSearch.trim()" x-text="messageMatchCount"></span>
            <button class="text-gray-400 hover:text-gray-600 flex-shrink-0"
              @click="showSearch = false; conversationSearch = ''">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Messages (Dynamic) -->
        <div class="flex-1 overflow-y-auto scrollbar-hide p-2 sm:p-4 space-y-3 sm:space-y-4 min-h-0"
          x-ref="messagesContainer"
          x-show="selectedConversation || selectedRecipientId">
          <template x-for="m in filteredMessages" :key="m.id">
            <div class="flex flex-col"
              :class="{ 'items-end': m.sender_id == authUserId, 'items-start': m.sender_id != authUserId }">
              <div class="px-3 sm:px-4 py-2 rounded-lg max-w-[85%] sm:max-w-xs text-xs sm:text-sm"
                :class="m.sender_id == authUserId ? 'bg-white border' : 'bg-blue-950 text-white'" x-text="m.body"></div>
              <span class="text-[10px] sm:text-xs text-gray-400 mt-1" x-text="m.created_at"></span>
            </div>
          </template>
          <div class="flex items-center justify-center h-full text-gray-400 text-xs sm:text-sm"
            x-show="messages.length === 0 && selectedRecipientId">
            No messages yet. Start the conversation!
          </div>
          <div class="flex items-center justify-center h-full text-gray-400 text-xs sm:text-sm"
            x-show="messages.length > 0 && filteredMessages.length === 0">
            No messages match your search.
          </div>
        </div>

        <!-- Placeholder when no recipient selected -->
        <div class="flex-1 overflow-y-auto scrollbar-hide p-2 sm:p-4 space-y-4 min-h-0"
          x-show="!selectedConversation && !selectedRecipientId">
          <div class="flex items-center justify-center h-full text-gray-400 text-xs sm:text-sm">Please click a recipient
          </div>
        </div>

        <!-- Input Box -->
        <footer class="border-t p-2 sm:p-3 flex items-center gap-2 flex-shrink-0">
          <input
            class="flex-1 px-3 py-2 text-xs sm:text-sm border rounded-md focus:outline-none focus:ring focus:ring-blue-300"
            x-model="messageText" type="text" placeholder="Type a message here"
            @keydown.enter.prevent="sendMessage">
          <button class="bg-blue-950 hover:bg-blue-900 text-white p-2 rounded-md flex-shrink-0 transition-colors" @click="sendMessage">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M2 21l21-9L2 3v7l15 2-15 2v7z" />
            </svg>
          </button>
        </footer>
      </section>

      {{-- Delete conversation confirmation modal --}}
      <x-modal-garic id="delete-conversation-modal" title="Delete Conversation" maxWidth="max-w-sm">
        <p class="text-sm text-gray-600 mb-5">Are you sure you want to delete this conversation? This will remove all your messages and cannot be undone.</p>
        <div class="flex justify-end gap-2">
          <div class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 cursor-pointer"
            data-modal-close="delete-conversation-modal">
            Cancel
          </div>
          <button class="px-4 py-2 text-sm bg-red-600 text-white rounded-md hover:bg-red-700"
            @click="deleteConversationMessages(selectedConversation?.id); window.Modal?.close('delete-conversation-modal')">
            Delete
          </button>
        </div>
      </x-modal-garic>
    </main>
  </div>
@endsection

@push("scripts")
  @vite(["resources/js/components/modals/modal.js"])
@endpush
@include("mobilelayouts.messages._scripts")
