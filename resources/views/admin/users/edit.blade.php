<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin • Edit User</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  {{-- Tailwind via CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- 2. กำหนด Design System (เขียวโอลีฟ) --}}
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'sans-serif'], // 3. ใช้ 'Inter' เป็นฟอนต์หลัก
          },
          colors: { 
            primary: { DEFAULT: '#7C8B6A' }, // 4. เปลี่ยนสีหลักเป็น 'Olive'
            sand: '#FAFAF7',                 // 5. พื้นหลังสีขาวนวล
            ink: '#111827',                  // 6. ตัวหนังสือสีเทาเข้ม
            olive: '#7C8B6A'
          },
          boxShadow: { 
            soft:'0 6px 24px rgba(0,0,0,0.06)' // 7. เงาที่นุ่มนวล
          },
          borderRadius: { xl2:'1rem' } // (คงเดิม)
        }
      }
    }
  </script>
</head>
<body class="bg-sand text-ink antialiased font-sans">

  <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-sm border-b border-neutral-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/></svg>
        </div>
        <span class="font-semibold">ผู้ดูแลระบบ</span>
      </div>

      <nav class="flex items-center gap-3 text-sm">
        <a href="{{ route('admin.index') }}" class="text-neutral-600 hover:text-neutral-900">จัดการผู้ใช้</a>
        {{-- <a href="{{ route('dashboard') }}" class="text-neutral-600 hover:text-neutral-900">Dashboard</a> --}}
      </nav>
    </div>
  </header>

  <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold">แก้ไขผู้ใช้</h1>
        <p class="text-neutral-500 text-sm">อัปเดตข้อมูลบัญชีและบทบาท.</p>
      </div>
      <a href="{{ route('admin.index') }}"
         class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 px-3 py-2 text-sm hover:bg-neutral-100 transition-colors">
        ← กลับ
      </a>
    </div>

    @if (session('status'))
      <div class="mb-6 rounded-xl bg-green-50 text-green-800 px-4 py-3 shadow-soft">{{ session('status') }}</div>
    @endif
    @if (session('error'))
      <div class="mb-6 rounded-xl bg-red-50 text-red-700 px-4 py-3 shadow-soft">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-soft p-6 sm:p-8">
      <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
        @csrf
        @method('PATCH')

        <div>
          <label for="name" class="block text-sm font-medium text-neutral-700">ชื่อ</label>
          <input id="name" name="name" type="text"
                 value="{{ old('name', $user->name) }}"
                 class="mt-1 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2
                        focus:border-primary focus:ring-4 focus:ring-primary/10 transition-colors"
                 required />
          @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-neutral-700">อีเมล</label>
          <input id="email" name="email" type="email"
                 value="{{ old('email', $user->email) }}"
                 class="mt-1 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2
                        focus:border-primary focus:ring-4 focus:ring-primary/10 transition-colors"
                 required />
          @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="role" class="block text-sm font-medium text-neutral-700">บทบาท</label>
          <select id="role" name="role"
                  class="mt-1 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2
                         focus:border-primary focus:ring-4 focus:ring-primary/10 transition-colors"
                  required>
            @foreach (['admin','seller','customer'] as $role)
              <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>
                {{ ucfirst($role) }}
              </option>
            @endforeach
          </select>
          @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3">
          <button class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-white
                         hover:bg-primary/90 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            บันทึกการเปลี่ยนแปลง
          </button>
          <a href="{{ route('admin.index') }}"
             class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 px-4 py-2.5 hover:bg-neutral-100 transition-colors">
            ยกเลิก
          </a>
        </div>
      </form>
    </div>

    {{-- Optional danger zone: delete user --}}
    <div class="mt-8 bg-white rounded-2xl shadow-soft p-6 sm:p-8">
      <div class="flex items-center justify-between">
        <div>
          {{-- <h2 class="font-semibold">Danger Zone</h2> --}}
          <p class="text-sm text-neutral-500">ลบบัญชีนี้อย่างถาวร.</p>
        </div>
        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
              onsubmit="return confirm('Delete this user permanently?')">
          @csrf
          @method('DELETE')
          <button class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-2 text-rose-700 hover:bg-rose-100 transition-colors">
            ลบบัญชีผู้ใช้งาน
          </button>
        </form>
      </div>
    </div>
  </main>

</body>
</html>