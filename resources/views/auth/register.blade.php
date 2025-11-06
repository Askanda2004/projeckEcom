<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>สมัครสมาชิก • My Shop</title>
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

<body class="min-h-screen bg-sand text-ink antialiased font-sans flex items-center justify-center px-4 py-8">

  <div class="w-full max-w-md bg-white shadow-soft rounded-2xl border border-neutral-100 p-8 animate-fadeIn">
    <div class="text-center mb-8">
      <h1 class="mt-4 text-2xl font-semibold text-ink">สมัครสมาชิก</h1>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
      @csrf

      <div>
        <label for="name" class="block text-sm font-medium text-neutral-700 mb-1">ชื่อ-นามสกุล</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
               class="w-full rounded-xl border border-neutral-300 bg-white px-3 py-2.5 text-neutral-900 placeholder-neutral-400 shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none"
               placeholder="ชื่อ-นามสกุล">
        @error('name') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-neutral-700 mb-1">อีเมล</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
               class="w-full rounded-xl border border-neutral-300 bg-white px-3 py-2.5 text-neutral-900 placeholder-neutral-400 shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none"
               placeholder="email@example.com">
        @error('email') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-neutral-700 mb-1">รหัสผ่าน</label>
        <input id="password" type="password" name="password" required autocomplete="new-password"
               class="w-full rounded-xl border border-neutral-300 bg-white px-3 py-2.5 text-neutral-900 placeholder-neutral-400 shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none"
               placeholder="••••••••">
        @error('password') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 mb-1">ยืนยันรหัสผ่าน</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
               class="w-full rounded-xl border border-neutral-300 bg-white px-3 py-2.5 text-neutral-900 placeholder-neutral-400 shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none"
               placeholder="กรอกรหัสผ่านอีกครั้ง">
        @error('password_confirmation') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="role" class="block text-sm font-medium text-neutral-700 mb-1">ประเภทบัญชี</label>
        <select id="role" name="role"
                class="w-full rounded-xl border border-neutral-300 bg-white px-3 py-2.5 text-neutral-900 shadow-sm focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none">
          <option value="customer">ลูกค้า</option>
          {{-- <option value="seller">ผู้ขาย</option> --}}
        </select>
        @error('role') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>
     

      <button
        class="w-full rounded-xl bg-ink text-white py-2.5 font-medium hover:bg-neutral-800 transition-all focus:outline-none focus:ring-4 focus:ring-primary/10 shadow-md">
        สมัครสมาชิก
      </button>

      <p class="text-center text-sm text-neutral-600 mt-6">
        มีบัญชีอยู่แล้ว?
        <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">เข้าสู่ระบบ</a>
      </p>
    </form>
  </div>

  <footer class="absolute bottom-4 w-full text-center text-xs text-neutral-400">
    © {{ date('Y') }} My Shop — All rights reserved.
  </footer>
</body>
</html>