<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>ตรวจสอบสลิป • Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // --- 2. ใช้ Design System กลาง (ชุดเดียวกับ index) ---
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

<main class="max-w-7xl mx-auto px-4 py-6 space-y-6">
  <div class="flex items-center justify-between">
    <a href="{{ route('admin.payments.index') }}" class="text-neutral-600 hover:underline">&larr; กลับรายการชำระเงิน</a>

    @php
      $status = $order->payment_status ?? 'pending';
      // (คงเดิม) Badge สียังคงเดิมเพราะสื่อความหมายชัดเจน
      $badge  = $status === 'verified' ? 'bg-emerald-100 text-emerald-700'
              : ($status === 'rejected' ? 'bg-rose-100 text-rose-700'
              : 'bg-amber-100 text-amber-700');
      $disabled = in_array($status, ['verified','rejected'], true);
    @endphp

    <span class="text-sm">
      สถานะปัจจุบัน:
      <span class="px-2 py-0.5 rounded-full text-xs {{ $badge }}">{{ $status }}</span>
    </span>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    {{-- ซ้าย: สลิปชำระเงิน --}}
    <section class="lg:col-span-5">
      <div class="bg-white rounded-2xl shadow-soft p-6">
        <h2 class="text-lg font-semibold mb-3">สลิปชำระเงิน</h2>

        @php
          $slip = $order->payment_slip ?? null;
          $slipUrl = $slip
            ? (\Illuminate\Support\Str::startsWith($slip, ['http://','https://']) ? $slip : asset('storage/'.$slip))
            : null;
        @endphp

        @if($slipUrl)
          <img src="{{ $slipUrl }}" alt="Payment Slip"
               class="rounded-lg shadow max-h-[70vh] w-full object-contain border border-neutral-200">
          <div class="mt-3 flex gap-2">
            <a href="{{ $slipUrl }}" target="_blank" class="rounded-lg border border-neutral-300 px-3 py-2 text-sm font-medium text-ink hover:bg-neutral-100 transition-colors">เปิดเต็มจอ</a>
            <a href="{{ $slipUrl }}" download class="rounded-lg border border-neutral-300 px-3 py-2 text-sm font-medium text-ink hover:bg-neutral-100 transition-colors">ดาวน์โหลด</a>
          </div>
        @else
          <div class="aspect-[4/5] grid place-items-center bg-neutral-50 border border-neutral-200 rounded-lg text-neutral-500">
            ไม่มีสลิปแนบมา
          </div>
        @endif

        <div class="mt-6 flex gap-3">
          {{-- (คงเดิม) ปุ่มสีเขียว/แดง สำหรับ Verify/Reject นั้นถูกต้องตามหลัก UX แล้ว --}}
          <form method="POST" action="{{ route('admin.payments.verify', $order->order_id ?? $order->id) }}">
            @csrf @method('PATCH')
            <button
              class="rounded-lg bg-emerald-600 text-white px-4 py-2 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
              {{ $disabled ? 'disabled' : '' }}>
              ✅ ยืนยันการชำระเงิน
            </button>
          </form>

          {{-- ตัวอย่างปุ่มปฏิเสธ (หากเปิดใช้งาน) --}}
          {{-- @if(Route::has('admin.payments.reject'))
            <form method="POST" action="{{ route('admin.payments.reject', $order->order_id ?? $order->id) }}">
              @csrf @method('PATCH')
              <button
                class="rounded-lg bg-rose-600 text-white px-4 py-2 hover:bg-rose-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                {{ $disabled ? 'disabled' : '' }}>
                ❌ ปฏิเสธ
              </button>
            </form>
          @endif --}}
        </div>
      </div>
    </section>

    {{-- ขวา: รายละเอียดคำสั่งซื้อ + ตารางสินค้า --}}
    <section class="lg:col-span-7">
      <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        {{-- ส่วนหัวสรุป --}}
        <div class="p-6 border-b border-neutral-200">
          <h1 class="text-xl font-bold">
            รายละเอียดคำสั่งซื้อ
            <span class="ml-2 text-sm text-neutral-500">
              #{{ $order->order_id ?? $order->id }}
              • {{ \Illuminate\Support\Carbon::parse($order->order_date)->timezone('Asia/Bangkok')->format('d/m/Y H:i') }}
            </span>
          </h1>

          <div class="mt-2 text-sm text-neutral-600 grid sm:grid-cols-2 gap-y-1 gap-x-6">
            <p>ผู้สั่งซื้อ: <span class="font-medium text-ink">{{ $order->shipping_name ?? '—' }}</span></p>
            <p>เบอร์โทร: <span class="font-medium text-ink">{{ $order->shipping_phone ?? '—' }}</span></p>
            <p class="sm:col-span-2">ที่อยู่: <span class="font-medium text-ink">{{ $order->shipping_address ?? '—' }}</span></p>
          </div>
        </div>

        {{-- ตารางสินค้า --}}
        <div class="p-6 overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-neutral-50">
              <tr>
                <th class="px-3 py-2 text-left">สินค้า</th>
                <th class="px-3 py-2 text-left">ราคา</th>
                <th class="px-3 py-2 text-left">จำนวน</th>
                <th class="px-3 py-2 text-right">รวม</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100">
              @forelse(($order->items ?? []) as $it)
                @php
                  $p = $it->product ?? null;
                  $thumb = $p?->image_url
                    ? (\Illuminate\Support\Str::startsWith($p->image_url, ['http://','https://']) ? $p->image_url : asset('storage/'.$p->image_url))
                    : null;
                @endphp
                <tr>
                  <td class="px-3 py-2">
                    <div class="flex items-center gap-3">
                      @if($thumb)
                        <img src="{{ $thumb }}" class="w-10 h-10 rounded object-cover border border-neutral-200" alt="">
                      @else
                        <div class="w-10 h-10 rounded bg-neutral-100 border border-neutral-200"></div>
                      @endif
                      <div>
                        <div class="font-medium">{{ $p->name ?? $it->name ?? '—' }}</div>
                        <div class="text-xs text-neutral-500">
                          ขนาด: {{ $p->size ?? '—' }} • สี: {{ $p->color ?? '—' }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-3 py-2">฿{{ number_format((float)$it->price, 2) }}</td>
                  <td class="px-3 py-2">x{{ (int)$it->quantity }}</td>
                  <td class="px-3 py-2 text-right">
                    ฿{{ number_format((float)$it->price * (int)$it->quantity, 2) }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="px-3 py-6 text-center text-neutral-500">ไม่มีรายการสินค้า</td>
                </tr>
              @endforelse
            </tbody>
          </table>

          <div class="mt-4 text-right">
            <div class="text-neutral-500 text-sm">ยอดรวม</div>
            <div class="text-xl font-bold">฿{{ number_format((float)$order->total_amount, 2) }}</div>
          </div>
        </div>
      </div>
    </section>
  </div>
</main>

</body>
</html>