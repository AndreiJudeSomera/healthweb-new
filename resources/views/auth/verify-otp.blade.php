<x-guest-layout>
  <div x-data="otpForm()" class="flex flex-col gap-6">

    {{-- Header --}}
    <div>
      <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mb-4">
        <i class="fa-solid fa-envelope-circle-check text-blue-950 text-sm"></i>
      </div>
      <h2 class="text-2xl font-bold text-blue-950">Check your email</h2>
      <p class="text-sm text-gray-500 mt-1">
        We sent a 6-digit code to <span class="font-medium text-blue-950">{{ $email }}</span>. Enter it below to continue.
      </p>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('password.otp.verify') }}" @submit.prevent="submit($el)" novalidate class="flex flex-col gap-4">
      @csrf
      <input type="hidden" name="email" value="{{ $email }}">

      {{-- OTP input --}}
      <div class="flex flex-col gap-1">
        <label for="otp" class="text-sm font-medium text-blue-950">One-time password</label>
        <div class="relative">
          <i class="fa-solid fa-key absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
          <input
            id="otp" type="text" name="otp" inputmode="numeric" maxlength="6"
            placeholder="••••••"
            autocomplete="one-time-code" autofocus
            x-model="otp"
            @blur="touch()"
            @input="otp = otp.replace(/\D/g, '').slice(0, 6)"
            class="w-full pl-10 pr-4 py-3 text-sm border rounded-md focus:outline-none focus:ring-2 transition-colors tracking-[0.35em] font-mono
              {{ $errors->has('otp') ? 'border-red-400 focus:ring-red-300 bg-red-50' : 'border-gray-300 focus:ring-blue-400 focus:border-blue-400' }}"
          >
        </div>
        @error('otp')
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
        <span x-show="!loading">Verify OTP</span>
        <span x-show="loading" x-cloak class="flex items-center justify-center gap-2">
          <i class="fa-solid fa-circle-notch fa-spin fa-sm"></i> Verifying...
        </span>
      </button>
    </form>

    {{-- Back link --}}
    <p class="text-center text-sm text-gray-500">
      Didn't receive a code?
      <a href="{{ route('password.request') }}" class="text-blue-700 font-medium hover:underline ml-1">Try again</a>
    </p>

  </div>

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('otpForm', () => ({
        otp:     '',
        loading: false,
        error:   '',

        touch() {
          if (!this.otp.trim()) {
            this.error = 'OTP is required.';
          } else if (!/^\d{6}$/.test(this.otp.trim())) {
            this.error = 'Enter the 6-digit code from your email.';
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
