<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ประวัติการสั่งซื้อ • My Shop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // โทนมินิมอล: sand/ink/olive + เงานุ่ม
    tailwind.config = {
      theme: {
        extend: {
          colors: { sand:'#FAFAF7', ink:'#111827', olive:'#7C8B6A', primary:{DEFAULT:'#2563eb'} },
          boxShadow: { soft:'0 6px 24px rgba(0,0,0,0.06)' },
          borderRadius: { xl2:'1rem' }
        }
      }
    }
  </script>
</head>
<body class="bg-sand text-ink antialiased">
  <!-- พื้นหลัง glass ไล่เฉด -->
  <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10 bg-glass"></div>

  <!-- Header -->
  <header class="sticky top-0 z-30 bg-white/85 backdrop-blur shadow-soft border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between gap-3">
      <a href="{{ route('customer.shop') }}" class="font-semibold text-slate-700 hover:text-primary transition">&larr; กลับไปหน้าร้าน</a>
      <div class="flex items-center gap-2">
        <a href="{{ route('customer.cart') }}" class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-100">ตะกร้า</a>
        <form method="POST" action="{{ route('logout') }}"> @csrf
          <button class="rounded-lg bg-slate-900 text-white px-3 py-1.5 hover:bg-slate-800">ออกจากระบบ</button>
        </form>
      </div>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-5">
      <h1 class="text-2xl font-bold text-slate-800">ประวัติการสั่งซื้อของฉัน</h1>
      <p class="text-sm text-slate-500">ดูรายการที่สั่งซื้อทั้งหมดของบัญชีนี้</p>
    </div>

    <div class="bg-white rounded-2xl shadow-soft overflow-hidden border border-slate-100">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-50 text-slate-600">
            <tr>
              <th class="px-4 py-3 text-left font-semibold border-b">วันที่สั่งซื้อ</th>
              <th class="px-4 py-3 text-left font-semibold border-b">ที่อยู่จัดส่ง</th>
              <th class="px-4 py-3 text-right font-semibold border-b">ยอดรวม</th>
              <th class="px-4 py-3 text-center font-semibold border-b">สถานะ</th>
              <th class="px-4 py-3 text-center font-semibold border-b">รายละเอียด</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($orders as $o)
              <tr class="hover:bg-slate-50 transition">
                <td class="px-4 py-3 border-b">
                  #{{ $o->order_id ?? $o->id }} - {{ \Illuminate\Support\Carbon::parse($o->order_date ?? $o->created_at)->format('d/m/Y H:i') }}
                </td>
                <td class="px-4 py-3 border-b align-top">
                  <div class="flex flex-col leading-tight">
                    {{-- ชื่อผู้รับ --}}
                    <div class="text-sm font-semibold text-slate-800 flex items-center gap-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A10.97 10.97 0 0112 15c2.28 0 4.374.72 6.121 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      {{ $o->shipping_name ?? '—' }}
                    </div>

                    {{-- เบอร์โทร --}}
                    <div class="text-xs text-slate-600 flex items-center gap-1 mt-0.5">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-.59 1.41l-1.41 1.41a16 16 0 007.07 7.07l1.41-1.41A2 2 0 0115 17h2a2 2 0 012 2v2a2 2 0 01-2 2h-2C9.373 23 1 14.627 1 5V3a2 2 0 012-2z" />
                      </svg>
                      {{ $o->shipping_phone ?? '—' }}
                    </div>

                    {{-- ที่อยู่ --}}
                    <div class="text-xs text-slate-500 mt-1">
                      {{ \Illuminate\Support\Str::limit($o->shipping_address ?? '—', 90) }}
                    </div>
                  </div>
                </td>
                <td class="px-4 py-3 border-b text-right font-semibold">
                  ฿{{ number_format((float)($o->total_amount ?? 0), 2) }}
                </td>
                <td class="px-4 py-3 border-b text-center">
                  @php
                    $status = $o->status ?? 'pending';

                    switch ($status) {
                      case 'paid':
                      case 'completed':
                        $badge = 'bg-emerald-500/10 text-emerald-700 border border-emerald-300';
                        $icon  = '';
                        $label = 'ชำระแล้ว';
                        break;

                      case 'processing':
                      case 'shipped':
                        $badge = 'bg-sky-500/10 text-sky-700 border border-sky-300';
                        $icon  = '';
                        $label = 'กำลังจัดส่ง';
                        break;

                      case 'cancelled':
                        $badge = 'bg-rose-500/10 text-rose-700 border border-rose-300';
                        $icon  = '';
                        $label = 'ยกเลิกแล้ว';
                        break;

                      default:
                        $badge = 'bg-amber-500/10 text-amber-700 border border-amber-300 animate-pulse';
                        $icon  = '';
                        $label = 'รอดำเนินการ';
                        break;
                    }
                  @endphp

                  <span class="inline-flex items-center justify-center gap-1 text-xs font-semibold
                              px-3 py-1.5 rounded-full whitespace-nowrap {{ $badge }}">
                    {{ $icon }} {{ $label }}
                  </span>
                </td>

                <td class="px-4 py-3 border-b text-center">
                  <button type="button"
                          class="rounded-lg border px-3 py-1.5 text-sm hover:bg-slate-50"
                          onclick="document.getElementById('dlg-{{ $o->order_id ?? $o->id }}').showModal()">
                    ดูรายละเอียด
                  </button>

                  <!-- Modal รายละเอียดคำสั่งซื้อ -->
                  <dialog id="dlg-{{ $o->order_id ?? $o->id }}" class="rounded-2xl p-0 w-full max-w-2xl">
                    <form method="dialog">
                      <div class="p-4 sm:p-6 border-b">
                        <div class="flex items-center justify-between">
                          <h3 class="font-semibold">
                            รายละเอียดคำสั่งซื้อ #{{ $o->order_id ?? $o->id }}
                            <span class="ml-2 text-sm text-slate-500">
                              ({{ \Illuminate\Support\Carbon::parse($o->order_date ?? $o->created_at)->format('d/m/Y H:i') }})
                            </span>
                          </h3>
                          <button class="rounded-lg border px-3 py-1.5 text-sm hover:bg-slate-50">ปิด</button>
                        </div>
                        <div class="mt-1 text-sm text-slate-600">
                          ผู้รับ: {{ $o->shipping_name ?? '—' }} • {{ $o->shipping_phone ?? '—' }}<br>
                          ที่อยู่: {{ $o->shipping_address ?? '—' }}
                        </div>
                      </div>

                      <div class="p-4 sm:p-6 overflow-x-auto">
                        <table class="min-w-full text-sm">
                          <thead class="bg-slate-50">
                            <tr>
                              <th class="px-3 py-2 text-left">สินค้า</th>
                              <th class="px-3 py-2 text-left">ตัวเลือก</th>
                              <th class="px-3 py-2 text-left">ราคา</th>
                              <th class="px-3 py-2 text-left">จำนวน</th>
                              <th class="px-3 py-2 text-right">รวม</th>
                            </tr>
                          </thead>
                          <tbody class="divide-y">
                            @forelse ($o->items ?? [] as $it)
                              @php
                                $prod = $it->product;
                                $thumb = $prod?->image_url;
                                if ($prod && method_exists($prod, 'images')) {
                                  $primary = $prod->relationLoaded('images')
                                    ? ($prod->images->firstWhere('is_primary', true) ?? $prod->images->first())
                                    : $prod->images()->orderByDesc('is_primary')->orderBy('ordering')->first();
                                  if ($primary) $thumb = $primary->path;
                                }
                              @endphp
                              <tr>
                                <td class="px-3 py-2">
                                  <div class="flex items-center gap-3">
                                    @if ($thumb)
                                      <img src="{{ asset('storage/'.$thumb) }}" class="w-10 h-10 rounded object-cover border">
                                    @else
                                      <div class="w-10 h-10 rounded bg-slate-100 border"></div>
                                    @endif
                                    <div class="font-medium">{{ $prod->name ?? '—' }}</div>
                                  </div>
                                </td>
                                <td class="px-3 py-2 text-slate-600">
                                  ขนาด: {{ $prod->size ?? '—' }} • สี: {{ $prod->color ?? '—' }}
                                </td>
                                <td class="px-3 py-2">฿{{ number_format((float)$it->price, 2) }}</td>
                                <td class="px-3 py-2">x{{ (int)$it->quantity }}</td>
                                <td class="px-3 py-2 text-right">
                                  ฿{{ number_format((float)$it->price * (int)$it->quantity, 2) }}
                                </td>
                              </tr>
                            @empty
                              <tr>
                                <td colspan="5" class="px-3 py-6 text-center text-slate-500">ไม่มีรายการสินค้า</td>
                              </tr>
                            @endforelse
                          </tbody>
                        </table>

                        <div class="mt-4 text-right">
                          <div class="text-slate-500 text-sm">ยอดรวม</div>
                          <div class="text-xl font-bold">฿{{ number_format((float)($o->total_amount ?? 0), 2) }}</div>
                        </div>
                      </div>

                      <div class="p-4 sm:p-6 border-t flex justify-end">
                        <button class="rounded-lg border px-4 py-2 hover:bg-slate-50">ปิด</button>
                      </div>
                    </form>
                  </dialog>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-4 py-8 text-center text-slate-500">ยังไม่เคยสั่งซื้อ</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="p-4 sm:p-6 border-t border-slate-100">
        {{ $orders->links() }}
      </div>
    </div>
  </main>
</body>
</html>
