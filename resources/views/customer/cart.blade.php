<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ตะกร้าสินค้า • {{ config('app.name','แพลตฟอร์มร้านผ้าคลุม') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Tailwind via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
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
  <style>
    .line-clamp-2{ display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
  </style>
</head>
<body class="bg-sand text-ink antialiased">

<main class="max-w-7xl mx-auto px-4 py-8">
  <div class="mb-6 flex items-center justify-between gap-3">
    <h1 class="text-xl sm:text-2xl font-semibold">ตะกร้าสินค้า</h1>
    <a href="{{ route('customer.shop') }}" class="inline-flex items-center gap-2 hover:opacity-80">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 5l-7 7 7 7" stroke-width="2"/></svg>
      <span>เลือกซื้อสินค้าต่อ</span>
    </a>
  </div>

  @if (session('status'))
    <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 px-4 py-2 border border-emerald-200">
      {{ session('status') }}
    </div>
  @endif
  @if (session('error'))
    <div class="mb-4 rounded-xl bg-rose-50 text-rose-700 px-4 py-2 border border-rose-200">
      {{ session('error') }}
    </div>
  @endif

  @php
    $cart  = $cart  ?? [];
    $total = (float)($total ?? 0);
  @endphp

  @if(empty($cart))
    <div class="rounded-2xl bg-white border border-slate-100 shadow-soft p-10 text-center">
      <div class="mx-auto w-12 h-12 rounded-full bg-olive/10 text-olive grid place-items-center mb-3">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <circle cx="9" cy="20" r="1"/><circle cx="16" cy="20" r="1"/><path d="M3 3h2l.4 2M7 13h9l3-8H6.4" stroke-width="2"/>
        </svg>
      </div>
      <div class="font-medium">ตะกร้ายังว่างอยู่</div>
      <p class="text-slate-600 text-sm mt-1">เริ่มช้อปสินค้าที่คุณชอบได้เลย</p>
      <a href="{{ route('customer.shop') }}" class="mt-4 inline-flex items-center justify-center h-11 px-5 rounded-xl bg-ink text-white hover:opacity-90">ไปหน้าร้าน</a>
    </div>
  @else
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- รายการสินค้า -->
      <section class="lg:col-span-8 space-y-4">
        {{-- $cart: product_id => [name, price, qty, image_url/image, size, color, stock] --}}
        @foreach($cart as $product_id => $row)
          @php
            $price    = (float)($row['price'] ?? 0);
            $qty      = max(1, (int)($row['qty'] ?? 1));

            // ดึงจำนวนคงเหลือจากตะกร้า (ถ้าคุณ push มาแล้ว) หรือ fallback 999
            // แนะนำให้ใส่ 'stock' ลงใน $cart เวลาบิลด์ตะกร้าเสมอ
            $stockRaw = $row['stock'] ?? $row['available'] ?? 999;
            $stock    = max(0, (int)$stockRaw);
            $maxQty   = max(1, min($stock, 999));

            // ถ้า qty เกินสต็อก ให้ clamp เฉย ๆ เวลาแสดงผล
            if ($qty > $maxQty) { $qty = $maxQty; }
            $sum = $price * $qty;

            $img    = $row['image_url'] ?? ($row['image'] ?? null);
            $imgSrc = $img
                      ? (\Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/'.$img))
                      : null;
          @endphp

          <article class="bg-white rounded-2xl border border-slate-100 shadow-soft p-4">
            <div class="grid grid-cols-12 gap-4">
              <div class="col-span-3 sm:col-span-2">
                <div class="aspect-square rounded-xl overflow-hidden bg-sand border border-slate-100">
                  @if($imgSrc)
                    <img src="{{ $imgSrc }}" alt="{{ $row['name'] ?? 'product' }}" class="w-full h-full object-cover object-center">
                  @endif
                </div>
              </div>

              <div class="col-span-9 sm:col-span-10 flex flex-col">
                <div class="flex items-start justify-between gap-3">
                  <div class="min-w-0">
                    <h2 class="font-medium leading-snug line-clamp-2">{{ $row['name'] ?? '-' }}</h2>
                    <p class="text-xs text-slate-600 mt-1">
                      ขนาด: {{ $row['size'] ?? '—' }} • สี: {{ $row['color'] ?? '—' }}
                    </p>
                    <span class="text-[11px] px-2 py-0.5 rounded-full border
                        {{ $stock > 0 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                        {{ $stock > 0 ? 'พร้อมส่ง · เหลือ ' . $stock : 'หมดชั่วคราว' }}
                      </span>
                  </div>

                  <form method="POST" action="{{ route('customer.cart.remove', $product_id) }}"
                        onsubmit="return confirm('ลบรายการนี้?')">
                    @csrf @method('DELETE')
                    <button class="text-rose-600 hover:underline text-sm">ลบ</button>
                  </form>
                </div>

                <div class="mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                  <div class="text-ink">
                    <div class="text-sm text-slate-600">ราคา</div>
                    <div class="font-semibold">฿{{ number_format($price, 2) }}</div>
                  </div>

                  <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('customer.cart.update', $product_id) }}" class="flex items-center gap-2">
                      @csrf @method('PATCH')

                      <div
                        class="qty-wrap flex items-center border border-slate-200 rounded-xl overflow-hidden"
                        data-min="1" data-max="{{ $maxQty }}"
                      >
                        <button type="button" class="btn-dec px-3 py-2 text-slate-600 hover:bg-slate-100">−</button>
                        <input
                          type="number" name="qty"
                          class="qty-input w-16 text-center border-x border-slate-200 focus:outline-none"
                          value="{{ $qty }}" min="1" max="{{ $maxQty }}"
                        >
                        <button type="button" class="btn-inc px-3 py-2 text-slate-600 hover:bg-slate-100">+</button>
                      </div>

                      <button class="h-10 px-3 rounded-xl border border-slate-200 hover:border-ink/30 text-sm">
                        อัปเดต
                      </button>
                    </form>

                    <div class="ml-auto sm:ml-0 text-right">
                      <div class="text-sm text-slate-600">รวม</div>
                      <div class="font-semibold">฿{{ number_format($sum, 2) }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </article>
        @endforeach

        <form method="POST" action="{{ route('customer.cart.clear') }}" onsubmit="return confirm('ล้างตะกร้าทั้งหมด?')">
          @csrf @method('DELETE')
          <button class="text-slate-600 hover:opacity-80 text-sm">ล้างตะกร้า</button>
        </form>
      </section>

      <!-- สรุปคำสั่งซื้อ -->
      <aside class="lg:col-span-4">
        <div class="lg:sticky lg:top-24 bg-white rounded-2xl border border-slate-100 shadow-soft p-5 space-y-4">
          <h3 class="font-semibold">สรุปคำสั่งซื้อ</h3>

          <div class="space-y-2 text-sm">
            <div class="flex items-center justify-between">
              <span class="text-slate-600">ยอดรวม</span>
              <span class="font-medium">฿{{ number_format($total, 2) }}</span>
            </div>
          </div>

          <a href="{{ route('customer.checkout') }}"
             class="block w-full h-11 rounded-xl bg-ink text-white grid place-items-center hover:opacity-90">
            ไปชำระเงิน
          </a>

          <p class="text-xs text-slate-600">
            * ตรวจสอบจำนวนและสีให้ถูกต้องก่อนชำระเงิน หากต้องการแก้ไขสามารถปรับจำนวนหรือกดลบได้ที่รายการสินค้า
          </p>
        </div>
      </aside>
    </div>
  @endif
</main>

<script>
  // Qty stepper: บังคับไม่เกินสต็อก (data-max) และไม่ต่ำกว่า (data-min)
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.qty-wrap').forEach(wrap => {
      const input = wrap.querySelector('.qty-input');
      const dec   = wrap.querySelector('.btn-dec');
      const inc   = wrap.querySelector('.btn-inc');
      const min   = Number(wrap.dataset.min || 1);
      const max   = Number(wrap.dataset.max || 999);

      function clamp(showAlert = false) {
        let v = Number(input.value || 0);
        if (isNaN(v)) v = min;
        if (v < min) v = min;
        if (v > max) {
          v = max;
          if (showAlert) alert(`จำนวนสูงสุดคือ ${max} ชิ้น (ตามสต็อกสินค้า)`);
        }
        input.value = v;

        dec.disabled = v <= min;
        inc.disabled = v >= max;
        dec.classList.toggle('opacity-40', dec.disabled);
        inc.classList.toggle('opacity-40', inc.disabled);
      }

      dec.addEventListener('click', () => { input.stepDown(); clamp(); });
      inc.addEventListener('click', () => { input.stepUp();   clamp(true); });
      input.addEventListener('input', () => clamp());

      clamp();
    });
  });
</script>
</body>
</html>
