<x-guest-layout>
  <div x-data="confirmPasswordForm()" class="flex flex-col gap-6">

    {{-- Header --}}
    <div>
      <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mb-4">
        <i class="fa-solid fa-shield-halved text-blue-950 text-sm"></i>
      </div>
      <h2 class="text-2xl font-bold text-blue-950">Confirm your password</h2>
      <p class="text-sm text-gray-500 mt-1">
        This is a secure area. Please re-enter your password to continue.
      </p>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('password.confirm') }}" @submit.prevent="submit($el)" novalidate class="flex flex-col gap-4">
      @csrf

      {{-- Password --}}
      <div class="flex flex-col gap-1">
        <label for="password" class="text-sm font-medium text-blue-950">Password</label>
        <div class="relative">
          <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
          <input
            id="password" :type="showPwd ? 'text' : 'password'" name="password"
            placeholder="••••••••"
            autocomplete="current-password" autofocus
            x-model="password"
            @blur="touch()"
            class="w-full pl-10 pr-10 py-3 text-sm border rounded-md focus:outline-none focus:ring-2 transition-colors
              {{ $errors->has('password') ? 'border-red-400 focus:ring-red-300 bg-red-50' : 'border-gray-300 focus:ring-blue-400 focus:border-blue-400' }}"
          >
          <button type="button" @click="showPwd = !showPwd"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
            <i :class="showPwd ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" class="text-sm"></i>
          </button>
        </div>
        @error('password')
          <p class="text-xs text-red-600 flex items-center gap-1">
            <i class="fa-solid fa-circle-exclamation fa-xs"></i> {{ $message }}
          </p>
        @else
          <p class="text-xs text-red-600 flex items-center gap-1" x-show="error" x-cloak>
            <i class="fa-solid fa-circle-exclamation fa-xs"></i> <span x-text="error"></span>
          </p>
        @enderror
      </div>

      {{-- Submit --}}
      <button type="submit"
        class="w-full py-3 bg-blue-950 hover:bg-blue-900 text-white text-sm font-semibold rounded-md transition-colors
          disabled:opacity-60 disabled:cursor-not-allowed"
        :disabled="loading">
        <span x-show="!loading">Confirm</span>
        <span x-show="loading" x-cloak class="flex items-center justify-center gap-2">
          <i class="fa-solid fa-circle-notch fa-spin fa-sm"></i> Confirming…
        </span>
      </button>
    </form>

  </div>

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('confirmPasswordForm', () => ({
        password: '',
        showPwd:  false,
        loading:  false,
        error:    '',

        touch() {
          this.error = this.password ? '' : 'Password is required.';
        },

        submit(form) {
          this.touch();
          if (this.error) return;
          this.loading = true;
          form.submit();
        },
      }));
    });
  </script>
</x-guest-layout>
