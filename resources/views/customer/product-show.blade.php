<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>{{ $product->name }} • My Shop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { colors:{ primary:{DEFAULT:'#2563eb'} }, boxShadow:{soft:'0 10px 30px rgba(2,6,23,.06)'} } } }
  </script>
  <style>
    .thumb-active{ outline:2px solid rgb(37,99,235); outline-offset:2px; }
  </style>
</head>
<body class="bg-slate-50 text-slate-800">
  <header class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between gap-3">
      <a href="{{ route('customer.shop') }}" class="font-semibold">&larr; กลับไปหน้าร้าน</a>
      <a href="{{ route('customer.cart') }}" class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-100">ตะกร้า</a>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 py-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      {{-- Gallery --}}
      <section>
        @php
          $images  = $product->images;
          $primary = $images->firstWhere('is_primary', true) ?? $images->first();
          $cover   = $primary ? asset('storage/'.$primary->path)
                    : ($product->image_url ? asset('storage/'.$product->image_url) : null);
        @endphp

        <div class="bg-white rounded-2xl border border-slate-200 shadow-soft p-3">
          <div class="aspect-square bg-slate-100 rounded-xl overflow-hidden">
            @if($cover)
              <img id="mainImage" src="{{ $cover }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
              <div class="w-full h-full grid place-items-center text-slate-400">ไม่มีรูปภาพ</div>
            @endif
          </div>

          @if($images->count() || $product->image_url)
            <div id="thumbs" class="mt-3 grid grid-cols-5 sm:grid-cols-6 gap-2">
              @foreach($images as $img)
                @php $src = asset('storage/'.$img->path); @endphp
                <button type="button"
                        data-src="{{ $src }}"
                        class="thumb aspect-square rounded-lg overflow-hidden border hover:ring-2 hover:ring-primary/50 {{ $cover===$src ? 'thumb-active' : '' }}">
                  <img src="{{ $src }}" class="w-full h-full object-cover" alt="">
                </button>
              @endforeach

              @if(!$images->count() && $product->image_url)
                @php $src = asset('storage/'.$product->image_url); @endphp
                <button type="button"
                        data-src="{{ $src }}"
                        class="thumb aspect-square rounded-lg overflow-hidden border hover:ring-2 hover:ring-primary/50 {{ $cover===$src ? 'thumb-active' : '' }}">
                  <img src="{{ $src }}" class="w-full h-full object-cover" alt="">
                </button>
              @endif
            </div>
          @endif
        </div>
      </section>

      {{-- Info / Buy --}}
      <section>
        @php $stock = (int)($product->stock_quantity ?? 0); @endphp
        <div class="bg-white rounded-2xl border border-slate-200 shadow-soft p-6 space-y-4">
          <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
          <p class="text-sm text-slate-500">
            หมวดหมู่: {{ $product->category->category_name ?? 'ไม่ระบุ' }}
            @if($product->size) · ขนาด: {{ $product->size }} @endif
            @if($product->color) · สี: {{ $product->color }} @endif
          </p>

          <div class="text-3xl font-bold text-primary">฿{{ number_format($product->price, 2) }}</div>

          @if($product->description)
            <div class="prose prose-slate max-w-none">
              <h2 class="text-base font-semibold">รายละเอียด</h2>
              <p class="whitespace-pre-line">{{ $product->description }}</p>
            </div>
          @endif

          <form method="POST" action="{{ route('customer.cart.add', $product) }}" class="flex items-center gap-3 pt-2">
            @csrf
            <div class="qty-wrap flex items-center border rounded-lg overflow-hidden"
                 data-min="{{ $stock>0 ? 1 : 0 }}" data-max="{{ $stock }}">
              <button type="button" class="btn-dec px-3 py-2 text-slate-600 hover:bg-slate-100">−</button>
              <input type="number" name="qty"
                     class="qty-input w-16 text-center border-x border-slate-200 focus:outline-none"
                     value="{{ $stock>0 ? 1 : 0 }}"
                     min="{{ $stock>0 ? 1 : 0 }}"
                     max="{{ $stock }}"
                     {{ $stock<=0 ? 'readonly' : '' }}>
              <button type="button" class="btn-inc px-3 py-2 text-slate-600 hover:bg-slate-100">+</button>
            </div>

            <button class="flex-1 rounded-lg {{ $stock>0 ? 'bg-slate-900 hover:bg-slate-800' : 'bg-slate-300 cursor-not-allowed' }} text-white py-2.5"
                    {{ $stock<=0 ? 'disabled' : '' }}>
              หยิบใส่ตะกร้า
            </button>
          </form>

          <div class="text-xs text-slate-500">
            สถานะ:
            @if($stock > 0)
              <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                พร้อมส่ง · เหลือ {{ $stock }}
              </span>
            @else
              <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200">หมดชั่วคราว</span>
            @endif
          </div>
        </div>
      </section>
    </div>

    {{-- Related products --}}
    @if($related->count())
      <section class="mt-10">
        <h3 class="font-semibold mb-3">สินค้าใกล้เคียง</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
          @foreach($related as $rp)
            @php
              $rpPrimary = $rp->relationLoaded('images')
                ? ($rp->images->firstWhere('is_primary', true) ?? $rp->images->first())
                : $rp->images()->orderByDesc('is_primary')->orderBy('ordering')->first();
              $rpImg = $rpPrimary ? asset('storage/'.$rpPrimary->path)
                      : ($rp->image_url ? asset('storage/'.$rp->image_url) : null);
            @endphp
            <a href="{{ route('customer.products.show', $rp) }}" class="block bg-white rounded-xl border p-2 hover:shadow-soft">
              <div class="aspect-square bg-slate-100 rounded-lg overflow-hidden">
                @if($rpImg)
                  <img src="{{ $rpImg }}" class="w-full h-full object-cover" alt="">
                @endif
              </div>
              <div class="mt-2 text-sm line-clamp-2">{{ $rp->name }}</div>
              <div class="text-primary font-semibold">฿{{ number_format($rp->price,2) }}</div>
            </a>
          @endforeach
        </div>
      </section>
    @endif
  </main>

  <script>
    // สลับรูปหลักด้วย thumbnails + ควบคุมจำนวนตามสต็อก
    document.addEventListener('DOMContentLoaded', () => {
      // thumbs
      const main = document.getElementById('mainImage');
      document.querySelectorAll('.thumb').forEach(btn => {
        btn.addEventListener('click', () => {
          if (main && btn.dataset.src) {
            main.src = btn.dataset.src;
            document.querySelectorAll('.thumb').forEach(b => b.classList.remove('thumb-active'));
            btn.classList.add('thumb-active');
          }
        });
      });

      // qty clamp
      document.querySelectorAll('.qty-wrap').forEach(wrap => {
        const input = wrap.querySelector('.qty-input');
        const dec   = wrap.querySelector('.btn-dec');
        const inc   = wrap.querySelector('.btn-inc');
        const min   = Number(wrap.dataset.min || 1);
        const max   = Number(wrap.dataset.max || 1);

        function clamp(){
          let v = Number(input.value || 0);
          if (isNaN(v)) v = min;
          if (v < min) v = min;
          if (v > max) v = max;
          input.value = v;

          dec.disabled = v <= min;
          inc.disabled = v >= max;
          dec.classList.toggle('opacity-40', dec.disabled);
          inc.classList.toggle('opacity-40', inc.disabled);
        }

        dec.addEventListener('click', () => { input.stepDown(); clamp(); });
        inc.addEventListener('click', () => { input.stepUp();   clamp(); });
        input.addEventListener('input', clamp);

        clamp();
      });
    });
  </script>
</body>
</html>
