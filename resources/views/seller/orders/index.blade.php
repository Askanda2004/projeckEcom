<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Seller • Order Management</title>

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
  <header class="sticky top-0 z-30 bg-white/85 backdrop-blur shadow-soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-olive/10 flex items-center justify-center">
          <svg class="w-5 h-5 text-olive" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
          </svg>
        </div>
        <span class="font-semibold">Seller</span>
        <span class="hidden md:inline text-slate-400">/</span>
        <span class="hidden md:inline text-slate-500">Order Management</span>
      </div>
      <div class="flex items-center gap-2">
        <form method="POST" action="{{ route('logout') }}"> @csrf
          <button class="px-3 py-1.5 text-sm rounded-lg bg-ink text-white hover:bg-slate-900">Logout</button>
        </form>
      </div>
    </div>
  </header>

  @php
    $isProductSection = request()->routeIs('seller.products.*')
                      || request()->routeIs('seller.categories.*')
                      || request()->routeIs('seller.subcategories.*');
  @endphp

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
            <div class="w-10 h-10 rounded-full bg-slate-100 grid place-items-center text-slate-500 text-xs border">LOGO</div>
          @endif
          <div class="min-w-0">
            <div class="font-semibold truncate">{{ $shop }}</div>
          </div>
        </div>

        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Menu</h3>
        <nav class="space-y-1">
          <a href="{{ route('seller.reports.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
            การวิเคราะห์และรายงาน
          </a>

          <!-- Product Management (collapsible) -->
          <div class="rounded-2xl">
            <button type="button"
                    class="w-full flex items-center justify-between gap-2 px-3 py-2 rounded-xl {{ $isProductSection ? 'bg-slate-100 text-slate-900' : 'hover:bg-slate-50' }} transition pr-1"
                    data-toggle="submenu-products"
                    aria-expanded="{{ $isProductSection ? 'true' : 'false' }}"
                    aria-controls="submenu-products">
              <span class="flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z"/></svg>
                การจัดการสินค้า
              </span>
              <svg class="w-4 h-4 transition-transform shrink-0"
                   style="transform: rotate({{ $isProductSection ? '90' : '0' }}deg)"
                   data-caret viewBox="0 0 24 24" fill="currentColor">
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

          <a href="{{ route('seller.orders.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-900">
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
      <!-- Title + Filters -->
      <div class="bg-white rounded-2xl shadow-soft p-4 sm:p-5">
        <!-- แถวหัวข้อ + ค้นหา (กลาง) + ตัวกรอง (ขวา) -->
        <div class="grid gap-4 items-start grid-cols-1 lg:grid-cols-[auto_1fr_auto]">
          <div>
            <h1 class="text-2xl font-bold text-ink">การจัดการคำสั่งซื้อ</h1>
            <p class="text-sm text-slate-500">จัดการคำสั่งซื้อทั้งหมดของร้านคุณ</p>
          </div>

          <!-- กลาง: ค้นหา (ยืดเต็มคอลัมน์กลาง) -->
          <div class="order-2 lg:order-none">
            <form method="GET" action="{{ route('seller.orders.index') }}" class="relative w-full max-w-2xl mx-auto lg:mx-0">
              <input type="text" name="q" value="{{ $q ?? request('q','') }}"
                    placeholder="ค้นหา: ชื่อ / เบอร์ / ที่อยู่"
                    class="h-11 w-full rounded-full border border-slate-200 pl-4 pr-28 text-sm
                            focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
              <button
                class="absolute right-1 top-1/2 -translate-y-1/2 h-9 px-4 rounded-full
                      bg-primary text-white text-sm hover:bg-blue-700">
                Search
              </button>
            </form>
          </div>

          <!-- ขวา: ตัวกรอง (จัด 2 แถว: วันที่ | สถานะ+ปุ่ม) -->
          <div class="order-3 lg:order-none">
            <form method="GET" action="{{ route('seller.orders.index') }}"
                  class="grid gap-2 w-full max-w-md lg:justify-items-end">
              
              <!-- แถวบน: ช่วงวันที่ -->
              {{-- <div class="flex items-center gap-2">
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                      class="h-11 rounded-lg border border-slate-200 px-3 text-sm
                              focus:border-primary focus:ring-primary/20">
                <span class="text-slate-400 text-sm">→</span>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                      class="h-11 rounded-lg border border-slate-200 px-3 text-sm
                              focus:border-primary focus:ring-primary/20">
              </div> --}}

              <!-- แถวล่าง: สถานะ + Apply -->
              <div class="flex items-center gap-2">
                <select name="status"
                        class="h-11 rounded-lg border border-slate-200 px-3 text-sm
                              focus:border-primary focus:ring-primary/20">
                  <option value="">สถานะทั้งหมด</option>
                  @foreach (($statusMap ?? []) as $v => $lbl)
                    <option value="{{ $v }}" @selected(request('status')===$v)>{{ $lbl }}</option>
                  @endforeach
                </select>
                <button class="h-11 rounded-lg bg-slate-900 text-white px-4 text-sm hover:bg-slate-800">
                  Apply
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      @if (session('status'))
        <div class="rounded-2xl bg-emerald-50 text-emerald-800 px-4 py-3 shadow-soft">{{ session('status') }}</div>
      @endif

      <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
              <tr>
                <th class="px-4 py-3 text-left font-semibold border-b">วันที่สั่งซื้อ</th>
                <th class="px-4 py-3 text-left font-semibold border-b">ชื่อผู้รับ</th>
                <th class="px-4 py-3 text-left font-semibold border-b">เบอร์โทร</th>
                <th class="px-4 py-3 text-left font-semibold border-b">ที่อยู่จัดส่ง</th>
                <th class="px-4 py-3 text-right font-semibold border-b">ยอดรวม</th>
                <th class="px-4 py-3 text-center font-semibold border-b">สถานะคำสั่งซื้อ</th>
                <th class="px-4 py-3 text-center font-semibold border-b">รายละเอียด</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($orders as $o)
                <tr class="hover:bg-slate-50">
                  <td class="px-4 py-3 border-b">
                    {{ \Illuminate\Support\Carbon::parse($o->order_date)->timezone('Asia/Bangkok')->format('d/m/Y H:i') }}
                  </td>
                  <td class="px-4 py-3 border-b font-medium">{{ $o->shipping_name ?? '—' }}</td>
                  <td class="px-4 py-3 border-b">{{ $o->shipping_phone ?? '—' }}</td>
                  <td class="px-4 py-3 border-b">
                    <span title="{{ $o->shipping_address }}">{{ \Illuminate\Support\Str::limit($o->shipping_address, 80) ?: '—' }}</span>
                  </td>
                  <td class="px-4 py-3 border-b text-right font-semibold">฿{{ number_format((float) $o->total_amount, 2) }}</td>

                  <td class="px-4 py-3 border-b text-center">
                    <form method="POST" action="{{ route('seller.orders.status', $o) }}">
                      @csrf @method('PATCH')
                      <select name="status"
                              class="rounded-lg border px-2 py-1 text-sm focus:border-primary focus:ring-2 focus:ring-primary/10"
                              onchange="this.form.submit()">
                        @foreach ($statusMap as $value => $label)
                          <option value="{{ $value }}" @selected($o->status === $value)>{{ $label }}</option>
                        @endforeach
                      </select>
                    </form>
                  </td>

                  <td class="px-4 py-3 border-b text-center">
                    <button type="button"
                            class="rounded-lg border px-3 py-1.5 text-sm hover:bg-slate-50"
                            onclick="document.getElementById('dlg-{{ $o->order_id }}').showModal()">
                      รายละเอียด
                    </button>

                    <!-- Modal -->
                    <dialog id="dlg-{{ $o->order_id }}" class="rounded-2xl p-0 w-full max-w-2xl">
                      <form method="dialog">
                        <div class="p-4 sm:p-6 border-b">
                          <div class="flex items-center justify-between gap-3">
                            <h3 class="font-semibold">
                              รายละเอียดคำสั่งซื้อ 
                              <span class="ml-2 text-sm text-slate-500">
                                #{{ $o->order_id ?? $o->id }} - ({{ \Illuminate\Support\Carbon::parse($o->order_date)->timezone('Asia/Bangkok')->format('d/m/Y H:i') }})
                              </span>
                            </h3>
                            <button class="rounded-lg border px-3 py-1.5 text-sm hover:bg-slate-50">ปิด</button>
                          </div>
                          <div class="mt-1 text-sm text-slate-600">
                            ชื่อผู้รับ: {{ $o->shipping_name ?? '—' }} • {{ $o->shipping_phone ?? '—' }}<br>
                            ที่อยู่: {{ $o->shipping_address ?? '—' }}
                          </div>
                        </div>

                        <div class="p-4 sm:p-6 overflow-x-auto">
                          <table class="min-w-full text-sm">
                            <thead class="bg-slate-50">
                              <tr>
                                <th class="px-3 py-2 text-left">สินค้า</th>
                                <th class="px-3 py-2 text-left">ราคา</th>
                                <th class="px-3 py-2 text-left">จำนวน</th>
                                <th class="px-3 py-2 text-right">รวม</th>
                              </tr>
                            </thead>
                            <tbody class="divide-y">
                              @forelse ($o->items ?? [] as $it)
                                <tr>
                                  <td class="px-3 py-2">
                                    <div class="flex items-center gap-3">
                                      @php $thumb = $it->product?->image_url ? asset('storage/'.$it->product->image_url) : null; @endphp
                                      @if ($thumb)
                                        <img src="{{ $thumb }}" class="w-10 h-10 rounded object-cover border" alt="">
                                      @else
                                        <div class="w-10 h-10 rounded bg-slate-100 border"></div>
                                      @endif
                                      <div>
                                        <div class="font-medium">{{ $it->product->name ?? $it->name ?? '—' }}</div>
                                        <div class="text-xs text-slate-500">
                                          ขนาด: {{ $it->product->size ?? '—' }} • สี: {{ $it->product->color ?? '—' }}
                                        </div>
                                      </div>
                                    </div>
                                  </td>
                                  <td class="px-3 py-2">฿{{ number_format((float)$it->price, 2) }}</td>
                                  <td class="px-3 py-2">{{ $it->quantity }}</td>
                                  <td class="px-3 py-2 text-right">฿{{ number_format((float)$it->price * (int)$it->quantity, 2) }}</td>
                                </tr>
                              @empty
                                <tr>
                                  <td colspan="4" class="px-3 py-6 text-center text-slate-500">ไม่มีรายการสินค้า</td>
                                </tr>
                              @endforelse
                            </tbody>
                          </table>

                          <div class="mt-4 text-right">
                            <div class="text-slate-500 text-sm">ยอดรวม</div>
                            <div class="text-xl font-bold">฿{{ number_format((float)$o->total_amount, 2) }}</div>
                          </div>
                        </div>

                        <div class="p-4 sm:p-6 border-t flex justify-end">
                          <button class="rounded-lg border px-4 py-2 hover:bg-slate-50">ปิด</button>
                        </div>
                      </form>
                    </dialog>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="px-4 py-8 text-center text-slate-500">No orders found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 sm:p-6 border-t border-slate-100">
          <div class="flex items-center justify-between">
            <div class="text-xs text-slate-500">
              Showing <span class="font-medium">{{ $orders->firstItem() ?? 0 }}</span> to
              <span class="font-medium">{{ $orders->lastItem() ?? 0 }}</span> of
              <span class="font-medium">{{ $orders->total() }}</span> results
            </div>
            {{ $orders->links() }}
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Toggle submenu -->
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
    })();
  </script>
</body>
</html>
