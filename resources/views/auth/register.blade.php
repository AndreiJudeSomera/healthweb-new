<x-guest-layout>
  <div x-data="registerForm()" class="flex flex-col gap-6">

    {{-- Header --}}
    <div>
      <h2 class="text-2xl font-bold text-blue-950">Create an account</h2>
      <p class="text-sm text-gray-500 mt-1">Join HealthWeb to manage your health records</p>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('register') }}" @submit.prevent="submit($el)" novalidate class="flex flex-col gap-4">
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

      {{-- Username --}}
      <div class="flex flex-col gap-1">
        <label for="username" class="text-sm font-medium text-blue-950">Username</label>
        <div class="relative">
          <i class="fa-solid fa-at absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
          <input
            id="username" type="text" name="username"
            value="{{ old('username') }}"
            placeholder="Ex. JohnDoe"
            autocomplete="username"
            x-model="username"
            @blur="touch('username')"
            class="w-full pl-10 pr-4 py-3 text-sm border rounded-md focus:outline-none focus:ring-2 transition-colors
              {{ $errors->has('username') ? 'border-red-400 focus:ring-red-300 bg-red-50' : 'border-gray-300 focus:ring-blue-400 focus:border-blue-400' }}"
          >
        </div>
        @error('username')
          <p class="text-xs text-red-600 flex items-center gap-1">
            <i class="fa-solid fa-circle-exclamation fa-xs"></i> {{ $message }}
          </p>
        @else
          <p class="text-xs text-red-600 flex items-center gap-1" x-show="errors.username" x-cloak>
            <i class="fa-solid fa-circle-exclamation fa-xs"></i> <span x-text="errors.username"></span>
          </p>
        @enderror
      </div>

      {{-- Password --}}
      <div class="flex flex-col gap-1">
        <label for="password" class="text-sm font-medium text-blue-950">Password</label>
        <div class="relative">
          <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
          <input
            id="password" :type="showPwd ? 'text' : 'password'" name="password"
            placeholder="••••••••"
            autocomplete="new-password"
            x-model="password"
            @input="updateStrength()"
            @blur="touch('password')"
            class="w-full pl-10 pr-10 py-3 text-sm border rounded-md focus:outline-none focus:ring-2 transition-colors
              {{ $errors->has('password') ? 'border-red-400 focus:ring-red-300 bg-red-50' : 'border-gray-300 focus:ring-blue-400 focus:border-blue-400' }}"
          >
          <button type="button" @click="showPwd = !showPwd"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
            <i :class="showPwd ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" class="text-sm"></i>
          </button>
        </div>

        {{-- Strength bar --}}
        <div class="flex gap-1 mt-1" x-show="password.length > 0" x-cloak>
          <template x-for="i in 4" :key="i">
            <div class="h-1 flex-1 rounded-full transition-colors"
              :class="i <= strength
                ? (strength === 1 ? 'bg-red-400' : strength === 2 ? 'bg-amber-400' : strength === 3 ? 'bg-yellow-400' : 'bg-emerald-500')
                : 'bg-gray-200'">
            </div>
          </template>
          <span class="text-xs ml-1 font-medium"
            :class="strength === 1 ? 'text-red-500' : strength === 2 ? 'text-amber-500' : strength === 3 ? 'text-yellow-600' : 'text-emerald-600'"
            x-text="['', 'Weak', 'Fair', 'Good', 'Strong'][strength]">
          </span>
        </div>

        <ul class="text-xs text-gray-400 flex flex-wrap gap-x-3 gap-y-0.5 mt-1" x-show="password.length > 0" x-cloak>
          <li :class="password.length >= 8 ? 'text-emerald-600' : 'text-gray-400'" class="flex items-center gap-1">
            <i :class="password.length >= 8 ? 'fa-solid fa-check' : 'fa-solid fa-xmark'" class="fa-xs"></i> 8+ chars
          </li>
          <li :class="/[A-Z]/.test(password) ? 'text-emerald-600' : 'text-gray-400'" class="flex items-center gap-1">
            <i :class="/[A-Z]/.test(password) ? 'fa-solid fa-check' : 'fa-solid fa-xmark'" class="fa-xs"></i> Uppercase
          </li>
          <li :class="/[a-z]/.test(password) ? 'text-emerald-600' : 'text-gray-400'" class="flex items-center gap-1">
            <i :class="/[a-z]/.test(password) ? 'fa-solid fa-check' : 'fa-solid fa-xmark'" class="fa-xs"></i> Lowercase
          </li>
          <li :class="/[0-9]/.test(password) ? 'text-emerald-600' : 'text-gray-400'" class="flex items-center gap-1">
            <i :class="/[0-9]/.test(password) ? 'fa-solid fa-check' : 'fa-solid fa-xmark'" class="fa-xs"></i> Number
          </li>
        </ul>

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

      {{-- Confirm Password --}}
      <div class="flex flex-col gap-1">
        <label for="password_confirmation" class="text-sm font-medium text-blue-950">Confirm password</label>
        <div class="relative">
          <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
          <input
            id="password_confirmation" :type="showPwdConfirm ? 'text' : 'password'" name="password_confirmation"
            placeholder="••••••••"
            autocomplete="new-password"
            x-model="passwordConfirm"
            @blur="touch('passwordConfirm')"
            class="w-full pl-10 pr-10 py-3 text-sm border rounded-md focus:outline-none focus:ring-2 transition-colors
              {{ $errors->has('password_confirmation') ? 'border-red-400 focus:ring-red-300 bg-red-50' : 'border-gray-300 focus:ring-blue-400 focus:border-blue-400' }}"
          >
          <button type="button" @click="showPwdConfirm = !showPwdConfirm"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
            <i :class="showPwdConfirm ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" class="text-sm"></i>
          </button>
        </div>
        @error('password_confirmation')
          <p class="text-xs text-red-600 flex items-center gap-1">
            <i class="fa-solid fa-circle-exclamation fa-xs"></i> {{ $message }}
          </p>
        @else
          <p class="text-xs text-red-600 flex items-center gap-1" x-show="errors.passwordConfirm" x-cloak>
            <i class="fa-solid fa-circle-exclamation fa-xs"></i> <span x-text="errors.passwordConfirm"></span>
          </p>
        @enderror
      </div>

      {{-- Submit --}}
      <button type="submit"
        class="w-full py-3 bg-blue-950 hover:bg-blue-900 text-white text-sm font-semibold rounded-md transition-colors mt-1
          disabled:opacity-60 disabled:cursor-not-allowed"
        :disabled="loading">
        <span x-show="!loading">Create account</span>
        <span x-show="loading" x-cloak class="flex items-center justify-center gap-2">
          <i class="fa-solid fa-circle-notch fa-spin fa-sm"></i> Creating account…
        </span>
      </button>
    </form>

    {{-- Login link --}}
    <p class="text-center text-sm text-gray-500">
      Already have an account?
      <a href="{{ route('login') }}" class="text-blue-700 font-medium hover:underline ml-1">Sign in</a>
    </p>

  </div>

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('registerForm', () => ({
        email:          '{{ old("email") }}',
        username:       '{{ old("username") }}',
        password:       '',
        passwordConfirm: '',
        showPwd:        false,
        showPwdConfirm: false,
        loading:        false,
        strength:       0,
        touched:        { email: false, username: false, password: false, passwordConfirm: false },
        errors:         { email: '', username: '', password: '', passwordConfirm: '' },

        rules: {
          email(v) {
            if (!v.trim()) return 'Email is required.';
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim())) return 'Enter a valid email address.';
            return '';
          },
          username(v) {
            if (!v.trim()) return 'Username is required.';
            if (v.trim().length < 3) return 'Username must be at least 3 characters.';
            if (v.trim().length > 30) return 'Username must not exceed 30 characters.';
            // if (!/^[a-zA-Z0-9_]+$/.test(v.trim())) return 'Only letters, numbers, and underscores allowed.';
            // return '';
            if (!/^[a-zA-ZñÑ]+$/i.test(v.trim())) return 'Only letters allowed (A-Z, a-z, Ñ, ñ).';
            return '';
          },
          password(v) {
            if (!v) return 'Password is required.';
            if (v.length < 8) return 'Password must be at least 8 characters.';
            if (!/[A-Z]/.test(v)) return 'Password must contain at least one uppercase letter.';
            if (!/[a-z]/.test(v)) return 'Password must contain at least one lowercase letter.';
            if (!/[0-9]/.test(v)) return 'Password must contain at least one number.';
            return '';
          },
          passwordConfirm(v, ctx) {
            if (!v) return 'Please confirm your password.';
            if (v !== ctx.password) return 'Passwords do not match.';
            return '';
          },
        },

        updateStrength() {
          const p = this.password;
          let score = 0;
          if (p.length >= 8)       score++;
          if (/[A-Z]/.test(p))     score++;
          if (/[a-z]/.test(p))     score++;
          if (/[0-9]/.test(p))     score++;
          this.strength = score;
        },

        touch(field) {
          this.touched[field] = true;
          this.errors[field] = field === 'passwordConfirm'
            ? this.rules[field](this[field], this)
            : this.rules[field](this[field]);
        },

        validate() {
          let ok = true;
          for (const f of ['email', 'username', 'password', 'passwordConfirm']) {
            this.touched[f] = true;
            this.errors[f] = f === 'passwordConfirm'
              ? this.rules[f](this[f], this)
              : this.rules[f](this[f]);
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
