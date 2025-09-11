<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>ProjeckEcom • Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme:{ extend:{ boxShadow:{soft:'0 10px 30px rgba(2,6,23,.06)'} } } }
  </script>
</head>
<body class="bg-slate-50 text-slate-800">
  <!-- Header -->
  <header class="sticky top-0 z-30 bg-white/90 backdrop-blur border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="h-16 flex items-center gap-4">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="font-extrabold text-lg">ProjeckEcom</a>

        <!-- Search -->
        <form action="{{ route('shop.products.index') }}" method="get" class="flex-1 hidden md:block">
          <div class="relative">
            <input name="q" placeholder="ค้นหาสินค้า…" class="w-full rounded-xl border border-slate-200 pl-10 pr-3 py-2 focus:ring-2 focus:ring-blue-200">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="currentColor"><path d="M10 18a8 8 0 1 1 6.32-3.16l3.42 3.42-1.41 1.41-3.42-3.42A8 8 0 0 1 10 18z"/></svg>
          </div>
        </form>

        <!-- Menu (หมวดหมู่) -->
        <div class="hidden lg:flex items-center gap-2">
          <div class="relative group">
            <button class="px-3 py-1.5 rounded-lg hover:bg-slate-100">หมวดหมู่</button>
            <div class="absolute right-0 mt-2 hidden group-hover:block bg-white border rounded-xl shadow-soft p-2 w-56">
              @forelse(($categories ?? collect()) as $c)
                <a href="{{ route('shop.products.index', ['category_id'=>$c->category_id]) }}"
                   class="block px-3 py-2 rounded-lg hover:bg-slate-50">{{ $c->category_name }}</a>
              @empty
                <div class="px-3 py-2 text-sm text-slate-500">ยังไม่มีหมวดหมู่</div>
              @endforelse
            </div>
          </div>
        </div>

        <div class="ml-auto flex items-center gap-2">
          @guest
            <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-lg hover:bg-slate-100">Login</a>
            <a href="{{ route('register') }}" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50">Register</a>
          @endguest
          @auth
            <a href="{{ route('shop.cart.index') }}" class="px-3 py-1.5 rounded-lg hover:bg-slate-100">Cart</a>
            <div class="relative group">
              <button class="px-3 py-1.5 rounded-lg hover:bg-slate-100">{{ auth()->user()->name }}</button>
              <div class="absolute right-0 mt-2 hidden group-hover:block bg-white border rounded-xl shadow-soft p-2 w-48">
                @if (auth()->user()->role === 'admin')
                  <a class="block px-3 py-2 hover:bg-slate-50 rounded-lg" href="{{ route('admin.dashboard') }}">Admin</a>
                @elseif (auth()->user()->role === 'seller')
                  <a class="block px-3 py-2 hover:bg-slate-50 rounded-lg" href="{{ route('seller.dashboard') }}">Seller</a>
                @else
                  <a class="block px-3 py-2 hover:bg-slate-50 rounded-lg" href="{{ route('customer.dashboard') }}">บัญชีของฉัน</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="w-full text-left px-3 py-2 hover:bg-slate-50 rounded-lg">Logout</button>
                </form>
              </div>
            </div>
          @endauth
        </div>
      </div>
    </div>
  </header>

  <!-- Hero Banner -->
  <section class="bg-gradient-to-r from-indigo-50 to-blue-50 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
      <div class="rounded-2xl overflow-hidden shadow-soft">
        <img src="https://images.unsplash.com/photo-1542831371-29b0f74f9713?q=80&w=1600&auto=format&fit=crop"
             alt="Promotion" class="w-full h-64 md:h-96 object-cover">
      </div>
    </div>
  </section>

  <!-- Category Section -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-xl font-bold">หมวดหมู่สินค้า</h2>
      <a class="text-sm text-slate-500 hover:underline" href="{{ route('shop.products.index') }}">ดูทั้งหมด</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
      @forelse(($categories ?? collect()) as $c)
        <a href="{{ route('shop.products.index', ['category_id'=>$c->category_id]) }}"
           class="bg-white border rounded-xl p-4 flex flex-col items-center gap-3 hover:shadow-soft">
          <div class="w-12 h-12 rounded-full bg-slate-100"></div>
          <span class="text-sm text-center">{{ $c->category_name }}</span>
        </a>
      @empty
        @for ($i=0;$i<6;$i++)
          <div class="bg-white border rounded-xl p-4 flex flex-col items-center gap-3 opacity-60">
            <div class="w-12 h-12 rounded-full bg-slate-100"></div>
            <span class="text-sm text-center">Category</span>
          </div>
        @endfor
      @endforelse
    </div>
  </section>

  <!-- Featured Products -->
  <section class="bg-white/60 border-y">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">สินค้าแนะนำ</h2>
        <a class="text-sm text-slate-500 hover:underline" href="{{ route('shop.products.index') }}">เลือกซื้อสินค้า</a>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
        @forelse(($featured ?? collect()) as $p)
          <div class="bg-white border rounded-2xl overflow-hidden hover:shadow-soft">
            <a href="{{ route('shop.products.show', $p) }}">
              <img class="w-full h-40 object-cover"
                   src="{{ $p->image_url ? asset('storage/'.$p->image_url) : 'https://picsum.photos/600/400?random='.$p->product_id }}"
                   alt="{{ $p->name }}">
            </a>
            <div class="p-3">
              <a href="{{ route('shop.products.show', $p) }}" class="block font-medium line-clamp-1">{{ $p->name }}</a>
              <div class="mt-1 font-semibold">฿{{ number_format($p->price,2) }}</div>
              <form method="POST" action="{{ route('shop.cart.add',$p) }}" class="mt-2">
                @csrf
                <input type="hidden" name="qty" value="1">
                <button class="w-full rounded-lg border px-3 py-1.5 hover:bg-slate-50">Add to Cart</button>
              </form>
            </div>
          </div>
        @empty
          @for ($i=0;$i<5;$i++)
            <div class="bg-white border rounded-2xl h-56"></div>
          @endfor
        @endforelse
      </div>
    </div>
  </section>

  <!-- Promotion / Campaign -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="rounded-2xl overflow-hidden shadow-soft">
        <img class="w-full h-40 object-cover" src="https://images.unsplash.com/photo-1512446816042-444d641267d4?q=80&w=1200&auto=format&fit=crop" alt="">
      </div>
      <div class="rounded-2xl overflow-hidden shadow-soft">
        <img class="w-full h-40 object-cover" src="https://images.unsplash.com/photo-1515165562835-c3b8c8d62d01?q=80&w=1200&auto=format&fit=crop" alt="">
      </div>
      <div class="rounded-2xl overflow-hidden shadow-soft">
        <img class="w-full h-40 object-cover" src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=1200&auto=format&fit=crop" alt="">
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-white border-t">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid grid-cols-1 md:grid-cols-4 gap-6">
      <div>
        <div class="font-bold text-lg">ProjeckEcom</div>
        <p class="text-sm text-slate-500 mt-2">ร้านค้าออนไลน์ของคุณ</p>
      </div>
      <div>
        <div class="font-semibold mb-2">เมนู</div>
        <ul class="space-y-1 text-sm text-slate-600">
          <li><a href="#" class="hover:underline">เกี่ยวกับเรา</a></li>
          <li><a href="#" class="hover:underline">ติดต่อ</a></li>
          <li><a href="#" class="hover:underline">นโยบายความเป็นส่วนตัว</a></li>
        </ul>
      </div>
      <div>
        <div class="font-semibold mb-2">ติดตามเรา</div>
        <div class="flex gap-3 text-slate-500">
          <a href="#" class="hover:text-slate-900">Facebook</a>
          <a href="#" class="hover:text-slate-900">Instagram</a>
          <a href="#" class="hover:text-slate-900">X</a>
        </div>
      </div>
      <div>
        <div class="font-semibold mb-2">สมัครรับข่าวสาร</div>
        <form class="flex gap-2">
          <input type="email" placeholder="อีเมลของคุณ" class="flex-1 rounded-lg border px-3 py-2">
          <button class="rounded-lg bg-slate-900 text-white px-4 py-2">สมัคร</button>
        </form>
      </div>
    </div>
    <div class="text-center text-xs text-slate-500 pb-6">© {{ date('Y') }} ProjeckEcom</div>
  </footer>
</body>
</html>
