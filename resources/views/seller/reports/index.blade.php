<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>Seller • Analytics & Reports</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { primary:{DEFAULT:'#2563eb'}, ink:'#0f172a' },
          boxShadow: { soft:'0 8px 30px rgba(2,6,23,.06)', card:'0 10px 35px rgba(2,6,23,.08)' },
          borderRadius: { xl2:'1rem' }
        }
      }
    }
  </script>
</head>
<body class="bg-gradient-to-br from-slate-50 to-white text-slate-800">

  {{-- HEADER --}}
  <header class="sticky top-0 z-30 bg-white/80 backdrop-blur shadow-soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center">
          <svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
          </svg>
        </div>
        <span class="font-semibold tracking-wide">Seller</span>
        <span class="hidden md:inline text-slate-400">/</span>
        <span class="hidden md:inline text-slate-500">Analytics & Reports</span>
      </div>
      <div class="flex items-center gap-2">
        <form method="POST" action="{{ route('logout') }}"> @csrf
          <button class="px-3 py-1.5 text-sm rounded-lg bg-ink text-white hover:bg-slate-900">Logout</button>
        </form>
      </div>
    </div>
  </header>

  @php
    // ใช้ตรวจว่าหน้าอยู่ในกลุ่ม Product เพื่อตั้งค่าสถานะเปิดเมนูเริ่มต้น
    $isProductSection = request()->routeIs('seller.products.*')
                      || request()->routeIs('seller.categories.*')
                      || request()->routeIs('seller.subcategories.*');
  @endphp

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-12 gap-6">
    {{-- SIDEBAR --}}
    <aside class="col-span-12 md:col-span-3">
      <div class="bg-white rounded-2xl shadow-card p-4 md:p-5">
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Menu</h3>
        <nav class="space-y-1">
          {{-- Analytics (active) --}}
          <a href="{{ route('seller.reports.index') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-xl bg-slate-100 text-ink">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            Analytics & Reports
          </a>

          {{-- Product Management (collapsible + submenu) --}}
          <div class="rounded-2xl">
            <button type="button"
                    class="w-full flex items-center justify-between gap-2 px-3 py-2 rounded-xl
                          {{ $isProductSection ? 'bg-slate-100 text-slate-900' : 'hover:bg-slate-100' }} transition"
                    data-toggle="submenu-products"
                    aria-expanded="{{ $isProductSection ? 'true' : 'false' }}"
                    aria-controls="submenu-products">
              <span class="flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z"/>
                </svg>
                Product Management
              </span>
              <svg class="w-4 h-4 transition-transform"
                  style="transform: rotate({{ $isProductSection ? '90' : '0' }}deg)"
                  data-caret viewBox="0 0 24 24" fill="currentColor">
                <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/>
              </svg>
            </button>

            <div id="submenu-products" class="mt-1 {{ $isProductSection ? '' : 'hidden' }}">
              <ul class="pl-3 border-l border-slate-200 space-y-1">
                <li>
                  <a href="{{ route('seller.products.index') }}"
                     class="submenu-link flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50
                            {{ request()->routeIs('seller.products.index') ? 'bg-slate-100 text-slate-900' : '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                    สินค้าทั้งหมด
                  </a>
                </li>
                <li>
                  <a href="{{ route('seller.products.create') }}"
                     class="submenu-link flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50
                            {{ request()->routeIs('seller.products.create') ? 'bg-slate-100 text-slate-900' : '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                    เพิ่มสินค้า
                  </a>
                </li>
                {{-- <li>
                  <a href="{{ route('seller.categories.index') }}"
                     class="submenu-link flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50
                            {{ request()->routeIs('seller.categories.*') ? 'bg-slate-100 text-slate-900' : '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                    หมวดหมู่สินค้า
                  </a>
                </li> --}}
                <li>
                  <a href="{{ route('seller.subcategories.index') }}"
                     class="submenu-link flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50
                            {{ request()->routeIs('seller.subcategories.*') ? 'bg-slate-100 text-slate-900' : '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                    หมวดหมู่สินค้า
                  </a>
                </li>
              </ul>
            </div>
          </div>

          {{-- Orders --}}
          <a href="{{ route('seller.orders.index') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-100">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4m-9 4v6"/>
            </svg>
            Order Management
          </a>
        </nav>
      </div>
    </aside>

    {{-- MAIN --}}
    <main class="col-span-12 md:col-span-9 space-y-6">
      {{-- Title + Filters --}}
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <h1 class="text-2xl font-bold">Analytics & Reports</h1>
          <p class="text-slate-500 text-sm">ภาพรวมผลงานร้านค้าของคุณ</p>
        </div>

        <form method="GET" class="rounded-full bg-white shadow-soft px-2 py-1.5 flex items-center gap-2">
          <div class="flex items-center gap-2 px-2">
            <span class="text-slate-400 text-xs">จาก</span>
            <input type="date" name="date_from" value="{{ $from }}"
                   class="rounded-lg border border-slate-200 text-sm px-2 py-1 focus:border-primary focus:ring-primary/20">
            <span class="text-slate-400 text-xs">ถึง</span>
            <input type="date" name="date_to" value="{{ $to }}"
                   class="rounded-lg border border-slate-200 text-sm px-2 py-1 focus:border-primary focus:ring-primary/20">
          </div>
          <button class="rounded-full bg-primary text-white px-4 py-1.5 text-sm hover:bg-blue-700">Apply</button>
        </form>
      </div>

      {{-- KPI Cards --}}
      <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-card p-5 relative overflow-hidden">
          <div class="absolute right-3 -top-3 opacity-10 text-primary">
            <svg class="w-20 h-20" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
          </div>
          <div class="text-slate-500 text-sm">สินค้าทั้งหมด</div>
          <div class="mt-2 text-3xl font-bold tracking-tight">{{ number_format($totalProducts) }}</div>
          <div class="mt-1 text-xs text-slate-400">รวมทุกหมวดของร้านคุณ</div>
        </div>

        <div class="bg-white rounded-2xl shadow-card p-5">
          <div class="text-slate-500 text-sm">สินค้าใกล้หมด (≤ {{ $lowStockThreshold }})</div>
          <div class="mt-2 text-3xl font-bold tracking-tight">{{ number_format($lowStockCount) }}</div>
          <div class="mt-1 text-xs text-amber-600">ควรวางแผนเติมสต็อก</div>
        </div>

        <div class="bg-white rounded-2xl shadow-card p-5">
          <div class="text-slate-500 text-sm">ยอดขายรวม</div>
          <div class="mt-2 text-3xl font-bold tracking-tight">฿{{ number_format($revenueSum,2) }}</div>
          <div class="mt-1 text-xs text-slate-400">{{ $from }} → {{ $to }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow-card p-5">
          <div class="text-slate-500 text-sm">ออเดอร์ทั้งหมด / Avg</div>
          <div class="mt-2 text-3xl font-bold tracking-tight">
            {{ number_format($ordersCount) }}
            <span class="text-base text-slate-500 font-medium">• ฿{{ number_format($avgOrder,2) }}</span>
          </div>
          <div class="mt-1 text-xs text-slate-400">ค่าเฉลี่ยต่อออเดอร์</div>
        </div>
      </section>

      {{-- Daily Sales --}}
      <section class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-start sm:items-center justify-between gap-3 mb-4">
          <div class="space-y-0.5">
            <h2 class="font-semibold text-slate-900">ยอดขายรายวัน</h2>
            <p class="text-xs text-slate-500">
              ช่วงวันที่
              <span class="font-medium text-slate-700">{{ $from ?? '—' }}</span>
              <span class="px-1">→</span>
              <span class="font-medium text-slate-700">{{ $to ?? '—' }}</span>
            </p>
          </div>
          @php
            $totalAll = isset($dailySales) && $dailySales->isNotEmpty() ? $dailySales->sum('total') : 0;
          @endphp
          @if($totalAll > 0)
            <div class="shrink-0 text-right">
              <div class="text-[11px] uppercase tracking-wide text-slate-400">รวมช่วงนี้</div>
              <div class="text-sm font-semibold text-slate-800">฿{{ number_format($totalAll, 2) }}</div>
            </div>
          @endif
        </div>

        @php
          $max = isset($dailySales) && $dailySales->isNotEmpty() ? max(1, (int)$dailySales->max('total')) : 1;
        @endphp

        @if (!isset($dailySales) || $dailySales->isEmpty())
          <div class="rounded-xl border border-dashed border-slate-200 p-8 text-center">
            <div class="mx-auto mb-3 w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-width="2" d="M3 3h18M3 7h18M3 21h18M7 7v14m10-14v14" />
              </svg>
            </div>
            <p class="text-slate-600 font-medium">ไม่มียอดขายในช่วงที่เลือก</p>
            <p class="text-slate-500 text-sm mt-1">ลองปรับช่วงวันที่ใหม่ เพื่อดูข้อมูลที่ครอบคลุมมากขึ้น</p>
          </div>
        @else
          {{-- Mini bar chart --}}
          <div class="mb-6">
            <div class="flex items-end gap-1.5 h-28">
              @foreach($dailySales as $row)
                @php
                  $val = (float) $row->total;
                  $h   = max(4, round(($val / $max) * 100));
                  $labelDate = \Illuminate\Support\Carbon::parse($row->d)->format('d/m');
                @endphp
                <div role="img"
                     aria-label="วันที่ {{ $labelDate }} ยอดขาย ฿{{ number_format($val,2) }}"
                     title="{{ $labelDate }} • ฿{{ number_format($val,2) }}"
                     class="w-2.5 rounded-t transition-all duration-300"
                     style="height: {{ $h }}%; background: linear-gradient(180deg, rgba(59,130,246,0.85) 0%, rgba(147,197,253,0.95) 100%); box-shadow: 0 4px 14px rgba(59,130,246,0.18);">
                </div>
              @endforeach
            </div>
            <div class="mt-2 text-xs text-slate-400">* แถบสูง = ยอดขายสูงในวันนั้น</div>
          </div>

          {{-- Table --}}
          <div class="overflow-x-auto rounded-xl ring-1 ring-slate-200/60">
            <table class="min-w-full text-sm">
              <thead class="bg-slate-50/80 text-slate-600">
                <tr>
                  <th class="sticky left-0 bg-slate-50/80 backdrop-blur px-4 py-3 text-left font-semibold border-b border-slate-200">วันที่</th>
                  <th class="px-4 py-3 text-right font-semibold border-b border-slate-200">ยอดขาย (บาท)</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @foreach($dailySales as $row)
                  <tr class="hover:bg-slate-50/70">
                    <td class="px-4 py-3 text-slate-700">{{ \Illuminate\Support\Carbon::parse($row->d)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-right font-medium text-slate-800">฿{{ number_format($row->total, 2) }}</td>
                  </tr>
                @endforeach
              </tbody>
              <tfoot class="bg-slate-50/60">
                <tr>
                  <td class="px-4 py-3 text-right font-semibold text-slate-700">รวม</td>
                  <td class="px-4 py-3 text-right font-semibold text-slate-900">฿{{ number_format($totalAll, 2) }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        @endif
      </section>

      {{-- Low stock + Top products --}}
      <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-card p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold">สินค้าใกล้หมด (Top 10)</h2>
            <span class="text-xs rounded-full bg-amber-50 text-amber-700 px-2 py-0.5">เหลือน้อยกว่าหรือเท่ากับ {{ $lowStockThreshold }}</span>
          </div>
          @if ($lowStockList->isEmpty())
            <div class="text-slate-500 text-sm">ไม่มีสินค้าใกล้หมด</div>
          @else
            <ul class="divide-y">
              @foreach($lowStockList as $p)
                <li class="py-3 flex items-center justify-between hover:bg-slate-50/60 px-2 rounded-lg transition">
                  <div class="font-medium">{{ $p->name }}</div>
                  <span class="text-xs rounded-full px-2 py-1 {{ $p->stock_quantity <= 2 ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                    คงเหลือ: {{ $p->stock_quantity }}
                  </span>
                </li>
              @endforeach
            </ul>
          @endif
        </div>

        <div class="bg-white rounded-2xl shadow-card p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold">สินค้าขายดี (Top 5)</h2>
            <span class="text-xs rounded-full bg-emerald-50 text-emerald-700 px-2 py-0.5">ตามจำนวนชิ้นขาย</span>
          </div>
          @if ($topProducts->isEmpty())
            <div class="text-slate-500 text-sm">ยังไม่มีข้อมูล</div>
          @else
            <div class="overflow-x-auto">
              <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                  <tr>
                    <th class="px-4 py-3 text-left font-semibold border-b">สินค้า</th>
                    <th class="px-4 py-3 text-right font-semibold border-b">จำนวนขาย</th>
                    <th class="px-4 py-3 text-right font-semibold border-b">รายได้</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($topProducts as $t)
                    <tr class="hover:bg-slate-50">
                      <td class="px-4 py-3 border-b font-medium">{{ $t->name }}</td>
                      <td class="px-4 py-3 border-b text-right">{{ number_format($t->qty) }}</td>
                      <td class="px-4 py-3 border-b text-right">฿{{ number_format($t->revenue, 2) }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </section>

    </main>
  </div>

  {{-- Toggle submenu --}}
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
