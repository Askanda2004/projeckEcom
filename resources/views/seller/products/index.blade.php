<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Seller • Product Management</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // โทนมินิมอลเข้าชุดทั้งระบบ
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            sand:'#FAFAF7',
            ink:'#111827',
            olive:'#7C8B6A',
            primary:{ DEFAULT:'#2563eb' }
          },
          boxShadow: { soft:'0 6px 24px rgba(0,0,0,0.06)', card:'0 10px 35px rgba(0,0,0,0.08)' },
          borderRadius: { xl2:'1rem' }
        }
      }
    }
  </script>
</head>
<body class="bg-gradient-to-br from-sand to-white text-ink antialiased">

  <!-- HEADER -->
  <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-olive/10 flex items-center justify-center">
          <svg class="w-5 h-5 text-olive" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
          </svg>
        </div>
        <span class="font-semibold">ร้านค้า</span>
        <span class="hidden md:inline text-slate-400">/</span>
        <span class="hidden md:inline text-slate-600">การจัดการสินค้า</span>
      </div>
      <div class="flex items-center gap-2">
        <form method="POST" action="{{ route('logout') }}"> @csrf
          <button class="px-3 py-1.5 text-sm rounded-lg border border-neutral-300 text-ink hover:bg-neutral-100 transition-colors">
            ออกจากระบบ
          </button>
        </form>
      </div>
    </div>
  </header>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-12 gap-6">
    <!-- SIDEBAR -->
    <aside class="col-span-12 md:col-span-3">
      <div class="bg-white rounded-2xl shadow-card p-4 md:p-5">
        @php
          $sp = $sidebarProfile ?? (auth()->user()->sellerProfile ?? null);
          $logo = $sp && $sp->logo_path ? asset('storage/'.$sp->logo_path) : null;
          $shop = $sp->shop_name ?? 'ตั้งชื่อร้านของคุณ';
        @endphp

        <div class="flex items-center gap-3 mb-4 p-3 rounded-xl border border-slate-200 bg-white">
          @if($logo)
            <img src="{{ $logo }}" class="w-10 h-10 rounded-full object-cover border" alt="logo">
          @else
            <div class="w-10 h-10 rounded-full bg-sand grid place-items-center text-slate-500 text-xs border">LOGO</div>
          @endif
          <div class="min-w-0">
            <div class="font-semibold truncate">{{ $shop }}</div>
          </div>
        </div>
        
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Menu</h3>

        <nav class="space-y-1">
          <!-- Analytics -->
          <a href="{{ route('seller.reports.index') }}"
             class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
            การวิเคราะห์และรายงาน
          </a>

          <!-- Product Management (collapsible with submenu) -->
          @php
            $isProductSection = request()->routeIs('seller.products.*')
                              || request()->routeIs('seller.categories.*')
                              || request()->routeIs('seller.subcategories.*');
          @endphp
          <div class="rounded-2xl">
            <button type="button"
                    class="w-full flex items-center justify-between gap-2 px-3 py-2 rounded-xl {{ $isProductSection ? 'bg-slate-100 text-slate-900' : 'hover:bg-slate-50' }} transition"
                    data-toggle="submenu-products"
                    aria-expanded="{{ $isProductSection ? 'true' : 'false' }}"
                    aria-controls="submenu-products">
              <span class="flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z"/></svg>
                การจัดการสินค้า
              </span>
              <svg class="w-4 h-4 transition-transform" style="transform: rotate({{ $isProductSection ? '90' : '0' }}deg)" data-caret viewBox="0 0 24 24" fill="currentColor">
                <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/>
              </svg>
            </button>

            <div id="submenu-products" class="mt-1 {{ $isProductSection ? '' : 'hidden' }}">
              <ul class="pl-3 border-l border-slate-200 space-y-1">
                <li>
                  <a href="{{ route('seller.products.index') }}"
                     class="submenu-link flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50 {{ request()->routeIs('seller.products.index') ? 'bg-slate-100 text-slate-900' : '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                    สินค้าทั้งหมด
                  </a>
                </li>
                <li>
                  <a href="{{ route('seller.products.create') }}"
                     class="submenu-link flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50 {{ request()->routeIs('seller.products.create') ? 'bg-slate-100 text-slate-900' : '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                    เพิ่มสินค้า
                  </a>
                </li>
                <li>
                  <a href="{{ route('seller.subcategories.index') }}"
                     class="submenu-link flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50 {{ request()->routeIs('seller.subcategories.*') ? 'bg-slate-100 text-slate-900' : '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                    หมวดหมู่สินค้า
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <!-- Orders -->
          <a href="{{ route('seller.orders.index') }}"
             class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4m-9 4v6"/></svg>
            การจัดการคำสั่งซื้อ
          </a>

          <a href="{{ route('seller.profile.edit') }}"
             class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12zm0 2.4c-3.3 0-9.6 1.6-9.6 4.9V22h19.2v-2.7c0-3.3-6.3-4.9-9.6-4.9z"/></svg>
            โปรไฟล์ร้านค้า
          </a>
        </nav>
      </div>
    </aside>

    <!-- MAIN -->
    <main class="col-span-12 md:col-span-9 space-y-6">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <h1 class="text-2xl font-semibold">การจัดการสินค้า</h1>

        <div class="flex items-center gap-3">
          {{-- Search --}}
          <form method="GET" action="{{ route('seller.products.index') }}" class="relative hidden md:block">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search..."
                   class="w-64 h-10 rounded-full border border-slate-200 bg-white/70 focus:bg-white py-2 pl-4 pr-24 outline-none focus:ring-2 focus:ring-olive/30">
            <button class="absolute right-1 top-1/2 -translate-y-1/2 rounded-full bg-ink text-white px-4 py-1.5 text-sm hover:opacity-90">
              ค้นหา
            </button>
          </form>

          <a href="{{ route('seller.products.create') }}"
             class="inline-flex items-center gap-2 h-10 rounded-xl bg-ink px-4 text-white hover:opacity-90 shadow-soft">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"/></svg>
            เพิ่มสินค้า
          </a>
        </div>
      </div>

      @if (session('status'))
        <div class="rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3 border border-emerald-200 shadow-soft">{{ session('status') }}</div>
      @endif

      <div class="bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
              <tr>
                <th class="px-4 py-3 text-left font-semibold border-b">รูปภาพสินค้า</th>
                <th class="px-4 py-3 text-left font-semibold border-b">ชื่อสินค้า</th>
                <th class="px-4 py-3 text-left font-semibold border-b">แบรนด์</th>
                <th class="px-4 py-3 text-left font-semibold border-b">ขนาด</th>
                <th class="px-4 py-3 text-left font-semibold border-b">สี</th>
                <th class="px-4 py-3 text-left font-semibold border-b">ราคา</th>
                <th class="px-4 py-3 text-left font-semibold border-b">จำนวนคงเหลือ</th>
                <th class="px-4 py-3 text-left font-semibold border-b">หมวดหมู่</th>
                <th class="px-4 py-3 text-left font-semibold border-b">แก้ไข</th>
                <th class="px-4 py-3 text-left font-semibold border-b">ลบ</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($products as $p)
                <tr class="hover:bg-slate-50">
                  <td class="px-4 py-3 border-b">
                    @if($p->image_url)
                      <img src="{{ asset('storage/'.$p->image_url) }}" alt="{{ $p->name }}" class="w-14 h-14 object-cover rounded-lg border">
                    @else
                      <div class="w-14 h-14 rounded-lg bg-sand border"></div>
                    @endif
                  </td>

                  <td class="px-4 py-3 border-b font-medium text-ink">{{ $p->name }}</td>

                  <td class="px-4 py-3 border-b text-slate-600">
                    <span title="{{ $p->description }}">{{ \Illuminate\Support\Str::limit($p->description, 60) }}</span>
                  </td>

                  <td class="px-4 py-3 border-b">{{ $p->size ?? '—' }}</td>
                  <td class="px-4 py-3 border-b">{{ $p->color ?? '—' }}</td>

                  <td class="px-4 py-3 border-b">฿{{ number_format((float) $p->price, 2) }}</td>

                  <td class="px-4 py-3 border-b">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs
                        {{ ($p->stock_quantity ?? 0) > 5 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                      {{ $p->stock_quantity ?? 0 }}
                    </span>
                  </td>

                  <td class="px-4 py-3 border-b">{{ $p->category->category_name ?? '—' }}</td>

                  <td class="px-4 py-3 border-b">
                    <a href="{{ route('seller.products.edit', $p) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50">แก้ไข</a>
                  </td>

                  <td class="px-4 py-3 border-b">
                    <form method="POST" action="{{ route('seller.products.destroy', $p) }}"
                          onsubmit="return confirm('Delete this product?')">
                      @csrf
                      @method('DELETE')
                      <button class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-rose-200 text-rose-700 hover:bg-rose-50">ลบ</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="10" class="px-4 py-8 text-center text-slate-500">No products found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 sm:p-6 border-t border-slate-100">
          <div class="flex items-center justify-between">
            <div class="text-xs text-slate-600">
              Showing <span class="font-medium">{{ $products->firstItem() ?? 0 }}</span> to
              <span class="font-medium">{{ $products->lastItem() ?? 0 }}</span> of
              <span class="font-medium">{{ $products->total() }}</span> results
            </div>
            {{ $products->links() }}
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Toggle & active states -->
  <script>
    (function(){
      const btn = document.querySelector('[data-toggle="submenu-products"]');
      const menu = document.getElementById('submenu-products');
      const caret = btn?.querySelector('[data-caret]');

      function setOpen(open){
        menu.classList.toggle('hidden', !open);
        btn?.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (caret) caret.style.transform = open ? 'rotate(90deg)' : 'rotate(0deg)';
      }

      btn?.addEventListener('click', () => setOpen(menu.classList.contains('hidden')));

      // mark active by URL & auto-open if any child matches
      const links = menu?.querySelectorAll('.submenu-link') || [];
      const currentPath = window.location.pathname;
      let hasActiveChild = false;

      links.forEach(a => {
        try {
          const to = new URL(a.href, window.location.origin).pathname;
          if (currentPath === to || currentPath.startsWith(to)) {
            a.classList.add('bg-slate-100','text-slate-900');
            hasActiveChild = true;
          }
        } catch (e) {}
      });

      if (hasActiveChild) setOpen(true);
    })();
  </script>
</body>
</html>
