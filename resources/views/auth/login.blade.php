<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>เข้าสู่ระบบ • My Shop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: { DEFAULT: '#2563eb' },
            ink: '#0f172a'
          },
          boxShadow: {
            soft: '0 10px 40px rgba(37,99,235,.08)'
          },
          animation: {
            fadeIn: 'fadeIn 1s ease-in-out'
          },
          keyframes: {
            fadeIn: { '0%': { opacity: 0, transform: 'translateY(10px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } }
          }
        }
      }
    }
  </script>
  <style>
    .bg-soft {
      background:
        radial-gradient(1000px 600px at 0% 0%, rgba(59,130,246,.05), transparent 70%),
        radial-gradient(1000px 600px at 100% 100%, rgba(16,185,129,.05), transparent 70%),
        linear-gradient(180deg, #f9fafb, #ffffff);
    }
    .glass {
      background: rgba(255,255,255,0.8);
      backdrop-filter: blur(14px);
    }
  </style>
</head>

<body class="min-h-screen bg-soft text-slate-800 selection:bg-primary/10 selection:text-primary flex items-center justify-center px-4">

  <!-- กล่อง Login -->
  <div class="w-full max-w-md glass shadow-soft rounded-2xl border border-slate-200 p-8 animate-fadeIn">
    <!-- โลโก้ / ชื่อแบรนด์ -->
    <div class="text-center mb-8">
      {{-- <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-primary/10 to-emerald-100 text-primary grid place-items-center shadow-inner">
        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
          <path d="M7 4h10l3 5v9a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9l3-5zm10 7H7v7h10v-7zM8 6l-1.2 2h10.4L16 6H8z"/>
        </svg>
      </div> --}}
      <h1 class="mt-4 text-2xl font-semibold text-ink">เข้าสู่ระบบ</h1>
      {{-- <p class="text-sm text-slate-500 mt-1">ยินดีต้อนรับกลับสู่ <span class="font-medium text-primary">My Shop</span></p> --}}
    </div>

    <!-- Session Status -->
    @if (session('status'))
      <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-2 text-sm">
        {{ session('status') }}
      </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
      @csrf

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">อีเมล</label>
        <input
          id="email"
          type="email"
          name="email"
          value="{{ old('email') }}"
          autocomplete="username"
          required
          class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-900 placeholder-slate-400 shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all"
          placeholder="email@example.com"
        />
        @error('email') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Password -->
      <div>
        <div class="flex items-center justify-between mb-1">
          <label for="password" class="block text-sm font-medium text-slate-700">รหัสผ่าน</label>
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-xs text-primary hover:underline">ลืมรหัสผ่าน?</a>
          @endif
        </div>
        <input
          id="password"
          type="password"
          name="password"
          autocomplete="current-password"
          required
          class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-900 placeholder-slate-400 shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all"
          placeholder="••••••••"
        />
        @error('password') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Remember -->
      <div class="flex items-center justify-between">
        <label class="inline-flex items-center gap-2 text-sm text-slate-600">
          <input type="checkbox" name="remember" class="rounded border-slate-300 text-primary focus:ring-primary" />
          จำฉันไว้ในระบบ
        </label>
      </div>

      <!-- Submit -->
      <button
        class="w-full rounded-xl bg-primary text-white py-2.5 font-medium hover:bg-blue-700 transition-all focus:outline-none focus:ring-4 focus:ring-blue-100 shadow-md">
        เข้าสู่ระบบ
      </button>
    </form>

    <!-- สมัครสมาชิก -->
    @if (Route::has('register'))
      <p class="text-center text-sm text-slate-600 mt-6">
        ยังไม่มีบัญชี?
        <a href="{{ route('register') }}" class="text-primary font-medium hover:underline">สร้างบัญชีใหม่</a>
      </p>
    @endif
  </div>

  <!-- ลายน้ำด้านล่าง -->
  <footer class="absolute bottom-4 w-full text-center text-xs text-slate-400">
    © {{ date('Y') }} My Shop — All rights reserved.
  </footer>
</body>
</html>
