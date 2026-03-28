<x-guest-layout>
  <div x-data="forgotForm()" class="flex flex-col gap-6">

    {{-- Header --}}
    <div>
      <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mb-4">
        <i class="fa-solid fa-lock text-blue-950 text-sm"></i>
      </div>
      <h2 class="text-2xl font-bold text-blue-950">Forgot your password?</h2>
      <p class="text-sm text-gray-500 mt-1">
        Enter your registered email and we'll send you a one-time password to reset it.
      </p>
    </div>

    {{-- Session status --}}
    @if (session('status'))
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-md flex items-center gap-2">
        <i class="fa-solid fa-circle-check fa-sm"></i>
        {{ session('status') }}
      </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('password.otp.send') }}" @submit.prevent="submit($el)" novalidate class="flex flex-col gap-4">
      @csrf

      {{-- Email --}}
      <div class="flex flex-col gap-1">
        <label for="email" class="text-sm font-medium text-blue-950">Email address</label>
        <div class="relative">
          <i class="fa-regular fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
          <input
            id="email" type="email" name="email"
            value="{{ old('email') }}"
            placeholder="joseprotaciorizal@gmail.com"
            autocomplete="email" autofocus
            x-model="email"
            @blur="touch()"
            class="w-full pl-10 pr-4 py-3 text-sm border rounded-md focus:outline-none focus:ring-2 transition-colors
              {{ $errors->has('email') ? 'border-red-400 focus:ring-red-300 bg-red-50' : 'border-gray-300 focus:ring-blue-400 focus:border-blue-400' }}"
          >
        </div>
        @error('email')
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
        <span x-show="!loading">Send OTP</span>
        <span x-show="loading" x-cloak class="flex items-center justify-center gap-2">
          <i class="fa-solid fa-circle-notch fa-spin fa-sm"></i> Sending…
        </span>
      </button>
    </form>

    {{-- Back to login --}}
    <p class="text-center text-sm text-gray-500">
      Remember your password?
      <a href="{{ route('login') }}" class="text-blue-700 font-medium hover:underline ml-1">Sign in</a>
    </p>

  </div>

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('forgotForm', () => ({
        email:   '{{ old("email") }}',
        loading: false,
        error:   '',

        touch() {
          if (!this.email.trim()) {
            this.error = 'Email is required.';
          } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email.trim())) {
            this.error = 'Enter a valid email address.';
          } else {
            this.error = '';
          }
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
