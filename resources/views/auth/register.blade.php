<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>สมัครสมาชิก • My Shop</title>
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

  <div class="w-full max-w-md glass shadow-soft rounded-2xl border border-slate-200 p-8 animate-fadeIn">
    <!-- โลโก้ -->
    <div class="text-center mb-8">
      {{-- <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-primary/10 to-emerald-100 text-primary grid place-items-center shadow-inner">
        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
          <path d="M7 4h10l3 5v9a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9l3-5zm10 7H7v7h10v-7zM8 6l-1.2 2h10.4L16 6H8z"/>
        </svg>
      </div> --}}
      <h1 class="mt-4 text-2xl font-semibold text-ink">สมัครสมาชิก</h1>
      {{-- <p class="text-sm text-slate-500 mt-1">สร้างบัญชีใหม่เพื่อเริ่มต้นการช้อปปิ้งกับ <span class="text-primary font-medium">My Shop</span></p> --}}
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
      @csrf

      <!-- Full Name -->
      <div>
        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">ชื่อ-นามสกุล</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
               class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-900 placeholder-slate-400 shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none"
               placeholder="ชื่อ-นามสกุล">
        @error('name') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">อีเมล</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
               class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-900 placeholder-slate-400 shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none"
               placeholder="email@example.com">
        @error('email') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">รหัสผ่าน</label>
        <input id="password" type="password" name="password" required autocomplete="new-password"
               class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-900 placeholder-slate-400 shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none"
               placeholder="••••••••">
        @error('password') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Confirm Password -->
      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">ยืนยันรหัสผ่าน</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
               class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-900 placeholder-slate-400 shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none"
               placeholder="กรอกรหัสผ่านอีกครั้ง">
        @error('password_confirmation') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Role (ถ้าต้องการให้เลือก) -->
      
      <div>
        <label for="role" class="block text-sm font-medium text-slate-700 mb-1">ประเภทบัญชี</label>
        <select id="role" name="role"
                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-900 shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none">
          <option value="customer">ลูกค้า</option>
          {{-- <option value="seller">ผู้ขาย</option> --}}
        </select>
        @error('role') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>
     

      <!-- Submit -->
      <button
        class="w-full rounded-xl bg-primary text-white py-2.5 font-medium hover:bg-blue-700 transition-all focus:outline-none focus:ring-4 focus:ring-blue-100 shadow-md">
        สมัครสมาชิก
      </button>

      <!-- Already Registered -->
      <p class="text-center text-sm text-slate-600 mt-6">
        มีบัญชีอยู่แล้ว?
        <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">เข้าสู่ระบบ</a>
      </p>
    </form>
  </div>

  <!-- ลายน้ำ -->
  <footer class="absolute bottom-4 w-full text-center text-xs text-slate-400">
    © {{ date('Y') }} My Shop — All rights reserved.
  </footer>
</body>
</html>
