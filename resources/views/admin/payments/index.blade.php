<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Admin • ตรวจสอบการชำระเงิน</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors:{ primary:{DEFAULT:'#2563eb'} },
          boxShadow:{ soft:'0 10px 30px rgba(2,6,23,.06)', card:'0 12px 40px rgba(2,6,23,.08)'}
        }
      }
    }
  </script>
</head>
<body class="bg-slate-50 text-slate-800">

  {{-- HEADER --}}
  <header class="sticky top-0 z-30 bg-white/80 backdrop-blur shadow">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-primary/10 grid place-items-center">
          <svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="currentColor"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </div>
        <span class="font-semibold">Admin</span>
        <span class="hidden md:inline text-slate-400">/</span>
        <span class="hidden md:inline text-slate-500">ตรวจสอบการชำระเงิน</span>
      </div>
      <form method="POST" action="{{ route('logout') }}"> @csrf
        <button class="rounded-lg bg-slate-900 text-white px-3 py-1.5 hover:bg-slate-800">Logout</button>
      </form>
    </div>
  </header>

  @php
    // ใช้ชุด class แบบเดียวกับหน้า users
    $linkBase  = 'flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-100';
    $activeCls = 'bg-slate-100 text-slate-900';
    $activeAll      = request()->routeIs('admin.users.index');
    $activeCustomer = request()->routeIs('admin.users.byRole') && request('role','')==='customer';
    $activeSeller   = request()->routeIs('admin.users.byRole') && request('role','')==='seller';
    $activeAdmin    = request()->routeIs('admin.users.byRole') && request('role','')==='admin';
    $activePayments = request()->routeIs('admin.payments.*');
  @endphp

  <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-12 gap-6">

    {{-- SIDEBAR --}}
    <aside class="col-span-12 md:col-span-3 lg:col-span-3">
      <div class="bg-white rounded-2xl shadow-card p-4 md:p-5">
        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Navigation</h3>
        <nav class="space-y-1">
          <a href="{{ route('admin.users.index') }}" class="{{ $linkBase }} {{ $activeAll ? $activeCls : '' }}">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
            Manage Users (All)
          </a>
          <a href="{{ route('admin.users.byRole','customer') }}" class="{{ $linkBase }} {{ $activeCustomer ? $activeCls : '' }}">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.7 0 4.88-2.18 4.88-4.88S14.7 2.24 12 2.24 7.12 4.42 7.12 7.12 9.3 12 12 12zm0 2.4c-3.25 0-9.6 1.63-9.6 4.88V22h19.2v-2.72c0-3.25-6.35-4.88-9.6-4.88z"/></svg>
            Users Customer
          </a>
          <a href="{{ route('admin.users.byRole','seller') }}" class="{{ $linkBase }} {{ $activeSeller ? $activeCls : '' }}">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6l3 7h11l3-7H3zm3 9c-1.66 0-3 1.34-3 3v1h18v-1c0-1.66-1.34-3-3-3H6z"/></svg>
            Users Seller
          </a>
          <a href="{{ route('admin.users.byRole','admin') }}" class="{{ $linkBase }} {{ $activeAdmin ? $activeCls : '' }}">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
            Users Admin
          </a>

          {{-- ✅ เมนูใหม่: ตรวจสอบการชำระเงิน --}}
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
          <p class="text-slate-500 text-sm">ตรวจสลิป / ยืนยัน / ปฏิเสธการชำระ</p>
        </div>

        <form method="GET" class="flex items-center gap-2">
          <input name="q" value="{{ $q ?? request('q','') }}" placeholder="ค้นหา: ชื่อ / เบอร์"
                 class="rounded-lg border border-slate-200 px-3 py-2 focus:border-primary focus:ring-4 focus:ring-blue-100">
          <button class="rounded-lg bg-primary text-white px-4 py-2 hover:bg-blue-700">ค้นหา</button>
        </form>
      </div>

      <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
              <tr>
                <th class="px-4 py-3 text-left">วันที่</th>
                <th class="px-4 py-3 text-left">ผู้สั่งซื้อ</th>
                <th class="px-4 py-3 text-right">ยอดรวม</th>
                <th class="px-4 py-3 text-center">สถานะ</th>
                <th class="px-4 py-3 text-center">สลิป</th>
                <th class="px-4 py-3 text-center">จัดการ</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orders as $o)
                <tr class="border-t hover:bg-slate-50">
                  <td class="px-4 py-3">
                    {{ \Carbon\Carbon::parse($o->order_date)->setTimezone('Asia/Bangkok')->format('d/m/Y H:i') }}
                  </td>
                  <td class="px-4 py-3">
                    {{ $o->shipping_name }} • {{ $o->shipping_phone }}
                  </td>
                  <td class="px-4 py-3 text-right">
                    ฿{{ number_format($o->total_amount,2) }}
                  </td>
                  <td class="px-4 py-3 text-center">
                    <span class="text-xs px-2 py-0.5 rounded-full
                      {{ $o->payment_status==='verified' ? 'bg-emerald-100 text-emerald-700' :
                         ($o->payment_status==='rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                      {{ $o->payment_status ?? 'pending' }}
                    </span>
                  </td>
                  <td class="px-4 py-3 text-center">
                    @if($o->payment_slip)
                      <a href="{{ asset('storage/'.$o->payment_slip) }}" target="_blank" class="text-primary underline">เปิดดู</a>
                    @else — @endif
                  </td>
                  <td class="px-4 py-3 text-center">
                    <a href="{{ route('admin.payments.show', $o->order_id) }}"
                       class="rounded-lg border px-3 py-1.5 hover:bg-slate-50">
                      รายละเอียด
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                    ไม่พบคำสั่งซื้อที่มีสลิป
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="p-4 border-t">
          {{ $orders->links() }}
        </div>
      </div>
    </main>
  </div>
</body>
</html>
