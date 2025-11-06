<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Admin • ตรวจสอบการชำระเงิน</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // --- 2. สร้าง Design System กลาง ---
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
          }
        }
      }
    }
  </script>
</head>
<body class="bg-sand text-ink antialiased font-sans">

  {{-- HEADER --}}
  <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-sm border-b border-neutral-200">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-primary/10 grid place-items-center">
          <svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="currentColor"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </div>
        <span class="font-semibold">ผู้ดูแลระบบ</span>
        <span class="hidden md:inline text-neutral-400">/</span>
        <span class="hidden md:inline text-neutral-500">ตรวจสอบการชำระเงิน</span>
      </div>
      <form method="POST" action="{{ route('logout') }}"> @csrf
        <button class="rounded-lg border border-neutral-300 px-3 py-1.5 text-sm font-medium text-ink hover:bg-neutral-100 transition-colors">
          ออกจากระบบ
        </button>
      </form>
    </div>
  </header>

  @php
    // 11. ปรับสี Active/Hover ของ Sidebar เป็น 'neutral'
    $linkBase  = 'flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-neutral-100 transition-colors';
    $activeCls = 'bg-neutral-100 text-ink font-medium';
    $activeAll      = request()->routeIs('admin.users.index');
    $activeCustomer = request()->routeIs('admin.users.byRole') && request('role','')==='customer';
    $activeSeller   = request()->routeIs('admin.users.byRole') && request('role','')==='seller';
    $activeAdmin    = request()->routeIs('admin.users.byRole') && request('role','')==='admin';
    $activePayments = request()->routeIs('admin.payments.*');
  @endphp

  <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-12 gap-6">

    {{-- SIDEBAR --}}
    <aside class="col-span-12 md:col-span-3 lg:col-span-3">
      <div class="bg-white rounded-2xl shadow-soft p-4 md:p-5">
        <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-3">Navigation</h3>
        <nav class="space-y-1">
          <a href="{{ route('admin.users.index') }}" class="{{ $linkBase }} {{ $activeAll ? $activeCls : '' }}">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
            จัดการผู้ใช้ (ทั้งหมด)
          </a>
          <a href="{{ route('admin.users.byRole','customer') }}" class="{{ $linkBase }} {{ $activeCustomer ? $activeCls : '' }}">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.7 0 4.88-2.18 4.88-4.88S14.7 2.24 12 2.24 7.12 4.42 7.12 7.12 9.3 12 12 12zm0 2.4c-3.25 0-9.6 1.63-9.6 4.88V22h19.2v-2.72c0-3.25-6.35-4.88-9.6-4.88z"/></svg>
            บัญชีลูกค้า
          </a>
          <a href="{{ route('admin.users.byRole','seller') }}" class="{{ $linkBase }} {{ $activeSeller ? $activeCls : '' }}">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6l3 7h11l3-7H3zm3 9c-1.66 0-3 1.34-3 3v1h18v-1c0-1.66-1.34-3-3-3H6z"/></svg>
            บัญชีร้านค้า
          </a>
          <a href="{{ route('admin.users.byRole','admin') }}" class="{{ $linkBase }} {{ $activeAdmin ? $activeCls : '' }}">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
            บัญชีผู้ดูแลระบบ
          </a>
          <a href="{{ route('admin.payments.index') }}" class="{{ $linkBase }} {{ $activePayments ? $activeCls : '' }}">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M3 5h18v4H3V5zm0 6h18v8H3v-8zm2 2v4h8v-4H5zM17 7V6h2v1h-2z"/>
            </svg>
            ตรวจสอบการชำระเงิน
          </a>
        </nav>
      </div>
    </aside>

    {{-- MAIN --}}
    <main class="col-span-12 md:col-span-9 space-y-6">

      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <h1 class="text-2xl font-bold">ตรวจสอบการชำระเงิน</h1>
          <p class="text-neutral-500 text-sm">ตรวจสลิป / ยืนยัน / ปฏิเสธการชำระ</p>
        </div>

        <form method="GET" class="flex items-center gap-2">
          <input
            name="q"
            value="{{ $q ?? request('q','') }}"
            placeholder="ค้นหา: ชื่อ / เบอร์"
            class="rounded-lg border border-neutral-300 px-3 py-2 focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
          <button class="rounded-lg bg-primary text-white px-4 py-2 hover:bg-primary/90 transition-colors">ค้นหา</button>
        </form>
      </div>

      @if (session('status'))
        <div class="rounded-xl bg-emerald-50 text-emerald-700 px-4 py-3 border border-emerald-200 shadow-soft">
          {{ session('status') }}
        </div>
      @endif

      <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-neutral-50 text-neutral-600">
              <tr>
                <th class="px-4 py-3 text-left">วันที่</th>
                <th class="px-4 py-3 text-left">ผู้สั่งซื้อ</th>
                <th class="px-4 py-3 text-right">ยอดรวม</th>
                <th class="px-4 py-3 text-center">สถานะ</th>
                <th class="px-4 py-3 text-center">จัดการ</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orders as $o)
                <tr class="border-t border-neutral-100 hover:bg-neutral-50">
                  <td class="px-4 py-3">
                    {{ \Carbon\Carbon::parse($o->order_date)->setTimezone('Asia/Bangkok')->format('d/m/Y H:i') }}
                  </td>
                  <td class="px-4 py-3 align-top">
                    <div class="leading-tight">
                      <span class="block font-semibold text-ink"> 👤 {{ $o->shipping_name ?? '—' }}</span>
                      <span class="block text-sm text-neutral-500">เบอร์โทร: {{ $o->shipping_phone ?? '—' }}</span>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-right">฿{{ number_format((float)$o->total_amount,2) }}</td>
                  <td class="px-4 py-3 text-center">
                    @php
                      // (คงเดิม) Badge สียังคงเดิมเพราะสื่อความหมายชัดเจน
                      $badge = match($o->payment_status){
                        'verified' => 'bg-emerald-100 text-emerald-700',
                        'rejected' => 'bg-rose-100 text-rose-700',
                        default    => 'bg-amber-100 text-amber-700',
                      };
                    @endphp
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $badge }}">
                      {{ $o->payment_status ?? 'pending' }}
                    </span>
                  </td>
                  <td class="px-4 py-3 text-center">
                    <a href="{{ route('admin.payments.show', $o->order_id) }}" class="rounded-lg border border-neutral-300 px-3 py-1.5 hover:bg-neutral-100 text-sm font-medium text-ink transition-colors">
                      รายละเอียด
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="px-4 py-8 text-center text-neutral-500">ไม่พบคำสั่งซื้อที่มีสลิป</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if ($orders->hasPages())
          <div class="p-4 border-t border-neutral-100">
            {{-- Laravel pagination --}}
            {{ $orders->links() }}
          </div>
        @endif
      </div>
    </main>
  </div>
</body>
</html>