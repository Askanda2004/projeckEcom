<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { primary: { DEFAULT: '#2563eb' } },
          boxShadow: { soft: '0 8px 30px rgba(0,0,0,0.08)' }
        }
      }
    }
  </script>
</head>
<body class="bg-slate-50 text-slate-800">

  <!-- Header -->
  <header class="sticky top-0 z-30 bg-white/90 backdrop-blur shadow-soft">
    <div class="max-w-4xl mx-auto px-4 h-14 flex items-center justify-between">
      <h1 class="font-semibold">ยืนยันคำสั่งซื้อ</h1>
      <a href="{{ route('customer.cart') }}" class="text-sm text-slate-600 hover:text-slate-900">กลับตะกร้า</a>
    </div>
  </header>

  <main class="max-w-4xl mx-auto px-4 py-8 space-y-6">

    {{-- Flash messages --}}
    @if (session('status'))
      <div class="rounded-lg bg-emerald-50 text-emerald-800 px-4 py-3 shadow-soft">
        {{ session('status') }}
      </div>
    @endif
    @if (session('error'))
      <div class="rounded-lg bg-rose-50 text-rose-700 px-4 py-3 shadow-soft">
        {{ session('error') }}
      </div>
    @endif

    @php
      // cart: product_id => [name, price, qty, image_url/image, size, color, ...]
      $cart  = $cart  ?? session('cart', []);
      $total = $total ?? collect($cart)->sum(fn($r) => (float)($r['price'] ?? 0) * (int)($r['qty'] ?? 0));
    @endphp

    {{-- รายการสินค้า --}}
    <section class="bg-white rounded-2xl shadow-soft border">
      <div class="p-5 border-b">
        <h2 class="text-base font-semibold">รายการสินค้า</h2>
      </div>

      @if(empty($cart))
        <div class="p-6 text-center text-slate-500">ตะกร้าของคุณว่างเปล่า</div>
      @else
        <div class="divide-y">
          @foreach($cart as $product_id => $row)
            @php
              $name  = $row['name'] ?? '-';
              $price = (float)($row['price'] ?? 0);
              $qty   = (int)  ($row['qty']   ?? 0);
              $sum   = $price * $qty;

              $img    = $row['image_url'] ?? ($row['image'] ?? null);
              $imgSrc = $img
                        ? (\Illuminate\Support\Str::startsWith($img, ['http://','https://'])
                              ? $img
                              : asset('storage/'.$img))
                        : null;
            @endphp
            <div class="p-5 flex items-center justify-between gap-4">
              <div class="flex items-center gap-3 min-w-0">
                @if($imgSrc)
                  <img src="{{ $imgSrc }}" alt="{{ $name }}" class="w-14 h-14 rounded-lg object-cover border">
                @else
                  <div class="w-14 h-14 rounded-lg bg-slate-100 border"></div>
                @endif
                <div class="min-w-0">
                  <div class="font-medium truncate">{{ $name }}</div>
                  <div class="text-xs text-slate-500">
                    ขนาด: {{ $row['size'] ?? '—' }} | สี: {{ $row['color'] ?? '—' }} | จำนวน x{{ $qty }}
                  </div>
                </div>
              </div>
              <div class="text-right">
                <div class="text-sm text-slate-500">฿{{ number_format($price, 2) }}/ชิ้น</div>
                <div class="font-semibold">฿{{ number_format($sum, 2) }}</div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="p-5 border-t flex items-center justify-between">
          <div class="text-slate-500">ยอดรวม</div>
          <div class="text-2xl font-bold">฿{{ number_format($total, 2) }}</div>
        </div>
      @endif
    </section>

    {{-- ฟอร์มที่อยู่จัดส่ง / ยืนยันคำสั่งซื้อ --}}
    <section class="bg-white rounded-2xl shadow-soft border">
      <div class="p-5 border-b">
        <h2 class="text-base font-semibold">ที่อยู่จัดส่ง</h2>
      </div>

      <form method="POST" action="{{ route('customer.checkout.place') }}" class="p-5 space-y-4">
        @csrf

        <div>
          <label class="block text-sm font-medium">ชื่อผู้รับ</label>
          <input
            name="shipping_name"
            value="{{ old('shipping_name', auth()->user()->name ?? '') }}"
            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-2 focus:ring-blue-100"
            required>
          @error('shipping_name') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium">เบอร์โทร</label>
          <input
            name="shipping_phone"
            value="{{ old('shipping_phone') }}"
            placeholder="0812345678"
            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-2 focus:ring-blue-100"
            required>
          @error('shipping_phone') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium">ที่อยู่จัดส่ง</label>
          <textarea
            name="shipping_address"
            rows="3"
            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-2 focus:ring-blue-100"
            required>{{ old('shipping_address') }}</textarea>
          @error('shipping_address') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <label class="inline-flex items-center gap-2 text-sm">
          <input type="checkbox" name="confirm" value="1"
                 class="rounded border-slate-300"
                 {{ old('confirm') ? 'checked' : '' }}>
          ยืนยันการสั่งซื้อ
        </label>
        @error('confirm') <p class="text-sm text-rose-600 -mt-2">{{ $message }}</p> @enderror

        <div class="pt-2 flex gap-3">
          <a href="{{ route('customer.cart') }}" class="flex-1 text-center rounded-xl border border-slate-200 px-4 py-2.5 hover:bg-slate-50">กลับไปตะกร้า</a>
          <button class="flex-1 rounded-xl bg-primary text-white px-5 py-2.5 hover:bg-blue-700">ยืนยันสั่งซื้อ</button>
        </div>
      </form>
    </section>

  </main>
</body>
</html>
