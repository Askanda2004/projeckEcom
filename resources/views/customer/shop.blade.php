<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Shop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: { DEFAULT: '#2563eb' },
            ink: '#0f172a'
          },
          boxShadow: { soft: '0 10px 30px rgba(2,6,23,.06)' },
          backgroundImage: {
            'glass': 'radial-gradient(1000px 200px at 50% -50%, rgba(37,99,235,.08), transparent)'
          }
        }
      }
    }
  </script>
  <style>
    .line-clamp-2 {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-800">

  <!-- Header -->
  <header class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between gap-3">
      <!-- Brand -->
      <a href="{{ route('customer.shop') }}" class="flex items-center gap-2">
        <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary grid place-items-center">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M7 4h10l3 5v9a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9l3-5zm10 7H7v7h10v-7zM8 6l-1.2 2h10.4L16 6H8z"/></svg>
        </div>
        <span class="font-semibold">My Shop</span>
      </a>

      <!-- Search -->
      <form class="w-full max-w-2xl" method="get" action="{{ route('customer.shop') }}">
        <div class="relative">
          <input
            name="q"
            value="{{ $q ?? request('q') ?? '' }}"
            placeholder="ค้นหาสินค้า / หมวดหมู่…"
            class="w-full rounded-xl border-slate-200 bg-slate-50/60 hover:bg-white focus:bg-white border px-4 py-2.5 pl-10 shadow-inner focus:border-primary focus:ring-4 focus:ring-primary/10 transition"
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
        <a href="{{ route('customer.cart') }}"
           class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-100 transition">
          ตะกร้า
        </a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="rounded-lg bg-slate-900 text-white px-3 py-1.5 hover:bg-slate-800 transition">
            Logout
          </button>
        </form>
      </div>
    </div>
  </header>

  <!-- Main -->
  <main class="bg-glass">
    <div class="max-w-7xl mx-auto px-4 py-6">
      @if (session('status'))
        <div class="mb-5 rounded-xl bg-green-50 text-green-700 px-4 py-2 border border-green-200">
          {{ session('status') }}
        </div>
      @endif

      <!-- Section header -->
      <div class="flex items-end justify-between mb-4">
        <div>
          <h1 class="text-xl font-bold text-ink">สินค้าทั้งหมดของแต่ละร้านค้า</h1>
          @if(request('q'))
            <p class="text-sm text-slate-500">คำค้นหา: <span class="font-medium text-slate-700">“{{ request('q') }}”</span></p>
          @else
            <p class="text-sm text-slate-500">เลือกชมสินค้าของทุกร้านค้าได้เลย</p>
          @endif
        </div>
      </div>

      <!-- Product Grid -->
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

          <article class="group bg-white rounded-2xl border border-slate-200 shadow-soft overflow-hidden flex flex-col transition hover:-translate-y-0.5 hover:shadow-lg">
            <!-- Image -->
            <a href="{{ route('customer.products.show', $product) }}" class="relative aspect-square bg-slate-100 overflow-hidden block">
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
                <div class="absolute right-3 top-3 text-[11px] px-2 py-1 rounded-full bg-primary text-white shadow">
                  ใหม่
                </div>
              @endif
            </a>

            <!-- Info -->
            <div class="p-4 flex flex-col flex-1 justify-between">
              <div>
                <a href="{{ route('customer.products.show', $product) }}" class="block hover:underline">
                  <h2 class="font-semibold leading-snug line-clamp-2">{{ $product->name }}</h2>
                </a>

                <!-- Description -->
                @if(!empty($product->description))
                  <p class="mt-1 text-xs text-slate-600 line-clamp-2">
                    แบรนด์:
                    {{ \Illuminate\Support\Str::limit(strip_tags($product->description), 120) }}
                  </p>
                @endif

                <p class="text-xs text-slate-500 mt-1">
                  ขนาด: {{ $product->size ?? '—' }} · สี: {{ $product->color ?? '—' }}
                </p>

                <p class="text-xs text-slate-500 mt-1">
                  สี: {{ $product->color ?? '—' }}
                </p>

                <div class="mt-2 flex items-center justify-between">
                  <div class="font-bold text-lg text-primary">฿{{ number_format($product->price,2) }}</div>
                  <span class="text-[11px] px-2 py-0.5 rounded-full
                    {{ $stock > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                    {{ $stock > 0 ? 'พร้อมส่ง · เหลือ ' . $stock : 'หมดชั่วคราว' }}
                  </span>
                </div>
              </div>

              <!-- Add to cart -->
              <form method="POST" action="{{ route('customer.cart.add', $product) }}"
                    class="mt-4 flex items-center gap-2">
                @csrf
                <div class="qty-wrap flex items-center border rounded-lg overflow-hidden"
                     data-min="1" data-max="{{ $stock }}">
                  <button type="button"
                          class="btn-dec px-2 py-1 text-slate-600 hover:bg-slate-100">−</button>
                  <input type="number"
                         name="qty"
                         class="qty-input w-12 text-center border-x border-slate-200 focus:outline-none"
                         value="{{ $stock > 0 ? 1 : 0 }}"
                         min="{{ $stock > 0 ? 1 : 0 }}"
                         max="{{ $stock }}"
                         {{ $stock <= 0 ? 'readonly' : '' }} />
                  <button type="button"
                          class="btn-inc px-2 py-1 text-slate-600 hover:bg-slate-100">+</button>
                </div>

                <button type="submit"
                        class="flex-1 rounded-lg {{ $stock>0 ? 'bg-slate-900 hover:bg-slate-800' : 'bg-slate-300 cursor-not-allowed' }} text-white py-2.5 transition"
                        {{ $stock<=0 ? 'disabled' : '' }}>
                  หยิบใส่ตะกร้า
                </button>
              </form>
            </div>
          </article>
        @empty
          <p class="col-span-full text-center text-slate-500 py-10">ไม่พบสินค้า</p>
        @endforelse
      </div>

      <!-- Pagination -->
      <div class="mt-8">
        {{ $products->links() }}
      </div>
    </div>
  </main>

  <script>
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
