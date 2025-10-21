<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ตรวจสอบสลิป • Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
<main class="max-w-6xl mx-auto p-6 space-y-6">
  <a href="{{ route('admin.payments.index') }}" class="text-slate-600 hover:underline">&larr; กลับ</a>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    {{-- ซ้าย: สลิปชำระเงิน --}}
    <section class="lg:col-span-5">
      <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-lg font-semibold mb-3">สลิปชำระเงิน</h2>
        @php
          $slip = $order->payment_slip ?? null;
          $slipUrl = $slip
            ? (\Illuminate\Support\Str::startsWith($slip, ['http://','https://']) ? $slip : asset('storage/'.$slip))
            : null;
        @endphp

        @if($slipUrl)
          <img src="{{ $slipUrl }}" alt="Payment Slip" class="rounded-lg shadow max-h-[70vh] w-full object-contain border">
          <div class="mt-3 flex gap-2">
            <a href="{{ $slipUrl }}" target="_blank" class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm">เปิดเต็มจอ</a>
            <a href="{{ $slipUrl }}" download class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm">ดาวน์โหลด</a>
          </div>
        @else
          <div class="aspect-[4/5] grid place-items-center bg-slate-100 border rounded-lg text-slate-500">
            ไม่มีสลิปแนบมา
          </div>
        @endif

        <div class="mt-6 flex gap-3">
          <form method="POST" action="{{ route('admin.payments.verify', $order->order_id ?? $order->id) }}">
            @csrf @method('PATCH')
            <button class="rounded-lg bg-emerald-600 text-white px-4 py-2 hover:bg-emerald-700">✅ ยืนยันการชำระเงิน</button>
          </form>

          {{-- ปุ่มปฏิเสธ (เปิดใช้เมื่อพร้อม) --}}
          {{-- <form method="POST" action="{{ route('admin.payments.reject', $order->order_id ?? $order->id) }}">
            @csrf @method('PATCH')
            <button class="rounded-lg bg-rose-600 text-white px-4 py-2 hover:bg-rose-700">❌ ปฏิเสธ</button>
          </form> --}}
        </div>
      </div>
    </section>

    {{-- ขวา: รายละเอียดคำสั่งซื้อ + ตารางสินค้า --}}
    <section class="lg:col-span-7">
      <div class="bg-white rounded-2xl shadow overflow-hidden">
        {{-- ส่วนหัวสรุป --}}
        <div class="p-6 border-b">
          <h1 class="text-xl font-bold">
            รายละเอียดการสั่งซื้อ
            <span class="ml-2 text-sm text-slate-500">
              #{{ $order->order_id ?? $order->id }}
              - ({{ \Illuminate\Support\Carbon::parse($order->order_date)->timezone('Asia/Bangkok')->format('d/m/Y H:i') }})
            </span>
          </h1>

          <div class="mt-2 text-sm text-slate-600 space-y-1">
            <p>ผู้สั่งซื้อ: {{ $order->shipping_name ?? '—' }}</p>
            <p>เบอร์โทร: {{ $order->shipping_phone ?? '—' }}</p>
            <p>ที่อยู่: {{ $order->shipping_address ?? '—' }}</p>
            <p>สถานะชำระเงิน: <span class="font-semibold uppercase">{{ $order->payment_status ?? 'pending' }}</span></p>
          </div>
        </div>

        {{-- ตารางสินค้า --}}
        <div class="p-6 overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-3 py-2 text-left">สินค้า</th>
                <th class="px-3 py-2 text-left">ราคา</th>
                <th class="px-3 py-2 text-left">จำนวน</th>
                <th class="px-3 py-2 text-right">รวม</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              @forelse ($order->items ?? [] as $it)
                @php
                  $p = $it->product ?? null;
                  $thumb = $p?->image_url
                    ? (\Illuminate\Support\Str::startsWith($p->image_url, ['http://','https://']) ? $p->image_url : asset('storage/'.$p->image_url))
                    : null;
                @endphp
                <tr>
                  <td class="px-3 py-2">
                    <div class="flex items-center gap-3">
                      @if ($thumb)
                        <img src="{{ $thumb }}" class="w-10 h-10 rounded object-cover border" alt="">
                      @else
                        <div class="w-10 h-10 rounded bg-slate-100 border"></div>
                      @endif
                      <div>
                        <div class="font-medium">{{ $p->name ?? $it->name ?? '—' }}</div>
                        <div class="text-xs text-slate-500">
                          ขนาด: {{ $p->size ?? '—' }} • สี: {{ $p->color ?? '—' }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-3 py-2">฿{{ number_format((float)$it->price, 2) }}</td>
                  <td class="px-3 py-2">{{ $it->quantity }}</td>
                  <td class="px-3 py-2 text-right">฿{{ number_format((float)$it->price * (int)$it->quantity, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="px-3 py-6 text-center text-slate-500">ไม่มีรายการสินค้า</td>
                </tr>
              @endforelse
            </tbody>
          </table>

          <div class="mt-4 text-right">
            <div class="text-slate-500 text-sm">ยอดรวม</div>
            <div class="text-xl font-bold">฿{{ number_format((float)$order->total_amount, 2) }}</div>
          </div>
        </div>
      </div>
    </section>
  </div>
</main>
</body>
</html>
