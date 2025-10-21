<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>Seller • Analytics & Reports</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // โทนมินิมอลให้เข้าชุดทั้งระบบ
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            sand:'#FAFAF7',
            ink:'#111827',
            olive:'#7C8B6A',
            primary:{DEFAULT:'#2563eb'}
          },
          boxShadow: {
            soft:'0 6px 24px rgba(0,0,0,0.06)',
            card:'0 10px 35px rgba(0,0,0,0.08)'
          },
          borderRadius: { xl2:'1rem' }
        }
      }
    }
  </script>
</head>
<body class="bg-gradient-to-br from-sand to-white text-ink antialiased">

  {{-- HEADER --}}
  <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-olive/10 flex items-center justify-center">
          <svg class="w-5 h-5 text-olive" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
          </svg>
        </div>
        <span class="font-semibold tracking-wide">ผู้ขาย</span>
        <span class="hidden md:inline text-slate-400">/</span>
        <span class="hidden md:inline text-slate-600">การวิเคราะห์และรายงาน</span>
      </div>
      <form method="POST" action="{{ route('logout') }}" class="flex items-center gap-2"> @csrf
        <button class="px-3 h-9 rounded-xl bg-ink text-white hover:opacity-90">ออกจากระบบ</button>
      </form>
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
        @php
          $sp   = $sidebarProfile ?? (auth()->user()->sellerProfile ?? null);
          $logo = $sp && $sp->logo_path ? asset('storage/'.$sp->logo_path) : null;
          $shop = $sp->shop_name ?? 'ตั้งชื่อร้านของคุณ';
        @endphp

        <div class="flex items-center gap-3 mb-4 p-3 rounded-xl border border-slate-200 bg-white">
          @if($logo)
            <img src="{{ $logo }}" class="w-10 h-10 rounded-full object-cover border" alt="logo">
          @else
            <div class="w-10 h-10 rounded-full bg-sand grid place-items-center text-slate-500 text-xs border">
              LOGO
            </div>
          @endif
          <div class="min-w-0">
            <div class="font-semibold truncate">{{ $shop }}</div>
          </div>
        </div>

        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Menu</h3>
        <nav class="space-y-1">
          {{-- Analytics (active) --}}
          <a href="{{ route('seller.reports.index') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-xl bg-slate-100 text-ink">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            การวิเคราะห์และรายงาน
          </a>

          {{-- Product Management (collapsible + submenu) --}}
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

          {{-- Orders --}}
          <a href="{{ route('seller.orders.index') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50">
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

    {{-- MAIN --}}
    <main class="col-span-12 md:col-span-9 space-y-6">
      {{-- Title + Filters --}}
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <h1 class="text-2xl font-semibold">การวิเคราะห์และรายงาน</h1>
          <p class="text-slate-600 text-sm">ภาพรวมผลงานร้านค้าของคุณ</p>
        </div>

        <form method="GET" class="rounded-full bg-white border border-slate-200 px-2 py-1.5 flex items-center gap-2 shadow-soft">
          <div class="flex items-center gap-2 px-2">
            <span class="text-slate-400 text-xs">จาก</span>
            <input type="date" name="date_from" value="{{ $from }}"
                   class="rounded-lg border border-slate-200 text-sm px-2 py-1 focus:border-olive focus:ring-olive/20">
            <span class="text-slate-400 text-xs">ถึง</span>
            <input type="date" name="date_to" value="{{ $to }}"
                   class="rounded-lg border border-slate-200 text-sm px-2 py-1 focus:border-olive focus:ring-olive/20">
          </div>
          <button class="rounded-full bg-ink text-white px-4 py-1.5 text-sm hover:opacity-90">Apply</button>
        </form>
      </div>

      {{-- KPI Cards --}}
      <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-5 relative overflow-hidden">
          <div class="absolute right-3 -top-3 opacity-10 text-olive">
            <svg class="w-20 h-20" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
          </div>
          <div class="text-slate-500 text-sm">สินค้าทั้งหมด</div>
          <div class="mt-2 text-3xl font-semibold tracking-tight">{{ number_format($totalProducts) }}</div>
          <div class="mt-1 text-xs text-slate-400">รวมทุกหมวดของร้านคุณ</div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-5">
          <div class="text-slate-500 text-sm">สินค้าใกล้หมด (≤ {{ $lowStockThreshold }})</div>
          <div class="mt-2 text-3xl font-semibold tracking-tight">{{ number_format($lowStockCount) }}</div>
          <div class="mt-1 text-xs text-amber-600">ควรวางแผนเติมสต็อก</div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-5">
          <div class="text-slate-500 text-sm">ยอดขายรวม</div>
          <div class="mt-2 text-3xl font-semibold tracking-tight">฿{{ number_format($revenueSum,2) }}</div>
          <div class="mt-1 text-xs text-slate-400">{{ $from }} → {{ $to }}</div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-5">
          <div class="text-slate-500 text-sm">ออเดอร์ทั้งหมด</div>
          <div class="mt-2 text-3xl font-semibold tracking-tight">
            {{ number_format($ordersCount) }}
            <span class="text-base text-slate-600 font-medium"></span>
          </div>
          <div class="mt-1 text-xs text-slate-400">ออเดอร์</div>
        </div>
      </section>

      {{-- Daily Sales --}}
      <section class="bg-white rounded-2xl border border-slate-100 shadow-soft p-6">
        <div class="flex items-start sm:items-center justify-between gap-3 mb-4">
          <div class="space-y-0.5">
            <h2 class="font-semibold text-slate-900">ยอดขายรายวัน</h2>
            <p class="text-xs text-slate-500">
              ช่วงวันที่
              <span class="font-medium text-ink">{{ $from ?? '—' }}</span>
              <span class="px-1">→</span>
              <span class="font-medium text-ink">{{ $to ?? '—' }}</span>
            </p>
          </div>
          @php
            $totalAll = isset($dailySales) && $dailySales->isNotEmpty() ? $dailySales->sum('total') : 0;
          @endphp
          @if($totalAll > 0)
            <div class="shrink-0 text-right">
              <div class="text-[11px] uppercase tracking-wide text-slate-400">รวมช่วงนี้</div>
              <div class="text-sm font-semibold text-ink">฿{{ number_format($totalAll, 2) }}</div>
            </div>
          @endif
        </div>

        @php
          $max = isset($dailySales) && $dailySales->isNotEmpty() ? max(1, (int)$dailySales->max('total')) : 1;
        @endphp

        @if (!isset($dailySales) || $dailySales->isEmpty())
          <div class="rounded-xl border border-dashed border-slate-200 p-8 text-center">
            <div class="mx-auto mb-3 w-10 h-10 rounded-full bg-sand flex items-center justify-center">
              <svg class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-width="2" d="M3 3h18M3 7h18M3 21h18M7 7v14m10-14v14" />
              </svg>
            </div>
            <p class="text-slate-700 font-medium">ไม่มียอดขายในช่วงที่เลือก</p>
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
                     style="height: {{ $h }}%; background: linear-gradient(180deg, rgba(124,139,106,0.85) 0%, rgba(203,213,225,0.95) 100%); box-shadow: 0 4px 14px rgba(124,139,106,0.18);">
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
                    <td class="px-4 py-3 text-slate-800">{{ \Illuminate\Support\Carbon::parse($row->d)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-right font-medium text-ink">฿{{ number_format($row->total, 2) }}</td>
                  </tr>
                @endforeach
              </tbody>
              <tfoot class="bg-slate-50/60">
                <tr>
                  <td class="px-4 py-3 text-right font-semibold text-slate-700">รวม</td>
                  <td class="px-4 py-3 text-right font-semibold text-ink">฿{{ number_format($totalAll, 2) }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        @endif
      </section>

      {{-- Low stock + Top products --}}
      <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold">สินค้าใกล้หมด (Top 10)</h2>
            <span class="text-xs rounded-full bg-amber-50 text-amber-700 px-2 py-0.5">≤ {{ $lowStockThreshold }}</span>
          </div>
          @if ($lowStockList->isEmpty())
            <div class="text-slate-600 text-sm">ไม่มีสินค้าใกล้หมด</div>
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

        <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold">สินค้าขายดี (Top 5)</h2>
            <span class="text-xs rounded-full bg-emerald-50 text-emerald-700 px-2 py-0.5">ตามจำนวนชิ้นขาย</span>
          </div>
          @if ($topProducts->isEmpty())
            <div class="text-slate-600 text-sm">ยังไม่มีข้อมูล</div>
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
                    <tr class="hover:bg-sand/60">
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
