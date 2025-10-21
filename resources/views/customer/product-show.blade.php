<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>{{ $product->name }} • {{ config('app.name','แพลตฟอร์มร้านผ้าคลุม') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Tailwind via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // โทนมินิมอลให้เข้าชุดทั้งเว็บ: sand/ink/olive + เงานุ่ม
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            sand:  '#FAFAF7',
            ink:   '#111827',
            olive: '#7C8B6A',
          },
          boxShadow: { soft: '0 6px 24px rgba(0,0,0,0.06)' },
          borderRadius: { xl2: '1rem' }
        }
      }
    }
  </script>
  <style>
    .thumb-active{ outline:2px solid #111827; outline-offset:2px; }
    .line-clamp-2{
      display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    }
  </style>
</head>
<body class="bg-sand text-ink antialiased">

  <!-- Header -->
  <header class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between gap-3">
      <a href="{{ route('customer.shop') }}" class="inline-flex items-center gap-2 hover:opacity-80">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M15 18l-6-6 6-6" stroke-width="2"/></svg>
        <span class="font-medium">กลับไปหน้าร้าน</span>
      </a>
      <a href="{{ route('customer.cart') }}" class="rounded-xl border border-slate-200 px-3 h-10 inline-flex items-center hover:border-ink/30">
        ตะกร้า
      </a>
    </div>
  </header>

  <!-- Main -->
  <main class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb เล็ก ๆ -->
    <nav class="text-xs text-slate-500 mb-4">
      <a href="{{ route('customer.shop') }}" class="hover:text-ink">หน้าร้าน</a>
      <span class="mx-1">/</span>
      <span class="text-slate-600">{{ $product->category->category_name ?? 'ไม่ระบุหมวด' }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
      {{-- ========== Gallery ========== --}}
      <section>
        @php
          $images  = $product->images;
          $primary = $images->firstWhere('is_primary', true) ?? $images->first();
          $cover   = $primary ? asset('storage/'.$primary->path)
                   : ($product->image_url ? asset('storage/'.$product->image_url) : null);
        @endphp

        <div class="bg-white rounded-2xl border border-slate-100 shadow-soft p-3">
          <div class="aspect-square bg-sand rounded-xl2 overflow-hidden">
            @if($cover)
              <img id="mainImage" src="{{ $cover }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
              <div class="w-full h-full grid place-items-center text-slate-400">ไม่มีรูปภาพ</div>
            @endif
          </div>

          @if(($images && $images->count()) || $product->image_url)
            <div id="thumbs" class="mt-3 grid grid-cols-5 sm:grid-cols-6 gap-2">
              @foreach($images as $img)
                @php $src = asset('storage/'.$img->path); @endphp
                <button type="button"
                        data-src="{{ $src }}"
                        class="thumb aspect-square rounded-xl overflow-hidden border border-slate-200 hover:ring-2 hover:ring-olive/40 {{ $cover===$src ? 'thumb-active' : '' }}">
                  <img src="{{ $src }}" class="w-full h-full object-cover" alt="">
                </button>
              @endforeach

              @if(!$images->count() && $product->image_url)
                @php $src = asset('storage/'.$product->image_url); @endphp
                <button type="button"
                        data-src="{{ $src }}"
                        class="thumb aspect-square rounded-xl overflow-hidden border border-slate-200 hover:ring-2 hover:ring-olive/40 {{ $cover===$src ? 'thumb-active' : '' }}">
                  <img src="{{ $src }}" class="w-full h-full object-cover" alt="">
                </button>
              @endif
            </div>
          @endif
        </div>
      </section>

      {{-- ========== Info / Buy ========== --}}
      <section>
        @php $stock = (int)($product->stock_quantity ?? 0); @endphp
        <div class="bg-white rounded-2xl border border-slate-100 shadow-soft p-6 space-y-5">
          <div>
            <h1 class="text-2xl font-semibold leading-tight">{{ $product->name }}</h1>
            <p class="mt-1 text-sm text-slate-600">
              หมวดหมู่: {{ $product->category->category_name ?? 'ไม่ระบุ' }}
              @if($product->size) • ขนาด: {{ $product->size }} @endif
              @if($product->color) • สี: {{ $product->color }} @endif
            </p>
          </div>

          <div class="text-3xl font-semibold">฿{{ number_format($product->price, 2) }}</div>

          @if($product->description)
            <div class="text-sm text-slate-700 leading-6 whitespace-pre-line">
              {{ $product->description }}
            </div>
          @endif

          <form method="POST" action="{{ route('customer.cart.add', $product) }}" class="flex items-center gap-3 pt-2">
            @csrf
            <div class="qty-wrap flex items-center border border-slate-200 rounded-xl overflow-hidden"
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

            <button class="flex-1 h-11 rounded-xl {{ $stock>0 ? 'bg-ink hover:opacity-90' : 'bg-slate-300 cursor-not-allowed' }} text-white transition"
                    {{ $stock<=0 ? 'disabled' : '' }}>
              หยิบใส่ตะกร้า
            </button>
          </form>

          <div class="text-xs text-slate-600">
            สถานะ:
            @if($stock > 0)
              <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                พร้อมส่ง • เหลือ {{ $stock }}
              </span>
            @else
              <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200">หมดชั่วคราว</span>
            @endif
          </div>

          <!-- จุดขาย/ความมั่นใจ -->
          <ul class="text-sm text-slate-600 space-y-1 pt-2">
            <li>• สินค้าดีมีคุณภาพ</li>
          </ul>
        </div>
      </section>
    </div>

    {{-- Related products --}}
    @if(!empty($related) && $related->count())
      <section class="mt-12">
        <div class="flex items-end justify-between mb-4">
          <h3 class="text-lg font-semibold">สินค้าใกล้เคียง</h3>
          <a href="{{ route('customer.shop', ['category'=>$product->category->category_id ?? null]) }}" class="text-sm hover:opacity-80">ดูทั้งหมด</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
          @foreach($related as $rp)
            @php
              $rpPrimary = $rp->relationLoaded('images')
                ? ($rp->images->firstWhere('is_primary', true) ?? $rp->images->first())
                : $rp->images()->orderByDesc('is_primary')->orderBy('ordering')->first();
              $rpImg = $rpPrimary ? asset('storage/'.$rpPrimary->path)
                      : ($rp->image_url ? asset('storage/'.$rp->image_url) : null);
            @endphp
            <a href="{{ route('customer.products.show', $rp) }}"
               class="group bg-white rounded-2xl border border-slate-100 p-2 shadow-soft hover:shadow transition block">
              <div class="aspect-square bg-sand rounded-xl overflow-hidden">
                @if($rpImg)
                  <img src="{{ $rpImg }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition" alt="">
                @endif
              </div>
              <div class="mt-2 text-sm line-clamp-2">{{ $rp->name }}</div>
              <div class="font-medium">฿{{ number_format($rp->price,2) }}</div>
            </a>
          @endforeach
        </div>
      </section>
    @endif
  </main>

  <footer class="border-t border-slate-100 bg-white mt-12">
    <div class="max-w-7xl mx-auto px-4 py-8 text-sm text-slate-600 flex items-center justify-between">
      <span>© {{ date('Y') }} {{ config('app.name', 'แพลตฟอร์มร้านผ้าคลุม') }}. All rights reserved.</span>
      <div class="flex items-center gap-4">
        <a href="#" class="hover:text-ink">Privacy</a>
        <a href="#" class="hover:text-ink">Terms</a>
      </div>
    </div>
  </footer>

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
