{{-- <!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>Customer • Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
  <!-- Topbar -->
  <header class="sticky top-0 z-30 bg-white/90 backdrop-blur border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="font-semibold">Customer Dashboard</div>
      <div class="flex items-center gap-3">
        <a href="{{ route('dashboard') }}" class="text-sm text-slate-600 hover:text-slate-900">Home</a>
        <form method="POST" action="{{ route('logout') }}"> @csrf
          <button class="px-3 py-1.5 text-sm rounded-lg bg-slate-900 text-white hover:bg-slate-800">Logout</button>
        </form>
      </div>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Welcome -->
    <section class="lg:col-span-3 bg-white rounded-2xl shadow p-6">
      <h1 class="text-2xl font-bold">สวัสดี {{ auth()->user()->name ?? 'ลูกค้า' }} 👋</h1>
      <p class="text-slate-600 mt-1">ยินดีต้อนรับสู่พื้นที่ลูกค้า คุณสามารถดูคำสั่งซื้อ ติดตามสถานะ และอัปเดตโปรไฟล์ได้ที่นี่</p>
      <div class="mt-4 flex gap-3">
        <a href="#" class="rounded-xl bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">สั่งซื้อสินค้า</a>
        <a href="#" class="rounded-xl border px-4 py-2 hover:bg-slate-50">ดูประวัติการสั่งซื้อ</a>
      </div>
    </section>

    <!-- Cards -->
    <section class="bg-white rounded-2xl shadow p-6">
      <div class="text-sm text-slate-500">คำสั่งซื้อทั้งหมด</div>
      <div class="mt-2 text-3xl font-bold">{{ $stats['orders_total'] ?? 0 }}</div>
    </section>
    <section class="bg-white rounded-2xl shadow p-6">
      <div class="text-sm text-slate-500">สถานะ “รอดำเนินการ”</div>
      <div class="mt-2 text-3xl font-bold">{{ $stats['orders_pending'] ?? 0 }}</div>
    </section>
    <section class="bg-white rounded-2xl shadow p-6">
      <div class="text-sm text-slate-500">ยอดใช้จ่ายรวม</div>
      <div class="mt-2 text-3xl font-bold">฿{{ number_format($stats['spent_total'] ?? 0, 2) }}</div>
    </section>

    <!-- Recent orders -->
    <section class="lg:col-span-3 bg-white rounded-2xl shadow p-6">
      <div class="flex items-center justify-between">
        <h2 class="font-semibold">คำสั่งซื้อล่าสุด</h2>
        <a href="#" class="text-sm text-blue-600 hover:underline">ดูทั้งหมด</a>
      </div>
      <div class="mt-4 overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="text-slate-500">
            <tr>
              <th class="py-2 text-left">เลขที่</th>
              <th class="py-2 text-left">วันที่</th>
              <th class="py-2 text-left">ยอดรวม</th>
              <th class="py-2 text-left">สถานะ</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            @forelse ($recentOrders ?? [] as $o)
              <tr>
                <td class="py-2">#{{ $o->order_id }}</td>
                <td class="py-2">{{ \Illuminate\Support\Carbon::parse($o->order_date)->format('d/m/Y H:i') }}</td>
                <td class="py-2">฿{{ number_format($o->total_amount, 2) }}</td>
                <td class="py-2">
                  <span class="px-2 py-0.5 rounded-full text-xs
                    @class([
                      'bg-amber-100 text-amber-700' => $o->status === 'pending',
                      'bg-emerald-100 text-emerald-700' => $o->status === 'completed' || $o->status === 'paid',
                      'bg-blue-100 text-blue-700' => $o->status === 'shipped',
                      'bg-rose-100 text-rose-700' => $o->status === 'canceled',
                    ])">
                    {{ ucfirst($o->status) }}
                  </span>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="py-6 text-center text-slate-500">ยังไม่มีคำสั่งซื้อ</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </main>
</body>
</html> --}}
