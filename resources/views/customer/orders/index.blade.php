<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ประวัติการสั่งซื้อ • My Shop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme:{ extend:{ colors:{ primary:{DEFAULT:'#2563eb'} }, boxShadow:{soft:'0 8px 30px rgba(0,0,0,0.08)'} } } }
  </script>
</head>
<body class="bg-slate-50 text-slate-800">

  <!-- Header -->
  <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between gap-3">
      <a href="{{ route('customer.shop') }}" class="font-semibold">&larr; กลับไปหน้าร้าน</a>
      <div class="flex items-center gap-2">
        <a href="{{ route('customer.cart') }}" class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-100">ตะกร้า</a>
        <form method="POST" action="{{ route('logout') }}"> @csrf
          <button class="rounded-lg bg-slate-900 text-white px-3 py-1.5 hover:bg-slate-800">Logout</button>
        </form>
      </div>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-4">
      <h1 class="text-xl font-bold">ประวัติการสั่งซื้อของฉัน</h1>
      <p class="text-sm text-slate-500">ดูรายการที่สั่งซื้อทั้งหมดของบัญชีนี้</p>
    </div>

    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
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
              <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 border-b">
                    {{ \Illuminate\Support\Carbon::parse($o->order_date ?? $o->created_at)->format('d/m/Y H:i') }}
                  {{-- <div class="font-semibold">#{{ $o->order_id ?? $o->id }}</div>
                  <div class="text-xs text-slate-500">
                    {{ \Illuminate\Support\Carbon::parse($o->order_date ?? $o->created_at)->format('d/m/Y H:i') }}
                  </div> --}}
                </td>
                <td class="px-4 py-3 border-b">
                  <div class="text-sm">{{ $o->shipping_name ?? '—' }} • {{ $o->shipping_phone ?? '—' }}</div>
                  <div class="text-xs text-slate-500">
                    {{ \Illuminate\Support\Str::limit($o->shipping_address ?? '—', 90) }}
                  </div>
                </td>
                <td class="px-4 py-3 border-b text-right font-semibold">
                  ฿{{ number_format((float)($o->total_amount ?? 0), 2) }}
                </td>
                <td class="px-4 py-3 border-b text-center">
                  @php
                    $map = $statusMap[$o->status] ?? $o->status;
                    $cls = match($o->status) {
                      'paid','completed'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                      'processing','shipped' => 'bg-sky-50 text-sky-700 border-sky-200',
                      'cancelled'          => 'bg-rose-50 text-rose-700 border-rose-200',
                      default              => 'bg-amber-50 text-amber-700 border-amber-200',
                    };
                  @endphp
                  <span class="text-[11px] px-2 py-0.5 rounded-full border {{ $cls }}">{{ $map }}</span>
                </td>
                <td class="px-4 py-3 border-b text-center">
                  <button type="button"
                          class="rounded-lg border px-3 py-1.5 text-sm hover:bg-slate-50"
                          onclick="document.getElementById('dlg-{{ $o->order_id ?? $o->id }}').showModal()">
                    ดูรายละเอียด
                  </button>

                  {{-- Modal รายการสินค้าในคำสั่งซื้อ --}}
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
                                // เลือกรูปหลักจาก product_images ถ้ามี
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
