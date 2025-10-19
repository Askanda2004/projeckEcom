<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>หมวดหมู่สินค้า • Seller</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme:{ extend:{ colors:{ primary:{DEFAULT:'#2563eb'} }, boxShadow:{soft:'0 8px 30px rgba(0,0,0,0.08)'} } } }
  </script>
</head>
<body class="bg-slate-50 text-slate-800">
  <header class="sticky top-0 z-30 bg-white/90 backdrop-blur shadow-soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="font-bold">Seller • หมวดหมู่สินค้า</div>
      {{-- <a href="{{ route('seller.products.index') }}" class="text-sm text-slate-600 hover:text-slate-900">กลับไปจัดการสินค้า</a> --}}
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-12 gap-6">
      <!-- Sidebar -->
      <aside class="col-span-12 md:col-span-3">
        <div class="bg-white rounded-2xl shadow-soft p-4 md:p-5">
          @php
            $sp = $sidebarProfile ?? (auth()->user()->sellerProfile ?? null);
            $logo = $sp && $sp->logo_path ? asset('storage/'.$sp->logo_path) : null;
            $shop = $sp->shop_name ?? 'ตั้งชื่อร้านของคุณ';
          @endphp

          <div class="flex items-center gap-3 mb-4 p-3 rounded-xl border border-slate-200 bg-slate-50">
            @if($logo)
              <img src="{{ $logo }}" class="w-10 h-10 rounded-full object-cover border" alt="logo">
            @else
              <div class="w-10 h-10 rounded-full bg-slate-200 grid place-items-center text-slate-500 text-xs border">
                LOGO
              </div>
            @endif
            <div class="min-w-0">
              <div class="font-semibold truncate">{{ $shop }}</div>
              {{-- <div class="text-xs text-slate-500 truncate">{{ auth()->user()->name }}</div> --}}
            </div>
          </div>
          
          <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Menu</h3>

          <nav class="space-y-1">
            <a href="{{ route('seller.reports.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
              </svg>
              Analytics & Reports
            </a>

            <!-- Product Management (collapsible) -->
            <div class="rounded-2xl">
              <button type="button"
                      class="w-full flex items-center justify-between gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-900 hover:bg-slate-200 transition"
                      data-toggle="submenu-products"
                      aria-expanded="false" aria-controls="submenu-products">
                <span class="flex items-center gap-2">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z"/>
                  </svg>
                  Product Management
                </span>
                <svg class="w-4 h-4 transition-transform" data-caret viewBox="0 0 24 24" fill="currentColor">
                  <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/>
                </svg>
              </button>

              <div id="submenu-products" class="mt-1 hidden">
                <ul class="pl-3 border-l border-slate-200 space-y-1">
                  <li>
                    <a href="{{ route('seller.products.index') }}"
                       class="submenu-link flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50">
                      <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                      สินค้าทั้งหมด
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('seller.products.create') }}"
                       class="submenu-link flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50">
                      <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                      เพิ่มสินค้า
                    </a>
                  </li>
                  {{-- <li>
                    <a href="{{ route('seller.categories.index') }}"
                       class="submenu-link flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50">
                      <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                      หมวดหมู่สินค้า
                    </a>
                  </li> --}}
                  <li>
                    <a href="{{ route('seller.subcategories.index') }}"
                       class="submenu-link flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50">
                      <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                      หมวดหมู่สินค้า
                    </a>
                  </li>
                </ul>
              </div>
            </div>

            <a href="{{ route('seller.orders.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4m-9 4v6"/>
              </svg>
              Order Management
            </a>

            <a href="{{ route('seller.profile.edit') }}"
              class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12zm0 2.4c-3.3 0-9.6 1.6-9.6 4.9V22h19.2v-2.7c0-3.3-6.3-4.9-9.6-4.9z"/></svg>
              Store Profeil
            </a>
          </nav>
        </div>
      </aside>
      <!-- /Sidebar -->

      <!-- Content -->
      <section class="col-span-12 md:col-span-9">
        <h1 class="text-xl font-bold mb-4">หมวดหมู่สินค้า (สินค้าแยกตามหมวด)</h1>

        @forelse($categories as $cat)
          <section class="mb-6 bg-white rounded-2xl shadow-soft p-4">
            <div class="flex items-center justify-between mb-2">
              <h2 class="font-semibold">{{ $cat->category_name }}</h2>
              <a class="text-sm text-primary hover:underline"
                 href="{{ route('seller.products.index') }}?category={{ urlencode($cat->category_name) }}">
                ดูเฉพาะหมวดนี้
              </a>
            </div>
            
            @if($cat->products->count())
              <div class="overflow-x-auto">
                <table class="w-full text-sm">
                  <thead>
                    <tr class="text-left text-slate-500 border-b">
                      <th class="py-2">รูป</th>
                      <th class="py-2">สินค้า</th>
                      <th class="py-2">แบรนด์</th>
                      <th class="py-2">ขนาด</th>
                      <th class="py-2">สี</th>
                      <th class="py-2">สต็อก</th>
                      <th class="py-2">ราคา</th>
                      <th class="py-2 text-center">จัดการ</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($cat->products as $p)
                      @php
                        // ใช้ image_url เป็นหลัก; ถ้าไม่มี ลองหยิบจากความสัมพันธ์ images (ถ้าโหลดไว้)
                        $thumb = $p->image_url
                          ? asset('storage/'.$p->image_url)
                          : (method_exists($p, 'images')
                                ? optional($p->images()->orderByDesc('is_primary')->orderBy('ordering')->first())->path
                                : null);
                        if ($thumb && !str_starts_with($thumb, 'http')) {
                          $thumb = asset('storage/'.(is_string($thumb)? $thumb : ''));
                        }
                      @endphp
                      <tr class="border-b last:border-0 align-top">
                        <td class="py-2">
                          @if($thumb)
                            <img src="{{ $thumb }}" alt="{{ $p->name }}"
                                class="h-12 w-12 rounded object-cover border">
                          @else
                            <div class="h-12 w-12 rounded bg-slate-100 border"></div>
                          @endif
                        </td>

                        <td class="py-2 font-medium">
                          {{ $p->name }}
                        </td>

                        <td class="py-2 text-slate-600">
                          {{ \Illuminate\Support\Str::limit($p->description ?: '—', 120) }}
                        </td>

                        <td class="py-2">{{ $p->size ?: '—' }}</td>
                        <td class="py-2">{{ $p->color ?: '—' }}</td>
                        <td class="py-2">{{ (int) $p->stock_quantity }}</td>
                        <td class="py-2">฿{{ number_format((float) $p->price, 2) }}</td>

                        <td class="py-2">
                          <div class="flex items-center gap-2 justify-center">
                            {{-- แก้ไข --}}
                            <a href="{{ route('seller.products.edit', $p) }}"
                              class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50">
                              แก้ไข
                            </a>

                            {{-- ลบ --}}
                            <form method="POST"
                                  action="{{ route('seller.products.destroy', $p) }}"
                                  onsubmit="return confirm('ลบสินค้า “{{ $p->name }}” ถาวร?')">
                              @csrf
                              @method('DELETE')
                              <button class="px-3 py-1.5 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100">
                                ลบ
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <p class="text-sm text-slate-500">— ไม่มีสินค้าในหมวดนี้ —</p>
            @endif


            {{-- @if($cat->products->count())
              <div class="overflow-x-auto">
                <table class="w-full text-sm">
                  <thead>
                    <tr class="text-left text-slate-500 border-b">
                      <th class="py-2">รูป</th>
                      <th class="py-2">สินค้า</th>
                      <th class="py-2">รายละเอียด</th>
                      <th class="py-2">ขนาด</th>
                      <th class="py-2">สี</th>
                      <th class="py-2">สต็อก</th>
                      <th class="py-2">ราคา</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($cat->products as $p)
                      <tr class="border-b last:border-0">
                        <td class="py-2">
                          @if($p->image_url)
                            <img src="{{ asset('storage/'.$p->image_url) }}"
                                class="h-12 w-12 rounded object-cover border"
                                alt="{{ $p->name }}">
                          @else
                            <div class="h-12 w-12 rounded bg-slate-100 border"></div>
                          @endif
                        </td>
                        <td class="py-2">{{ $p->name }}</td>
                        <td class="py-2">{{ $p->description ?: '—' }}</td>
                        <td class="py-2">{{ $p->size ?: '—' }}</td>
                        <td class="py-2">{{ $p->color ?: '—' }}</td>
                        <td class="py-2">{{ $p->stock_quantity }}</td>
                        <td class="py-2">฿{{ number_format($p->price,2) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <p class="text-sm text-slate-500">— ไม่มีสินค้าในหมวดนี้ —</p>
            @endif --}}

          </section>
        @empty
          <p class="text-center text-slate-500">ยังไม่มีหมวดหมู่</p>
        @endforelse

        {{-- <div>
          <a href="{{ route('seller.products.index') }}" class="text-sm text-slate-600 hover:underline">&larr; กลับไปจัดการสินค้า</a>
        </div> --}}
      </section>
      <!-- /Content -->
    </div>
  </main>

  <!-- Sidebar toggle & auto-active -->
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

      // Active ตาม URL และเปิดเมนูอัตโนมัติเมื่ออยู่ในกลุ่ม Product
      const links = menu?.querySelectorAll('.submenu-link') || [];
      const currentPath = window.location.pathname;
      let hasActiveChild = false;
      links.forEach(a => {
        try{
          const to = new URL(a.href, window.location.origin).pathname;
          if (currentPath === to || currentPath.startsWith(to)) {
            a.classList.add('bg-slate-100','text-slate-900');
            hasActiveChild = true;
          }
        }catch(e){}
      });
      if (hasActiveChild) setOpen(true);
    })();
  </script>
</body>
</html>
