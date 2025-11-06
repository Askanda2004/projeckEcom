<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ยืนยันคำสั่งซื้อ • {{ config('app.name','แพลตฟอร์มร้านผ้าคลุม') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { sand:'#FAFAF7', ink:'#111827', olive:'#7C8B6A', primary:{DEFAULT:'#2563eb'} },
          boxShadow: { soft:'0 6px 24px rgba(0,0,0,0.06)' }
        }
      }
    }
  </script>
</head>
<body class="bg-sand text-ink antialiased">

  <!-- Header -->
  <header class="sticky top-0 z-30 bg-white/90 backdrop-blur border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 h-14 flex items-center justify-between">
      <h1 class="font-semibold">ยืนยันคำสั่งซื้อ</h1>
      <a href="{{ route('customer.cart') }}" class="text-sm hover:opacity-80">กลับตะกร้า</a>
    </div>
  </header>

  @php
    // cart: product_id => [name, price, qty, image_url/image, size, color, ...]
    $cart  = $cart  ?? session('cart', []);
    $total = (float)($total ?? collect($cart)->sum(fn($r) => (float)($r['price'] ?? 0) * (int)($r['qty'] ?? 0)));
  @endphp

  <main class="max-w-7xl mx-auto px-4 py-8">
    @if (session('status'))
      <div class="mb-6 rounded-xl bg-emerald-50 text-emerald-700 px-4 py-3 border border-emerald-200 shadow-soft">
        {{ session('status') }}
      </div>
    @endif
    @if (session('error'))
      <div class="mb-6 rounded-xl bg-rose-50 text-rose-700 px-4 py-3 border border-rose-200 shadow-soft">
        {{ session('error') }}
      </div>
    @endif

    @if(empty($cart))
      <div class="rounded-2xl bg-white border border-slate-100 shadow-soft p-10 text-center">
        <div class="mx-auto w-12 h-12 rounded-full bg-olive/10 text-olive grid place-items-center mb-3">
          <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="9" cy="20" r="1"/><circle cx="16" cy="20" r="1"/><path d="M3 3h2l.4 2M7 13h9l3-8H6.4" stroke-width="2"/>
          </svg>
        </div>
        <div class="font-medium">ตะกร้าของคุณว่างเปล่า</div>
        <a href="{{ route('customer.shop') }}" class="mt-4 inline-flex items-center justify-center h-11 px-5 rounded-xl bg-ink text-white hover:opacity-90">เลือกสินค้า</a>
      </div>
    @else
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- ฟอร์มที่อยู่ + ชำระเงิน -->
        <section class="lg:col-span-7">
          <div class="bg-white rounded-2xl border border-slate-100 shadow-soft">
            <div class="p-5 border-b border-slate-100">
              <h2 class="text-base font-semibold">ข้อมูลจัดส่ง & การชำระเงิน</h2>
            </div>

            <form id="checkoutForm" method="POST" action="{{ route('customer.checkout.place') }}" enctype="multipart/form-data" class="p-5 space-y-4">
              @csrf

              <!-- ชื่อผู้รับ -->
              <div>
                <label class="block text-sm font-medium">ชื่อผู้รับ</label>
                <input
                  name="shipping_name"
                  value="{{ old('shipping_name', auth()->user()->name ?? '') }}"
                  class="mt-1 w-full h-11 rounded-xl border border-slate-200 bg-white/70 focus:bg-white px-3 outline-none focus:ring-2 focus:ring-olive/30"
                  required>
                @error('shipping_name') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
              </div>

              <!-- เบอร์โทร -->
              <div>
                <label class="block text-sm font-medium">เบอร์โทร</label>
                <input
                  type="tel"
                  name="shipping_phone"
                  value="{{ old('shipping_phone') }}"
                  placeholder="0812345678"
                  maxlength="10"
                  pattern="0\d{9}"
                  inputmode="numeric"
                  oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                  class="mt-1 w-full h-11 rounded-xl border border-slate-200 bg-white/70 focus:bg-white px-3 outline-none focus:ring-2 focus:ring-olive/30"
                  required>
                @error('shipping_phone') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
              </div>

              <!-- ที่อยู่จัดส่ง -->
              <div>
                <label class="block text-sm font-medium">ที่อยู่จัดส่ง</label>
                <textarea
                  name="shipping_address"
                  rows="3"
                  class="mt-1 w-full rounded-xl border border-slate-200 bg-white/70 focus:bg-white px-3 py-2 outline-none focus:ring-2 focus:ring-olive/30"
                  required>{{ old('shipping_address') }}</textarea>
                @error('shipping_address') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
              </div>

              <!-- ช่องทางการชำระเงิน (ตัวเลือก, ไม่บังคับฝั่งเซิร์ฟเวอร์) -->
              <div>
                <label class="block text-sm font-medium mb-1">ช่องทางการชำระเงิน</label>
                <select
                  name="payment_method"
                  id="payment_method"
                  class="w-full h-11 rounded-xl border border-slate-200 bg-white/70 focus:bg-white px-3 outline-none focus:ring-2 focus:ring-olive/30">
                  <option value="">— เลือกช่องทาง —</option>
                  <option value="cod"      @selected(old('payment_method')=='cod')>เก็บเงินปลายทาง (COD)</option>
                  <option value="transfer" @selected(old('payment_method')=='transfer')>โอนผ่านบัญชี / QR Code</option>
                </select>
              </div>

              <!-- โอนเงิน/QR + แนบสลิป -->
              <div id="payment_qr_section" class="{{ old('payment_method')=='transfer' ? '' : 'hidden' }}">
                <div class="mt-2 bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                  <p class="text-sm font-medium">สแกน QR เพื่อชำระเงิน</p>
                  <img src="{{ asset('images/QR.jpg') }}" alt="QR Code" class="w-48 h-48 object-contain rounded-lg border bg-white">
                  <p class="text-xs text-slate-600">
                    โปรดชำระยอดรวมทั้งหมด <span class="font-semibold text-ink">฿{{ number_format($total,2) }}</span>
                    แล้วอัปโหลดสลิปการชำระเงินเพื่อยืนยัน
                  </p>

                  <div>
                    <label class="block text-sm font-medium">อัปโหลดหลักฐานการชำระเงิน (รองรับ .jpg .jpeg .png .webp ≤ 4MB)</label>
                    <input
                      type="file"
                      name="payment_slip"
                      id="payment_slip"
                      accept="image/png,image/jpeg,image/jpg,image/webp"
                      class="mt-1 block w-full text-sm rounded-xl border border-slate-200 bg-white/70 focus:bg-white px-3 py-2 outline-none focus:ring-2 focus:ring-olive/30">
                    @error('payment_slip') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror

                    <!-- preview สลิป -->
                    <img id="slip_preview" class="mt-3 hidden w-40 h-40 object-cover rounded-lg border bg-white" alt="ตัวอย่างสลิป">
                  </div>
                </div>
              </div>

              <!-- ยืนยัน -->
              <label class="inline-flex items-center gap-2 text-sm">
                <input type="checkbox" name="confirm" value="1" class="rounded border-slate-300" {{ old('confirm') ? 'checked' : '' }}>
                ยืนยันข้อมูลถูกต้องและยอมรับการสั่งซื้อ
              </label>
              @error('confirm') <p class="text-sm text-rose-600 -mt-2">{{ $message }}</p> @enderror

              <!-- ปุ่ม -->
              <div class="pt-2 grid grid-cols-2 gap-3">
                <a href="{{ route('customer.cart') }}" class="h-11 grid place-items-center rounded-xl border border-slate-200 hover:border-ink/30">
                  กลับตะกร้า
                </a>
                <button id="placeBtn" class="h-11 rounded-xl bg-ink text-white hover:opacity-90">
                  ยืนยันสั่งซื้อ
                </button>
              </div>
            </form>
          </div>
        </section>

        <!-- สรุปคำสั่งซื้อ -->
        <aside class="lg:col-span-5">
          <div class="lg:sticky lg:top-24 bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
            <div class="p-5 border-b border-slate-100">
              <h3 class="font-semibold">สรุปคำสั่งซื้อ</h3>
            </div>

            <div class="max-h-[340px] overflow-auto divide-y">
              @foreach($cart as $product_id => $row)
                @php
                  $name  = $row['name'] ?? '-';
                  $price = (float)($row['price'] ?? 0);
                  $qty   = (int)  ($row['qty']   ?? 0);
                  $sum   = $price * $qty;

                  $img    = $row['image_url'] ?? ($row['image'] ?? null);
                  $imgSrc = $img ? (\Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/'.$img)) : null;
                @endphp
                <div class="p-4 flex items-center gap-3">
                  <div class="w-14 h-14 rounded-lg overflow-hidden bg-sand border border-slate-100 shrink-0">
                    @if($imgSrc)
                      <img src="{{ $imgSrc }}" alt="{{ $name }}" class="w-full h-full object-cover">
                    @endif
                  </div>
                  <div class="min-w-0 flex-1">
                    <div class="text-sm font-medium truncate">{{ $name }}</div>
                    <div class="text-xs text-slate-600 truncate">
                      ขนาด: {{ $row['size'] ?? '—' }} • สี: {{ $row['color'] ?? '—' }}
                    </div>
                  </div>
                  <div class="text-right text-sm">
                    <div class="text-slate-600">x{{ $qty }}</div>
                    <div class="font-medium">฿{{ number_format($sum,2) }}</div>
                  </div>
                </div>
              @endforeach
            </div>

            <div class="p-5 border-t border-slate-100 space-y-2 text-sm">
              <div class="flex items-center justify-between">
                <span class="text-slate-600">ยอดรวม</span>
                <span class="font-medium">฿{{ number_format($total, 2) }}</span>
              </div>
            </div>
          </div>
        </aside>
      </div>
    @endif
  </main>

  <script>
    // toggle QR + บังคับแนบสลิปเมื่อเลือกโอนเงิน
    document.addEventListener('DOMContentLoaded', function() {
      const select = document.getElementById('payment_method');
      const qrBox  = document.getElementById('payment_qr_section');
      const slip   = document.getElementById('payment_slip');
      const preview= document.getElementById('slip_preview');
      const place  = document.getElementById('placeBtn');
      const form   = document.getElementById('checkoutForm');

      function syncQR() {
        const isTransfer = (select?.value === 'transfer');
        qrBox?.classList.toggle('hidden', !isTransfer);
        if (slip) slip.required = isTransfer;
      }
      select?.addEventListener('change', syncQR);
      syncQR();

      // preview สลิป
      slip?.addEventListener('change', (e) => {
        const file = e.target.files?.[0];
        if (file) {
          const url = URL.createObjectURL(file);
          preview.src = url;
          preview.classList.remove('hidden');
        } else {
          preview.src = '';
          preview.classList.add('hidden');
        }
      });

      // กันกดซ้ำตอน submit
      form?.addEventListener('submit', () => {
        if (place) {
          place.disabled = true;
          place.textContent = 'กำลังส่งคำสั่งซื้อ...';
          place.classList.add('opacity-70','cursor-not-allowed');
        }
      });
    });
  </script>
</body>
</html>
