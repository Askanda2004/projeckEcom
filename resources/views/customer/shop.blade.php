<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Shop – {{ config('app.name', 'แพลตฟอร์มร้านผ้าคลุม') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Tailwind via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // โทนมินิมอลให้เข้าชุด: sand/ink/olive + เงานุ่ม
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            sand:  '#FAFAF7',
            ink:   '#111827',
            olive: '#7C8B6A',
            primary: { DEFAULT: '#2563eb' } // ถ้ายังใช้สีเดิมบางจุด
          },
          boxShadow: { soft: '0 6px 24px rgba(0,0,0,0.06)' },
          borderRadius: { xl2: '1rem' },
          backgroundImage: {
            glass: 'radial-gradient(1000px 200px at 50% -50%, rgba(124,139,106,.10), transparent)'
          }
        }
      }
    }
  </script>

  <style>
    /* ถ้ายังไม่ได้เปิด plugin line-clamp */
    .line-clamp-2 {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
  </style>
</head>
<body class="bg-sand text-ink antialiased">

  <!-- Header -->
  <header class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between gap-3">
      <!-- Brand -->
      <a href="{{ route('customer.shop') }}" class="flex items-center gap-2 group">
        <div class="w-9 h-9 rounded-xl bg-olive/10 text-olive grid place-items-center shadow">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M4 12h16M4 7h16M4 17h16" stroke-width="2"/>
          </svg>
        </div>
        <span class="font-semibold tracking-wide group-hover:opacity-80 transition">
          {{ config('app.name', 'แพลตฟอร์มร้านผ้าคลุม') }}
        </span>
      </a>

      <!-- Search -->
      <form class="w-full max-w-2xl" method="get" action="{{ route('customer.shop') }}">
        <div class="relative">
          <input
            name="q"
            value="{{ $q ?? request('q') ?? '' }}"
            placeholder="ค้นหาสินค้า…"
            class="w-full h-10 rounded-xl border border-slate-200 bg-white/70 focus:bg-white px-4 pl-10 outline-none focus:ring-2 focus:ring-olive/30 transition"
            autocomplete="off"
          >
          <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"
               viewBox="0 0 24 24" fill="currentColor">
            <path d="M10 18a8 8 0 1 1 6.32-3.16l3.42 3.42-1.41 1.41-3.42-3.42A8 8 0 0 1 10 18z"/>
          </svg>
        </div>
      </form>

      <!-- Actions -->
      <div class="flex items-center gap-2">
        @php
          // แนะนำ: ย้าย logic นี้ไป Controller แล้วส่ง $cartQty เข้ามา (แต่คงไว้ให้ใช้งานได้ทันที)
          $cartQty = \Illuminate\Support\Facades\DB::table('carts')
              ->join('cart_items','cart_items.cart_id','=','carts.cart_id')
              ->where('carts.user_id', auth()->id())
              ->sum('cart_items.quantity');
        @endphp

        <a href="{{ route('customer.cart') }}" class="relative rounded-xl border border-slate-200 px-3 h-10 inline-flex items-center hover:border-ink/30">
          ตะกร้า
          @if($cartQty>0)
            <span class="absolute -top-2 -right-2 text-[10px] bg-ink text-white rounded-full px-1.5 py-0.5">
              {{ $cartQty }}
            </span>
          @endif
        </a>

        <a href="{{ route('customer.orders.index') }}"
           class="rounded-xl border border-slate-200 px-3 h-10 inline-flex items-center hover:border-ink/30">
          ประวัติการสั่งซื้อ
        </a>

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="rounded-xl bg-ink text-white px-3 h-10 inline-flex items-center hover:opacity-90 transition">
            ออกจากระบบ
          </button>
        </form>
      </div>
    </div>
  </header>

  <!-- Main -->
  <main class="bg-glass">
    <div class="max-w-7xl mx-auto px-4 py-6">

      @if (session('status'))
        <div class="mb-5 rounded-xl bg-emerald-50 text-emerald-700 px-4 py-2 border border-emerald-200">
          {{ session('status') }}
        </div>
      @endif

      <div class="grid grid-cols-12 gap-6">
        {{-- ============ Sidebar: หมวดหมู่ ============ --}}
        <aside class="col-span-12 md:col-span-3 md:sticky md:top-24 self-start">
          @php
            $u = auth()->user();
            $initial = $u ? mb_substr($u->name ?? '', 0, 1) : '';
          @endphp

          <!-- โปรไฟล์ผู้ใช้ -->
          <div class="mb-4 bg-white rounded-2xl shadow-soft p-4 md:p-5">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-olive/10 text-olive grid place-items-center font-semibold">
                {{ $initial }}
              </div>
              <div class="min-w-0">
                <div class="text-xs text-slate-500">สวัสดี,</div>
                <div class="font-semibold truncate">{{ $u->name ?? 'Guest' }}</div>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-2xl shadow-soft p-4 md:p-5">
            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">หมวดหมู่</h3>
            <nav class="space-y-1">
              @php
                $isAll = is_null($currentCategory);
                $baseAll = ['q' => request('q')];
              @endphp
              <a href="{{ route('customer.shop', array_filter($baseAll)) }}"
                 class="flex items-center justify-between px-3 py-2 rounded-xl {{ $isAll ? 'bg-slate-100 text-slate-900' : 'hover:bg-slate-50' }}">
                ทั้งหมด
                <span class="text-xs text-slate-500">{{ $categories->sum('products_count') }}</span>
              </a>

              @foreach($categories as $cat)
                @php
                  $active = (int)$currentCategory === (int)$cat->category_id;
                  $params = array_filter([
                    'q' => request('q'),
                    'category' => $cat->category_id,
                  ]);
                @endphp
                <a href="{{ route('customer.shop', $params) }}"
                   class="flex items-center justify-between px-3 py-2 rounded-xl {{ $active ? 'bg-ink text-white' : 'hover:bg-slate-50' }}">
                  <span class="truncate">{{ $cat->category_name }}</span>
                  <span class="text-xs {{ $active ? 'text-white/80' : 'text-slate-500' }}">{{ $cat->products_count }}</span>
                </a>
              @endforeach
            </nav>
          </div>
        </aside>

        {{-- ============ Content: header + product grid ============ --}}
        <section class="col-span-12 md:col-span-9">
          <div class="flex items-end justify-between mb-4">
            <div>
              <h1 class="text-xl font-semibold">
                สินค้าทั้งหมดของแต่ละร้านค้า
                @if($currentCategory && isset($categories))
                  @php 
                    $catName = optional($categories->firstWhere('category_id', (int)$currentCategory))->category_name; 
                  @endphp
                  @if($catName) 
                    <span class="text-sm text-slate-500 font-normal">• หมวด: {{ $catName }}</span> 
                  @endif
                @endif
              </h1>
              @if(request('q'))
                <p class="text-sm text-slate-600">คำค้นหา: <span class="font-medium text-ink">“{{ request('q') }}”</span></p>
              @else
                <p class="text-sm text-slate-600">เลือกชมสินค้าของทุกร้านค้าได้เลย</p>
              @endif
            </div>
          </div>

          {{-- Product Grid --}}
          <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6">
            @forelse ($products as $product)
              @php
                $primary = $product->relationLoaded('images')
                  ? ($product->images->firstWhere('is_primary', true) ?? $product->images->first())
                  : $product->images()->orderByDesc('is_primary')->orderBy('ordering')->first();
                $imgSrc = $primary ? asset('storage/'.$primary->path)
                        : ($product->image_url ? asset('storage/'.$product->image_url) : null);
                $stock = (int)($product->stock_quantity ?? 0);
              @endphp

              <article class="group bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden flex flex-col transition hover:shadow-lg hover:-translate-y-0.5">
                <!-- Image -->
                <a href="{{ route('customer.products.show', $product) }}" class="relative aspect-square bg-sand overflow-hidden block">
                  @if($imgSrc)
                    <img src="{{ $imgSrc }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover object-center transition duration-300 group-hover:scale-[1.03]" />
                  @else
                    <div class="absolute inset-0 grid place-items-center bg-gradient-to-br from-slate-100 to-slate-200">
                      <svg class="w-10 h-10 text-slate-400" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-4-4-3 3-4-4-5 5V7z"/>
                      </svg>
                    </div>
                  @endif

                  <div class="absolute left-3 top-3 text-[11px] px-2 py-1 rounded-full bg-white/90 text-slate-700 shadow">
                    {{ $product->category->category_name ?? 'ไม่ระบุหมวด' }}
                  </div>

                  @if(isset($product->created_at) && \Illuminate\Support\Carbon::parse($product->created_at)->gte(now()->subDays(14)))
                    <div class="absolute right-3 top-3 text-[11px] px-2 py-1 rounded-full bg-ink text-white shadow">
                      ใหม่
                    </div>
                  @endif
                </a>

                <!-- Info -->
                <div class="p-4 flex flex-col flex-1 justify-between">
                  <div>
                    <a href="{{ route('customer.products.show', $product) }}" class="block hover:underline">
                      <h2 class="font-medium leading-snug line-clamp-2">{{ $product->name }}</h2>
                    </a>

                    @if(!empty($product->description))
                      <p class="mt-1 text-xs text-slate-600 line-clamp-2">
                        {{ \Illuminate\Support\Str::limit(strip_tags($product->description), 120) }}
                      </p>
                    @endif

                    <p class="text-xs text-slate-500 mt-1">
                      ขนาด: {{ $product->size ?? '—' }} · สี: {{ $product->color ?? '—' }}
                    </p>

                    <div class="mt-2 flex items-center justify-between">
                      <div class="font-semibold text-lg text-ink">฿{{ number_format($product->price,2) }}</div>
                      <span class="text-[11px] px-2 py-0.5 rounded-full border
                        {{ $stock > 0 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                        {{ $stock > 0 ? 'พร้อมส่ง · เหลือ ' . $stock : 'หมดชั่วคราว' }}
                      </span>
                    </div>
                  </div>

                  <!-- Add to cart -->
                  <form method="POST" action="{{ route('customer.cart.add', $product) }}"
                        class="mt-4 flex items-center gap-2">
                    @csrf
                    <div class="qty-wrap flex items-center border border-slate-200 rounded-xl overflow-hidden"
                         data-min="1" data-max="{{ $stock }}">
                      <button type="button" class="btn-dec px-2 py-1 text-slate-600 hover:bg-slate-100">−</button>
                      <input type="number" name="qty"
                             class="qty-input w-12 text-center border-x border-slate-200 focus:outline-none"
                             value="{{ $stock > 0 ? 1 : 0 }}"
                             min="{{ $stock > 0 ? 1 : 0 }}" max="{{ $stock }}"
                             {{ $stock <= 0 ? 'readonly' : '' }} />
                      <button type="button" class="btn-inc px-2 py-1 text-slate-600 hover:bg-slate-100">+</button>
                    </div>

                    <button type="submit"
                            class="flex-1 h-10 rounded-xl {{ $stock>0 ? 'bg-ink hover:opacity-90' : 'bg-slate-300 cursor-not-allowed' }} text-white transition"
                            {{ $stock<=0 ? 'disabled' : '' }}>
                      เพิ่มตะกร้า
                    </button>
                  </form>
                </div>
              </article>
            @empty
              <p class="col-span-full text-center text-slate-500 py-10">ไม่พบสินค้า</p>
            @endforelse
          </div>

          {{-- Pagination --}}
          <div class="mt-8">{{ $products->links() }}</div>
        </section>
      </div>
    </div>
  </main>

  <footer class="border-t border-slate-100 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-sm text-slate-600 flex items-center justify-between">
      <span>© {{ date('Y') }} {{ config('app.name', 'แพลตฟอร์มร้านผ้าคลุม') }}. All rights reserved.</span>
      <div class="flex items-center gap-4">
        <a href="#" class="hover:text-ink">Privacy</a>
        <a href="#" class="hover:text-ink">Terms</a>
      </div>
    </div>
  </footer>

  <script>
    // Qty stepper (คง logic เดิม + ปรับ visual)
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.qty-wrap').forEach(wrap => {
        const input = wrap.querySelector('.qty-input');
        const dec   = wrap.querySelector('.btn-dec');
        const inc   = wrap.querySelector('.btn-inc');
        const min   = Number(wrap.dataset.min || 1);
        const max   = Number(wrap.dataset.max || 1);

        function clamp() {
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
