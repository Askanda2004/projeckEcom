<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Seller • Product Management</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { primary: { DEFAULT: '#2563eb' } },
          boxShadow: { soft: '0 8px 30px rgba(0,0,0,0.08)' }
        }
      }
    }
  </script>
</head>
<body class="bg-slate-50 text-slate-800">

  <!-- HEADER (เหมือน dashboard) -->
  <header class="sticky top-0 z-30 bg-white/90 backdrop-blur shadow-soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
          <svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/></svg>
        </div>
        <span class="font-bold">Seller</span>
        <span class="hidden md:inline text-slate-400">/</span>
        <span class="hidden md:inline text-slate-500">Product Management</span>
      </div>
      <div class="flex items-center gap-2">
        {{-- <a href="{{ route('seller.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Dashboard</a> --}}
        <form method="POST" action="{{ route('logout') }}"> @csrf
          <button class="px-3 py-1.5 text-sm rounded-lg bg-slate-900 text-white hover:bg-slate-800">Logout</button>
        </form>
      </div>
    </div>
  </header>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-12 gap-6">
    <!-- SIDEBAR -->
    <aside class="col-span-12 md:col-span-3">
      <div class="bg-white rounded-2xl shadow-soft p-4 md:p-5">
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Menu</h3>
        <nav class="space-y-1">
          <a href="{{ route('seller.reports.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
            Analytics & Reports
          </a>
          <a href="{{ route('seller.products.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-900">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20 6H4v12h16V6zM8 8h8v2H8V8zm0 4h8v2H8v-2z"/></svg>
            Product Management
          </a>
          <a href="{{ route('seller.orders.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4m-9 4v6"/></svg>
            Order Management
          </a>
        </nav>
      </div>
    </aside>

    <!-- MAIN -->
    <main class="col-span-12 md:col-span-9 space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Product Management</h1>

        <div class="flex items-center gap-3">
          {{-- ค้นหา --}}
          <form method="GET" action="{{ route('seller.products.index') }}" class="relative hidden md:block">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search..."
                   class="w-64 rounded-full border border-slate-200 py-2.5 pl-4 pr-24 focus:border-primary focus:ring-4 focus:ring-blue-100 transition">
            <button class="absolute right-1 top-1/2 -translate-y-1/2 rounded-full bg-primary text-white px-4 py-1.5 text-sm">
              Search
            </button>
          </form>

          <a href="{{ route('seller.products.create') }}"
             class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-white hover:bg-blue-700 shadow">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"/></svg>
            Add Product
          </a>
        </div>
      </div>

      @if (session('status'))
        <div class="rounded-xl bg-green-50 text-green-800 px-4 py-3 shadow-soft">{{ session('status') }}</div>
      @endif

      <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-50 text-slate-600">
        <tr>
          <th class="px-4 py-3 text-left font-semibold border-b">รูปภาพสินค้า</th>
          {{-- <th class="px-4 py-3 text-left font-semibold border-b">รหัสสินค้า</th> --}}
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
                <div class="w-14 h-14 rounded-lg bg-slate-100 border"></div>
              @endif
            </td>

            {{-- <td class="px-4 py-3 border-b">{{ $p->product_id }}</td>  ← ลบออก --}}

            <td class="px-4 py-3 border-b font-medium">{{ $p->name }}</td>

            <td class="px-4 py-3 border-b text-slate-600">
              <span title="{{ $p->description }}">{{ \Illuminate\Support\Str::limit($p->description, 60) }}</span>
            </td>

            <td class="px-4 py-3 border-b">{{ $p->size ?? '—' }}</td>
            <td class="px-4 py-3 border-b">{{ $p->color ?? '—' }}</td>   {{-- ✅ เพิ่ม --}}

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
                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50">Edit</a>
            </td>

            <td class="px-4 py-3 border-b">
              <form method="POST" action="{{ route('seller.products.destroy', $p) }}"
                    onsubmit="return confirm('Delete this product?')">
                @csrf
                @method('DELETE')
                <button class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-rose-200 text-rose-700 hover:bg-rose-50">Delete</button>
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
      <div class="text-xs text-slate-500">
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
</body>
</html>
