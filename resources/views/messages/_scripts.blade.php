<script>
  function messagesApp() {
    return {
      searchQuery: '',
      searchResults: [],
      selectedRecipientId: null,
      selectedRecipient: null,
      selectedConversation: null,
      messages: [],
      messageText: '',
      conversations: [],
      hiddenConversationIds: [],
      showDotsMenu: false,
      showSearch: false,
      conversationSearch: '',
      authUserId: {{ auth()->id() }},

      // New conversation modal
      showNewConversationModal: false,
      modalSearchQuery: '',
      modalSearchResults: [],

      // Polling intervals
      messagePollingInterval: null,
      conversationPollingInterval: null,

      init() {
        this.loadConversations();
        this.startPolling();
      },

      startPolling() {
        this.messagePollingInterval = setInterval(() => {
          if (this.selectedConversation?.id) {
            this.loadMessages(this.selectedConversation.id, true);
          }
        }, 2000);

        this.conversationPollingInterval = setInterval(() => {
          this.loadConversations(true);
        }, 5000);
      },

      stopPolling() {
        clearInterval(this.messagePollingInterval);
        clearInterval(this.conversationPollingInterval);
      },

      get filteredConversations() {
        const q = this.searchQuery.trim().toLowerCase();
        if (!q) return this.conversations;
        return this.conversations.filter(c =>
          c.participants?.some(p => p.display_name?.toLowerCase().includes(q) || p.username?.toLowerCase().includes(q)) ||
          c.last_message?.toLowerCase().includes(q)
        );
      },

      async searchUsers() {
        const q = this.searchQuery.trim();
        if (q.length < 1) {
          this.searchResults = [];
          return;
        }
        try {
          const res = await fetch('/api/users/search?q=' + encodeURIComponent(q));
          const json = await res.json();
          const loadedIds = new Set(this.conversations.flatMap(c => (c.participants || []).map(p => p.id)));
          this.searchResults = json.data.filter(u => !loadedIds.has(u.id));
        } catch (e) {
          console.error('User search error:', e);
        }
      },

      async searchUsersForModal() {
        if (this.modalSearchQuery.length < 1) {
          this.modalSearchResults = [];
          return;
        }
        try {
          const res = await fetch('/api/users/search?q=' + encodeURIComponent(this.modalSearchQuery));
          const json = await res.json();
          this.modalSearchResults = json.data;
        } catch (e) {
          console.error('Modal user search error:', e);
        }
      },

      async startConversationWith(u) {
        this.selectedRecipientId = u.id;
        this.selectedRecipient = u;
        const existing = this.conversations.find(c => c.participants.some(p => p.id == u.id));
        if (existing) {
          await this.selectConversation(existing);
        } else {
          this.selectedConversation = { id: null, subject: null };
          this.messages = [];
        }
        this.showNewConversationModal = false;
        this.modalSearchQuery = '';
        this.modalSearchResults = [];
      },

      async selectUser(u) {
        this.selectedRecipientId = u.id;
        this.selectedRecipient = u;
        const conv = this.conversations.find(c => c.participants.some(p => p.id == u.id));
        if (conv) {
          await this.selectConversation(conv);
        } else {
          this.selectedConversation = { id: null, subject: null };
          this.messages = [];
        }
      },

      async loadConversations(silent = false) {
        try {
          const res = await fetch('/api/conversations');
          const json = await res.json();
          this.conversations = json.data.filter(c => !this.hiddenConversationIds.includes(c.id));
        } catch (error) {
          if (!silent) console.error('Error loading conversations:', error);
        }
      },

      get filteredMessages() {
        const q = this.conversationSearch.trim().toLowerCase();
        if (!q) return this.messages;
        return this.messages.filter(m => m.body?.toLowerCase().includes(q));
      },

      get messageMatchCount() {
        if (!this.conversationSearch.trim()) return null;
        const n = this.filteredMessages.length;
        return n === 0 ? 'No results' : `${n} result${n !== 1 ? 's' : ''}`;
      },

      async selectConversation(conv) {
        if (conv?.id === this.selectedConversation?.id) return;

        // Clear pending new-conversation search state so the staged entry disappears
        this.searchQuery = '';
        this.searchResults = [];

        this.showSearch = false;
        this.conversationSearch = '';
        this.selectedConversation = conv;
        const other = conv.participants.find(p => p.id !== this.authUserId) || conv.participants[0];
        this.selectedRecipientId = other?.id ?? null;
        this.selectedRecipient = other ?? null;
        await this.loadMessages(conv.id);
      },

      confirmDeleteConversation() {
        if (!this.selectedConversation?.id) return toastr?.error?.('No conversation selected');
        window.Modal?.open('delete-conversation-modal');
      },

      async deleteConversationMessages(conversationId) {
        try {
          const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          const res = await fetch('/api/conversations/' + conversationId + '/messages', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': token }
          });
          const json = await res.json();
          if (res.ok) {
            this.hiddenConversationIds.push(conversationId);
            this.conversations = this.conversations.filter(c => c.id !== conversationId);
            this.messages = [];
            this.selectedConversation = null;
            this.selectedRecipientId = null;
            this.selectedRecipient = null;
            toastr?.success?.('Conversation removed');
          } else {
            toastr?.error?.(json.message || 'Failed to delete messages');
          }
        } catch (e) {
          console.error('Error deleting messages:', e);
          toastr?.error?.('Failed to delete messages');
        }
      },

      scrollToBottom() {
        this.$nextTick(() => {
          const el = this.$refs.messagesContainer;
          if (el) el.scrollTop = el.scrollHeight;
        });
      },

      async loadMessages(conversationId, silent = false) {
        try {
          const res = await fetch('/api/conversations/' + conversationId + '/messages');
          const json = await res.json();
          const formatted = json.data.map(m => ({
            ...m,
            created_at: this.formatMessageDate(m.created_at)
          }));

          if (!silent || this.messages.length !== formatted.length) {
            const growing = formatted.length > this.messages.length;
            this.messages = formatted;
            if (!silent || growing) this.scrollToBottom();
          }
        } catch (error) {
          if (!silent) console.error('Error loading messages:', error);
        }
      },

      formatMessageDate(dateString) {
        const date = new Date(dateString);
        const month = date.toLocaleDateString('en-US', { month: 'short' });
        const day = date.getDate();
        const time = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
        return `${month} ${day} | ${time}`;
      },

      avatarBg(roleLabel, gender) {
        if (roleLabel === 'Doctor') return 'bg-indigo-100';
        if (roleLabel === 'Secretary') return 'bg-amber-100';
        return (gender ?? '').toLowerCase() === 'female' ? 'bg-pink-100' : 'bg-blue-100';
      },

      avatarColor(roleLabel, gender) {
        if (roleLabel === 'Doctor') return 'text-indigo-700';
        if (roleLabel === 'Secretary') return 'text-amber-700';
        return (gender ?? '').toLowerCase() === 'female' ? 'text-pink-700' : 'text-blue-700';
      },

      avatarIcon(roleLabel, gender) {
        if (roleLabel === 'Doctor') return 'fa-solid fa-user-doctor';
        if (roleLabel === 'Secretary') return 'fa-solid fa-user-nurse';
        return (gender ?? '').toLowerCase() === 'female' ? 'fa-solid fa-person-dress' : 'fa-solid fa-person';
      },

      async sendMessage() {
        if (!this.messageText.trim() || !this.selectedRecipientId) return;

        const payload = {
          recipient_id: this.selectedRecipientId,
          body: this.messageText.trim(),
          conversation_id: this.selectedConversation?.id ?? null,
        };

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
          const res = await fetch('/messages', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token
            },
            body: JSON.stringify(payload)
          });

          const json = await res.json();

          if (res.ok) {
            this.messageText = '';
            this.messages.push({
              ...json.data,
              created_at: this.formatMessageDate(json.data.created_at)
            });

            // Promote staged conversation and clear search state
            if (json.data.conversation_id && !this.selectedConversation?.id) {
              this.selectedConversation = { id: json.data.conversation_id, subject: null };
              this.searchQuery = '';
              this.searchResults = [];
            }

            this.loadConversations();
            this.scrollToBottom();
          } else {
            toastr?.error?.(json.message || 'Failed to send message');
          }
        } catch (error) {
          console.error('Error sending message:', error);
          toastr?.error?.('Failed to send message. Please try again.');
        }
      },
    }
  }

  // Alias for admin/doctor view
  function adminMessagesApp() {
    return messagesApp();
  }
</script>
