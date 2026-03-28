<x-guest-layout>
  <div x-data="changePasswordForm()" class="flex flex-col gap-6">

    {{-- Header --}}
    <div>
      <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mb-4">
        <i class="fa-solid fa-key text-blue-950 text-sm"></i>
      </div>
      <h2 class="text-2xl font-bold text-blue-950">Set new password</h2>
      <p class="text-sm text-gray-500 mt-1">Choose a strong password for your account.</p>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('password.change') }}" @submit.prevent="submit($el)" novalidate class="flex flex-col gap-4">
      @csrf
      <input type="hidden" name="email" value="{{ $email }}">

      {{-- New password --}}
      <div class="flex flex-col gap-1">
        <label for="password" class="text-sm font-medium text-blue-950">New password</label>
        <div class="relative">
          <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
          <input
            id="password" :type="showPwd ? 'text' : 'password'" name="password"
            placeholder="••••••••"
            autocomplete="new-password" autofocus
            x-model="password"
            @blur="touch('password')"
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
          <p class="text-xs text-red-600 flex items-center gap-1" x-show="errors.password" x-cloak>
            <i class="fa-solid fa-circle-exclamation fa-xs"></i> <span x-text="errors.password"></span>
          </p>
        @enderror
      </div>

      {{-- Confirm password --}}
      <div class="flex flex-col gap-1">
        <label for="password_confirmation" class="text-sm font-medium text-blue-950">Confirm new password</label>
        <div class="relative">
          <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
          <input
            id="password_confirmation" :type="showConfirm ? 'text' : 'password'" name="password_confirmation"
            placeholder="••••••••"
            autocomplete="new-password"
            x-model="confirmation"
            @blur="touch('confirmation')"
            class="w-full pl-10 pr-10 py-3 text-sm border rounded-md focus:outline-none focus:ring-2 transition-colors
              border-gray-300 focus:ring-blue-400 focus:border-blue-400"
          >
          <button type="button" @click="showConfirm = !showConfirm"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
            <i :class="showConfirm ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" class="text-sm"></i>
          </button>
        </div>
        <p class="text-xs text-red-600 flex items-center gap-1" x-show="errors.confirmation" x-cloak>
          <i class="fa-solid fa-circle-exclamation fa-xs"></i> <span x-text="errors.confirmation"></span>
        </p>
      </div>

      {{-- Submit --}}
      <button type="submit"
        class="w-full py-3 bg-blue-950 hover:bg-blue-900 text-white text-sm font-semibold rounded-md transition-colors mt-1
          disabled:opacity-60 disabled:cursor-not-allowed"
        :disabled="loading">
        <span x-show="!loading">Update Password</span>
        <span x-show="loading" x-cloak class="flex items-center justify-center gap-2">
          <i class="fa-solid fa-circle-notch fa-spin fa-sm"></i> Updating…
        </span>
      </button>
    </form>

    {{-- Back to login --}}
    <p class="text-center text-sm text-gray-500">
      <a href="{{ route('login') }}" class="text-blue-700 font-medium hover:underline">Back to sign in</a>
    </p>

  </div>

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('changePasswordForm', () => ({
        password:     '',
        confirmation: '',
        showPwd:      false,
        showConfirm:  false,
        loading:      false,
        errors:       { password: '', confirmation: '' },

        rules: {
          password(v) {
            if (!v) return 'Password is required.';
            if (v.length < 8) return 'Password must be at least 8 characters.';
            return '';
          },
          confirmation(v, pwd) {
            if (!v) return 'Please confirm your password.';
            if (v !== pwd) return 'Passwords do not match.';
            return '';
          },
        },

        touch(field) {
          if (field === 'confirmation') {
            this.errors.confirmation = this.rules.confirmation(this.confirmation, this.password);
          } else {
            this.errors[field] = this.rules[field](this[field]);
          }
        },

        submit(form) {
          this.errors.password     = this.rules.password(this.password);
          this.errors.confirmation = this.rules.confirmation(this.confirmation, this.password);
          if (this.errors.password || this.errors.confirmation) return;
          this.loading = true;
          form.submit();
        },
      }));
    });
  </script>
</x-guest-layout>
