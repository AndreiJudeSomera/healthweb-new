<x-guest-layout>
  <div x-data="loginForm()" class="flex flex-col gap-6">

    {{-- Header --}}
    <div>
      <h2 class="text-2xl font-bold text-blue-950">Welcome back</h2>
      <p class="text-sm text-gray-500 mt-1">Sign in to your HealthWeb account</p>
    </div>

    {{-- Session status --}}
    @if (session('status'))
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-md flex items-center gap-2">
        <i class="fa-solid fa-circle-check fa-sm"></i>
        {{ session('status') }}
      </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('login') }}" @submit.prevent="submit($el)" novalidate class="flex flex-col gap-4">
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
            @blur="touch('email')"
            class="w-full pl-10 pr-4 py-3 text-sm border rounded-md focus:outline-none focus:ring-2 transition-colors
              {{ $errors->has('email') ? 'border-red-400 focus:ring-red-300 bg-red-50' : 'border-gray-300 focus:ring-blue-400 focus:border-blue-400' }}"
          >
        </div>
        @error('email')
          <p class="text-xs text-red-600 flex items-center gap-1">
            <i class="fa-solid fa-circle-exclamation fa-xs"></i> {{ $message }}
          </p>
        @else
          <p class="text-xs text-red-600 flex items-center gap-1" x-show="errors.email" x-cloak>
            <i class="fa-solid fa-circle-exclamation fa-xs"></i> <span x-text="errors.email"></span>
          </p>
        @enderror
      </div>

      {{-- Password --}}
      <div class="flex flex-col gap-1">
        <div class="flex items-center justify-between">
          <label for="password" class="text-sm font-medium text-blue-950">Password</label>
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-xs text-blue-700 hover:underline">
              Forgot password?
            </a>
          @endif
        </div>
        <div class="relative">
          <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
          <input
            id="password" :type="showPwd ? 'text' : 'password'" name="password"
            placeholder="••••••••"
            autocomplete="current-password"
            x-model="password"
            @blur="touch('password')"
            class="w-full pl-10 pr-10 py-3 text-sm border rounded-md focus:outline-none focus:ring-2 transition-colors
              {{ $errors->any() ? 'border-red-300' : 'border-gray-300 focus:ring-blue-400 focus:border-blue-400' }}"
          >
          <button type="button" @click="showPwd = !showPwd"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
            <i :class="showPwd ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" class="text-sm"></i>
          </button>
        </div>
        <p class="text-xs text-red-600 flex items-center gap-1" x-show="errors.password" x-cloak>
          <i class="fa-solid fa-circle-exclamation fa-xs"></i> <span x-text="errors.password"></span>
        </p>
      </div>

      {{-- Remember me --}}
      <label class="flex items-center gap-2 cursor-pointer select-none">
        <input type="checkbox" name="remember"
          class="w-4 h-4 rounded border-gray-300 text-blue-950 focus:ring-blue-400">
        <span class="text-sm text-gray-600">Remember me for 30 days</span>
      </label>

      {{-- Submit --}}
      <button type="submit"
        class="w-full py-3 bg-blue-950 hover:bg-blue-900 text-white text-sm font-semibold rounded-md transition-colors mt-1
          disabled:opacity-60 disabled:cursor-not-allowed"
        :disabled="loading">
        <span x-show="!loading">Sign in</span>
        <span x-show="loading" x-cloak class="flex items-center justify-center gap-2">
          <i class="fa-solid fa-circle-notch fa-spin fa-sm"></i> Signing in…
        </span>
      </button>
    </form>

    {{-- Register link --}}
    @if (Route::has('register'))
      <p class="text-center text-sm text-gray-500">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-blue-700 font-medium hover:underline ml-1">Create one</a>
      </p>
    @endif

  </div>

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('loginForm', () => ({
        email:    '{{ old("email") }}',
        password: '',
        showPwd:  false,
        loading:  false,
        touched:  { email: false, password: false },
        errors:   { email: '', password: '' },

        rules: {
          email(v) {
            if (!v.trim()) return 'Email is required.';
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim())) return 'Enter a valid email address.';
            return '';
          },
          password(v) {
            if (!v) return 'Password is required.';
            return '';
          },
        },

        touch(field) {
          this.touched[field] = true;
          this.errors[field] = this.rules[field](this[field]);
        },

        validate() {
          let ok = true;
          for (const f of ['email', 'password']) {
            this.touched[f] = true;
            this.errors[f] = this.rules[f](this[f]);
            if (this.errors[f]) ok = false;
          }
          return ok;
        },

        submit(form) {
          if (!this.validate()) return;
          this.loading = true;
          form.submit();
        },
      }));
    });
  </script>
</x-guest-layout>
