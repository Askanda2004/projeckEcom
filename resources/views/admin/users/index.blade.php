<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <title>Admin • Manage User Accounts</title>
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
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/></svg>
          </div>
          <span class="font-semibold">ผู้ดูแลระบบ</span>
        </div>

        <div class="flex items-center gap-2">
          <form method="POST" action="{{ route('logout') }}" class="ml-2">
            @csrf
            <button class="px-3 py-1.5 text-sm rounded-lg border border-neutral-300 text-ink hover:bg-neutral-100 transition-colors">
              ออกจากระบบ
            </button>
          </form>
        </div>
      </div>
    </header>

    @php
      // 12. เปลี่ยนสี Active/Hover ของ Sidebar เป็น 'neutral'
      $activeAll      = !isset($filterRole) || is_null($filterRole);
      $activeCustomer = (isset($filterRole) && $filterRole === 'customer');
      $activeSeller   = (isset($filterRole) && $filterRole === 'seller');
      $activeAdmin    = (isset($filterRole) && $filterRole === 'admin');

      $linkBase = 'flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-neutral-100 transition-colors';
      $activeCls = 'bg-neutral-100 text-ink font-medium';
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-12 gap-6">
      <aside class="col-span-12 md:col-span-3 lg:col-span-3">
        <div class="bg-white rounded-2xl shadow-soft p-4 md:p-5">
          <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wide mb-3">Navigation</h3>

          <nav class="space-y-1">
            {{-- 🔹 จัดการผู้ใช้ทั้งหมด --}}
            <a href="{{ route('admin.users.index') }}"
              class="{{ $linkBase }} {{ $activeAll ? $activeCls : '' }}">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
              จัดการผู้ใช้ (ทั้งหมด)
            </a>
            {{-- 🔹 Users Customer --}}
            <a href="{{ route('admin.users.byRole','customer') }}"
              class="{{ $linkBase }} {{ $activeCustomer ? $activeCls : '' }}">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.7 0 4.88-2.18 4.88-4.88S14.7 2.24 12 2.24 7.12 4.42 7.12 7.12 9.3 12 12 12zm0 2.4c-3.25 0-9.6 1.63-9.6 4.88V22h19.2v-2.72c0-3.25-6.35-4.88-9.6-4.88z"/></svg>
              บัญชีลูกค้า
            </a>
            {{-- 🔹 Users Seller --}}
            <a href="{{ route('admin.users.byRole','seller') }}"
              class="{{ $linkBase }} {{ $activeSeller ? $activeCls : '' }}">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6l3 7h11l3-7H3zm3 9c-1.66 0-3 1.34-3 3v1h18v-1c0-1.66-1.34-3-3-3H6z"/></svg>
              บัญชีร้านค้า
            </a>
            {{-- 🔹 Users Admin --}}
            <a href="{{ route('admin.users.byRole','admin') }}"
              class="{{ $linkBase }} {{ $activeAdmin ? $activeCls : '' }}">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 11.99V5.5l6 2.67V11c0 4.02-2.68 7.96-6 9.27V12.99z"/></svg>
              บัญชีผู้ดูแลระบบ
            </a>
            {{-- ✅ เพิ่มเมนูตรวจสอบการชำระเงิน --}}
            <a href="{{ route('admin.payments.index') }}"
              class="{{ $linkBase }} {{ request()->routeIs('admin.payments.*') ? $activeCls : '' }}">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M21 7H3V5h18v2zm0 4H3v8h18v-8zm-6 3h2v2h-2v-2z"/></svg>
              ตรวจสอบการชำระเงิน
            </a>
          </nav>
        </div>
      </aside>


      <main class="col-span-12 md:col-span-9 lg:col-span-9 space-y-6">
        <div class="flex items-center justify-between">
          <h1 class="text-2xl font-bold">
            {{ $activeAll ? 'จัดการบัญชีผู้ใช้' : 'จัดการบัญชีผู้ใช้ • '.ucfirst($filterRole) }}
          </h1>

          <form method="GET"
                action="{{ $activeAll ? route('admin.users.index') : route('admin.users.byRole',$filterRole) }}"
                class="relative max-w-xl w-full">
            <div class="rounded-full bg-gradient-to-b from-neutral-50 to-white p-1 shadow-soft">
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-400">
                  <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="7" stroke-width="2"></circle><path d="M20 20l-3.5-3.5" stroke-width="2"></path></svg>
                </span>

                <input
                  id="q"
                  type="text"
                  name="q"
                  value="{{ request('q') }}"
                  placeholder="Search name or email..."
                  class="peer w-full pr-28 pl-12 py-3.5 rounded-full border border-neutral-200
                         bg-white/90 placeholder-neutral-400 text-ink
                         focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary
                         transition-all" />

                <button type="button" id="clearBtn"
                        class="hidden absolute right-28 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600"
                        aria-label="Clear search">
                  <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M18.3 5.7a1 1 0 0 0-1.4 0L12 10.6 7.1 5.7A1 1 0 1 0 5.7 7.1L10.6 12l-4.9 4.9a1 1 0 1 0 1.4 1.4L12 13.4l4.9 4.9a1 1 0 0 0 1.4-1.4L13.4 12l4.9-4.9a1 1 0 0 0 0-1.4z"/></svg>
                </button>

                <button class="absolute right-1 top-1/2 -translate-y-1/2 rounded-full px-5 py-2.5
                               bg-primary text-white text-sm font-medium hover:bg-primary/90
                               active:scale-[.98] transition-all shadow-md">
                  ค้นหา
                </button>
              </div>
            </div>
          </form>
          <script>/* (Script คงเดิม) */ (function(){const input = document.getElementById('q'); const clearBtn = document.getElementById('clearBtn'); const toggleClear = () => clearBtn.classList.toggle('hidden', !input.value); toggleClear(); input.addEventListener('input', toggleClear); clearBtn.addEventListener('click', () => { input.value=''; input.focus(); toggleClear(); });})();</script>
        </div>

        @if (session('status'))
          <div class="rounded-xl bg-green-50 text-green-800 px-4 py-3 shadow-soft">{{ session('status') }}</div>
        @endif
        @if (session('error'))
          <div class="rounded-xl bg-red-50 text-red-700 px-4 py-3 shadow-soft">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-2xl shadow-soft">
          <div class="p-4 sm:p-6 border-b border-neutral-100">
            <div class="flex items-center justify-between">
              <div class="text-neutral-500 text-sm">
                ผู้ใช้ทั้งหมด: <strong>{{ $users->total() }}</strong>
                @if(!$activeAll)
                  <span class="ml-2 text-neutral-400">(Role: {{ ucfirst($filterRole) }})</span>
                @endif
              </div>
              <div class="md:hidden">
                <form method="GET" action="{{ $activeAll ? route('admin.users.index') : route('admin.users.byRole',$filterRole) }}">
                  <input type="text" name="q" value="{{ request('q') }}" placeholder="Search…"
                         class="w-56 rounded-lg border-neutral-300 focus:border-primary focus:ring-primary/10" />
                </form>
              </div>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead>
                <tr class="bg-neutral-50 text-neutral-600">
                  <th class="text-left font-semibold px-4 py-3 border-b border-neutral-200">ชื่อ</th>
                  <th class="text-left font-semibold px-4 py-3 border-b border-neutral-200">อีเมล</th>
                  <th class="text-left font-semibold px-4 py-3 border-b border-neutral-200">บทบาท</th>
                  <th class="text-left font-semibold px-4 py-3 border-b border-neutral-200 w-28">แก้ไข</th>
                  <th class="text-left font-semibold px-4 py-3 border-b border-neutral-200 w-28">ลบ</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($users as $user)
                  <tr class="hover:bg-neutral-50">
                    <td class="px-4 py-3 border-b border-neutral-100">{{ $user->username ?? $user->name ?? '-' }}</td>
                    <td class="px-4 py-3 border-b border-neutral-100">{{ $user->email }}</td>
                    <td class="px-4 py-3 border-b border-neutral-100">
                      @php
                        // (คงเดิม) Badge สียังคงเดิมเพราะสื่อความหมายชัดเจน
                        $badge = [
                          'admin'    => 'bg-rose-100 text-rose-700',
                          'seller'   => 'bg-amber-100 text-amber-700',
                          'customer' => 'bg-emerald-100 text-emerald-700',
                        ][$user->role] ?? 'bg-neutral-100 text-neutral-700';
                      @endphp
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                        {{ ucfirst($user->role) }}
                      </span>
                    </td>
                    <td class="px-4 py-3 border-b border-neutral-100">
                      <a href="{{ route('admin.users.edit', $user) }}"
                         class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-neutral-300 hover:bg-neutral-100 transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                        แก้ไข
                      </a>
                    </td>
                    <td class="px-4 py-3 border-b border-neutral-100">
                      <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                            onsubmit="return confirm('ยืนยันลบผู้ใช้นี้?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 transition-colors">
                          ลบ
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-neutral-500">No users found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="p-4 sm:p-6 border-t border-neutral-100">
            <div class="flex items-center justify-between">
              <div class="text-xs text-neutral-500">
                Showing
                <span class="font-medium">{{ $users->firstItem() ?? 0 }}</span>
                to
                <span class="font-medium">{{ $users->lastItem() ?? 0 }}</span>
                of
                <span class="font-medium">{{ $users->total() }}</span>
                results
              </div>
              <div>{{ $users->links() }}</div>
            </div>
          </div>
        </div>
      </main>
    </div>

</body>
</html>