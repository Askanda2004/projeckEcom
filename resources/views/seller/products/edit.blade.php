<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Seller • Edit Product</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { colors:{ primary:{DEFAULT:'#2563eb'} }, boxShadow:{soft:'0 8px 30px rgba(0,0,0,0.08)'} } } }
  </script>
</head>
<body class="bg-slate-50 text-slate-800">

  {{-- HEADER --}}
  <header class="sticky top-0 z-30 bg-white/90 backdrop-blur shadow-soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="font-bold">Seller • Edit Product</div>
      <a href="{{ route('seller.products.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Back to list</a>
    </div>
  </header>

  @php
    $isProductSection = request()->routeIs('seller.products.*')
                      || request()->routeIs('seller.categories.*')
                      || request()->routeIs('seller.subcategories.*');
  @endphp

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-12 gap-6">
      {{-- ========== SIDEBAR ========== --}}
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
              <div class="text-xs text-slate-500 truncate">{{ auth()->user()->name }}</div>
            </div>
          </div>
          
          <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Menu</h3>
          <nav class="space-y-1">
            <a href="{{ route('seller.reports.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100">
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

            <a href="{{ route('seller.orders.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4m-9 4v6"/></svg>
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
      {{-- ========== /SIDEBAR ========== --}}

      {{-- ========== MAIN ========== --}}
      <section class="col-span-12 md:col-span-9 space-y-6">

        @if ($errors->any())
          <div class="rounded-2xl bg-red-50 text-red-700 px-4 py-3 shadow-soft">
            <ul class="list-disc pl-5">
              @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
          </div>
        @endif

        {{-- ฟอร์มที่ 1: อัปเดตข้อมูลสินค้า + เพิ่มรูปใหม่ --}}
        <div class="bg-white rounded-2xl shadow-soft p-6 sm:p-8">
          <form method="POST"
                action="{{ route('seller.products.update', $product) }}"
                enctype="multipart/form-data"
                class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            @method('PATCH')

            <div class="md:col-span-2">
              <label class="block text-sm font-medium">ชื่อสินค้า</label>
              <input name="name" value="{{ old('name', $product->name) }}" required
                    class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
            </div>

            <div>
              <label class="block text-sm font-medium">หมวดหมู่</label>
              <select name="category_id"
                      class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
                <option value="">— ไม่ระบุ —</option>
                @foreach ($categories as $c)
                  <option value="{{ $c->category_id }}" @selected(old('category_id', $product->category_id) == $c->category_id)>
                    {{ $c->category_name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium">ขนาด</label>
              <select name="size"
                      class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
                <option value="">— เลือกขนาด —</option>
                <option value="S"  @selected(old('size', $product->size) == 'S')>S</option>
                <option value="M"  @selected(old('size', $product->size) == 'M')>M</option>
                <option value="L"  @selected(old('size', $product->size) == 'L')>L</option>
                <option value="XL" @selected(old('size', $product->size) == 'XL')>XL</option>
                <option value="47" @selected(old('size', $product->size) == '47')>47</option>
                <option value="50" @selected(old('size', $product->size) == '50')>50</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium">สี</label>
              <select name="color"
                      class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
                <option value="">— เลือกสี —</option>
                <option value="แดง"    @selected(old('color', $product->color) == 'แดง')>แดง</option>
                <option value="น้ำเงิน" @selected(old('color', $product->color) == 'น้ำเงิน')>น้ำเงิน</option>
                <option value="เขียว"  @selected(old('color', $product->color) == 'เขียว')>เขียว</option>
                <option value="ดำ"     @selected(old('color', $product->color) == 'ดำ')>ดำ</option>
                <option value="ขาว"    @selected(old('color', $product->color) == 'ขาว')>ขาว</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium">ราคา</label>
              <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" required
                    class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
            </div>

            <div>
              <label class="block text-sm font-medium">จำนวนคงเหลือ</label>
              <input type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required
                    class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium">แบรนด์สินค้าชื่อ</label>
              <textarea name="description" rows="2"
                        class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- อัปโหลดรูปเพิ่ม (หลายรูป) + preview --}}
            <div class="md:col-span-2">
              <label class="block text-sm font-medium">อัปโหลดรูปเพิ่ม (ไม่บังคับ)</label>
              <input id="newImages" type="file" name="images[]" accept="image/*" multiple
                    class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
              @error('images') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
              @error('images.*') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror

              <div id="previewWrapNew" class="mt-3 hidden">
                <div class="flex items-center justify-between mb-2">
                  <p class="text-xs text-slate-500">ตัวอย่างรูปใหม่:</p>
                  <button type="button" id="clearImagesNew"
                          class="text-xs px-2 py-1 rounded-lg border border-slate-200 hover:bg-slate-50">
                    ล้างรูปที่เลือก
                  </button>
                </div>
                <div id="previewListNew" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3"></div>
              </div>
            </div>

            <div class="md:col-span-2 flex items-center gap-3 pt-2">
              <button class="rounded-xl bg-primary text-white px-5 py-2.5 hover:bg-blue-700">บันทึกการแก้ไข</button>
              <a href="{{ route('seller.products.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 hover:bg-slate-50">ยกเลิก</a>
            </div>
          </form>
        </div>

        {{-- ส่วนรูปเดิม --}}
        <div class="bg-white rounded-2xl shadow-soft p-6 sm:p-8">
          <h2 class="font-semibold mb-3">รูปปัจจุบัน</h2>

          @php
            $images = $product->relationLoaded('images') ? $product->images : $product->images()->orderBy('ordering')->get();
          @endphp

          @if($images->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
              @foreach($images as $img)
                <div class="rounded-xl border border-slate-200 p-3">
                  <div class="relative mb-3">
                    <img src="{{ asset('storage/'.$img->path) }}" class="h-32 w-full object-cover rounded-lg border" alt="">
                    @if($img->is_primary)
                      <span class="absolute bottom-1 left-1 text-[10px] bg-primary text-white rounded px-1.5 py-0.5">Primary</span>
                    @endif
                  </div>

                  {{-- อัปเดตรูปนี้ --}}
                  <form method="POST"
                        action="{{ route('seller.products.images.update', [$product, $img]) }}"
                        enctype="multipart/form-data"
                        class="space-y-2">
                    @csrf
                    @method('PATCH')

                    <div>
                      <label class="text-xs text-slate-600 block mb-1">แทนที่รูปนี้</label>
                      <input type="file" name="replace_image" accept="image/*"
                             class="block w-full text-sm rounded-lg border border-slate-200 px-3 py-1.5 focus:border-primary focus:ring-primary/20">
                      @error('replace_image') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                      <button class="text-xs rounded-lg bg-primary text-white px-3 py-1.5 hover:bg-blue-700">
                        อัปเดตรูปนี้
                      </button>
                    </div>
                  </form>

                  {{-- ลบรูปนี้ --}}
                  <form method="POST"
                        action="{{ route('seller.products.images.destroy', [$product, $img]) }}"
                        onsubmit="return confirm('ลบรูปนี้ถาวร?')"
                        class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button class="text-xs rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-rose-700 hover:bg-rose-100">
                      ลบรูปนี้
                    </button>
                  </form>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-xs text-slate-400">— ไม่มีรูปปัจจุบัน —</p>
          @endif
        </div>

      </section>
      {{-- ========== /MAIN ========== --}}
    </div>
  </main>

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

  {{-- พรีวิวหลายรูป (ฟอร์มเพิ่มรูปใหม่) --}}
  <script>
    (function () {
      const input = document.getElementById('newImages');
      const wrap  = document.getElementById('previewWrapNew');
      const list  = document.getElementById('previewListNew');
      const clear = document.getElementById('clearImagesNew');

      let dt = new DataTransfer();

      input?.addEventListener('change', function () {
        dt = new DataTransfer();
        list.innerHTML = '';

        const files = Array.from(input.files || []);
        if (!files.length) { wrap.classList.add('hidden'); return; }

        files.forEach((file, idx) => {
          dt.items.add(file);
          const url = URL.createObjectURL(file);

          const item = document.createElement('div');
          item.className = 'relative group';
          item.innerHTML = `
            <img src="${url}" class="h-24 w-full object-cover rounded-lg border">
            <button type="button" data-index="${idx}"
              class="absolute -top-2 -right-2 hidden group-hover:block bg-white/90 border border-slate-200 rounded-full p-1 shadow">✕</button>
          `;

          item.querySelector('button').addEventListener('click', function () {
            const i = Number(this.dataset.index);
            const keep = Array.from(dt.files).filter((_, k) => k !== i);
            dt = new DataTransfer();
            keep.forEach(f => dt.items.add(f));
            input.files = dt.files;
            input.dispatchEvent(new Event('change'));
          });

          list.appendChild(item);
        });

        input.files = dt.files;
        wrap.classList.toggle('hidden', dt.files.length === 0);
      });

      clear?.addEventListener('click', function () {
        dt = new DataTransfer();
        input.value = '';
        list.innerHTML = '';
        wrap.classList.add('hidden');
      });
    })();
  </script>
</body>
</html>
