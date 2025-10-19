<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>Seller • โปรไฟล์ร้าน</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: {
      colors:{ primary:{DEFAULT:'#2563eb'} },
      boxShadow:{ soft:'0 8px 30px rgba(0,0,0,.08)' }
    } } }
  </script>
</head>
<body class="bg-slate-50 text-slate-800">

{{-- HEADER --}}
<header class="sticky top-0 z-30 bg-white/90 backdrop-blur shadow-soft">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
        <svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/></svg>
      </div>
      <span class="font-bold">Seller</span>
      <span class="hidden md:inline text-slate-400">/</span>
      <span class="hidden md:inline text-slate-500">Store Profile</span>
    </div>
    <div class="flex items-center gap-2">
      <form method="POST" action="{{ route('logout') }}"> @csrf
        <button class="px-3 py-1.5 text-sm rounded-lg bg-slate-900 text-white hover:bg-slate-800">Logout</button>
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
  {{-- SIDEBAR (เหมือนหน้าอื่น + เพิ่ม Store Profile) --}}
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
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
          Analytics & Reports
        </a>

        {{-- Product Management (collapsible) --}}
        <div class="rounded-2xl">
          <button type="button"
                  class="w-full flex items-center justify-between gap-2 px-3 py-2 rounded-xl {{ $isProductSection ? 'bg-slate-100 text-slate-900' : 'hover:bg-slate-100' }} transition pr-1"
                  data-toggle="submenu-products"
                  aria-expanded="{{ $isProductSection ? 'true' : 'false' }}"
                  aria-controls="submenu-products">
            <span class="flex items-center gap-2">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z"/></svg>
              Product Management
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

        <a href="{{ route('seller.orders.index') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4m-9 4v6"/></svg>
          Order Management
        </a>

        {{-- ✅ เมนูใหม่: Store Profile (active) --}}
        <a href="{{ route('seller.profile.edit') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-900">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-4 0-8 2-8 5v1h16v-1c0-3-4-5-8-5z"/></svg>
          Store Profeil
        </a>
      </nav>
    </div>
  </aside>

  {{-- MAIN --}}
  <main class="col-span-12 md:col-span-9 space-y-6">

    {{-- Header image + logo --}}
    <section class="bg-white rounded-2xl shadow-soft overflow-hidden">
      <div class="relative h-48 bg-slate-100">
        @if($profile->photo_path)
          <img src="{{ asset('storage/'.$profile->photo_path) }}" class="w-full h-48 object-cover" alt="">
        @else
          <div class="h-full grid place-items-center text-slate-400 text-sm">ยังไม่มีภาพร้าน</div>
        @endif

        <div class="absolute -bottom-8 left-6">
          @if($profile->logo_path)
            <img src="{{ asset('storage/'.$profile->logo_path) }}"
                 class="w-20 h-20 rounded-full border-4 border-white shadow object-cover" alt="">
          @else
            <div class="w-20 h-20 rounded-full bg-slate-200 border-4 border-white grid place-items-center text-slate-400 text-xs">
              ไม่มีโลโก้
            </div>
          @endif
        </div>
      </div>
      <div class="pt-10 pb-6 px-6">
        <h2 class="text-xl font-semibold">{{ $profile->shop_name ?? 'ยังไม่ได้ตั้งชื่อร้าน' }}</h2>
        <p class="text-sm text-slate-600 mt-1">{{ $profile->address ?? 'ยังไม่ได้เพิ่มที่อยู่ร้าน' }}</p>
      </div>
    </section>

    {{-- Form --}}
    <section class="bg-white rounded-2xl shadow-soft p-6">
      @if (session('status'))
        <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 px-4 py-3">{{ session('status') }}</div>
      @endif
      @if ($errors->any())
        <div class="mb-4 rounded-xl bg-rose-50 text-rose-700 px-4 py-3">
          <ul class="list-disc ml-5">
            @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('seller.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('PUT')

        <div>
          <label class="block text-sm font-medium">ชื่อร้าน</label>
          <input name="shop_name" value="{{ old('shop_name', $profile->shop_name) }}" required
                 class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-2 focus:ring-blue-100">
        </div>

        <div>
          <label class="block text-sm font-medium">ที่อยู่ร้าน</label>
          <textarea name="address" rows="3"
                    class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-2 focus:ring-blue-100">{{ old('address', $profile->address) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium">โลโก้</label>
            <input type="file" name="logo" accept="image/*"
                   class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2">
            @if($profile->logo_path)
              <img src="{{ asset('storage/'.$profile->logo_path) }}" class="mt-2 h-20 w-20 object-cover rounded border">
            @endif
          </div>

          <div>
            <label class="block text-sm font-medium">รูปหน้าร้าน / แบนเนอร์</label>
            <input type="file" name="photo" accept="image/*"
                   class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2">
            @if($profile->photo_path)
              <img src="{{ asset('storage/'.$profile->photo_path) }}" class="mt-2 h-24 w-40 object-cover rounded border">
            @endif
          </div>
        </div>

        <div class="pt-2">
          <button class="rounded-xl bg-primary text-white px-5 py-2.5 hover:bg-blue-700">บันทึก</button>
        </div>
      </form>
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
