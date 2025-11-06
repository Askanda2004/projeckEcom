<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>เข้าสู่ระบบ • My Shop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // --- 2. เปลี่ยน Design System เป็น (Olive/Sand/Ink) ---
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'sans-serif'], // 3. ใช้ 'Inter' เป็นฟอนต์หลัก
          },
          colors: { 
            primary: { DEFAULT: '#7C8B6A' }, // 4. เปลี่ยนสีหลัก/แอคเซนต์เป็น 'Olive'
            sand: '#FAFAF7',                 // 5. พื้นหลังสีขาวนวล
            ink: '#111827',                  // 6. ตัวหนังสือสีเทาเข้ม
            olive: '#7C8B6A'
          },
          boxShadow: { 
            soft:'0 6px 24px rgba(0,0,0,0.06)' // 7. เงาที่นุ่มนวล
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
  </head>

<body class="min-h-screen bg-sand text-ink antialiased font-sans flex items-center justify-center px-4">

  <div class="w-full max-w-md bg-white shadow-soft rounded-2xl border border-neutral-100 p-8 animate-fadeIn">
    <div class="text-center mb-8">
      <h1 class="mt-4 text-2xl font-semibold text-ink">เข้าสู่ระบบ</h1>
    </div>

    @if (session('status'))
      <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-2 text-sm">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
      @csrf

      <div>
        <label for="email" class="block text-sm font-medium text-neutral-700 mb-1">อีเมล</label>
        <input
          id="email"
          type="email"
          name="email"
          value="{{ old('email') }}"
          autocomplete="username"
          required
          class="w-full rounded-xl border border-neutral-300 bg-white px-3 py-2.5 text-neutral-900 placeholder-neutral-400 shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all"
          placeholder="email@example.com"
        />
        @error('email') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <div class="flex items-center justify-between mb-1">
          <label for="password" class="block text-sm font-medium text-neutral-700">รหัสผ่าน</label>
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
          class="w-full rounded-xl border border-neutral-300 bg-white px-3 py-2.5 text-neutral-900 placeholder-neutral-400 shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all"
          placeholder="••••••••"
        />
        @error('password') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="flex items-center justify-between">
        <label class="inline-flex items-center gap-2 text-sm text-neutral-600">
          <input type="checkbox" name="remember" class="rounded border-neutral-300 text-primary focus:ring-primary" />
          จำฉันไว้ในระบบ
        </label>
      </div>

      <button
        class="w-full rounded-xl bg-ink text-white py-2.5 font-medium hover:bg-neutral-800 transition-all focus:outline-none focus:ring-4 focus:ring-primary/10 shadow-md">
        เข้าสู่ระบบ
      </button>
    </form>

    @if (Route::has('register'))
      <p class="text-center text-sm text-neutral-600 mt-6">
        ยังไม่มีบัญชี?
        <a href="{{ route('register') }}" class="text-primary font-medium hover:underline">สร้างบัญชีใหม่</a>
      </p>
    @endif
  </div>

  <footer class="absolute bottom-4 w-full text-center text-xs text-neutral-400">
    © {{ date('Y') }} My Shop — All rights reserved.
  </footer>
</body>
</html>