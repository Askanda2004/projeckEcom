<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Seller • Add Product</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // โทนมินิมอลให้เข้าชุดทั้งระบบ
    tailwind.config = {
      theme: {
        extend: {
          colors: { sand:'#FAFAF7', ink:'#111827', olive:'#7C8B6A', primary:{DEFAULT:'#2563eb'} },
          boxShadow: { soft:'0 6px 24px rgba(0,0,0,0.06)', card:'0 10px 35px rgba(0,0,0,0.08)' },
          borderRadius: { xl2:'1rem' }
        }
      }
    }
  </script>
  <style>
    .line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
    .req::after{content:" *"; color:#ef4444;}
  </style>
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
        <span class="font-semibold"> • หน้าเพิ่มสินค้า</span>
      </div>
      <form method="POST" action="{{ route('logout') }}"> @csrf
        <button class="px-3 py-1.5 text-sm rounded-lg border border-neutral-300 text-ink hover:bg-neutral-100 transition-colors">
            ออกจากระบบ
          </button>
      </form>
    </div>
  </header>

  @php
    // ใช้ตรวจ route ปัจจุบัน เพื่อเปิดเมนูและทำ active
    $isProductSection = request()->routeIs('seller.products.*') || request()->routeIs('seller.categories.*') || request()->routeIs('seller.subcategories.*');
  @endphp

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-12 gap-6">
      <!-- Sidebar -->
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
            <a href="{{ route('seller.reports.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
              การวิเคราะห์และรายงาน
            </a>

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
      <!-- /Sidebar -->

      <!-- Main -->
      <section class="col-span-12 md:col-span-9">
        @if ($errors->any())
          <div class="mb-6 rounded-2xl bg-rose-50 text-rose-700 px-4 py-3 border border-rose-200 shadow-soft">
            <ul class="list-disc pl-5">
              @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
          </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-100 shadow-soft p-6 sm:p-8">
          <form method="POST"
                action="{{ route('seller.products.store') }}"
                enctype="multipart/form-data"
                class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf

            <div class="md:col-span-2">
              <label class="block text-sm font-medium req">ชื่อสินค้า</label>
              <input name="name" value="{{ old('name') }}" required
                     class="mt-1 w-full h-11 rounded-xl border border-slate-200 bg-white/70 focus:bg-white px-3 outline-none focus:ring-2 focus:ring-olive/30">
            </div>

            <div>
              <label class="block text-sm font-medium">หมวดหมู่</label>
              <select name="category_id"
                      class="mt-1 w-full h-11 rounded-xl border border-slate-200 bg-white/70 focus:bg-white px-3 outline-none focus:ring-2 focus:ring-olive/30">
                <option value="">— ไม่ระบุ —</option>
                @foreach ($categories as $c)
                  <option value="{{ $c->category_id }}" @selected(old('category_id') == $c->category_id)>{{ $c->category_name }}</option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium">ขนาด</label>
              <select name="size"
                      class="mt-1 w-full h-11 rounded-xl border border-slate-200 bg-white/70 focus:bg-white px-3 outline-none focus:ring-2 focus:ring-olive/30">
                <option value="">— เลือกขนาด —</option>
                <option value="S"  @selected(old('size') == 'S')>S</option>
                <option value="M"  @selected(old('size') == 'M')>M</option>
                <option value="L"  @selected(old('size') == 'L')>L</option>
                <option value="XL" @selected(old('size') == 'XL')>XL</option>
                <option value="47" @selected(old('size') == '47')>47</option>
                <option value="50" @selected(old('size') == '50')>50</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium">สี</label>
              <select name="color"
                      class="mt-1 w-full h-11 rounded-xl border border-slate-200 bg-white/70 focus:bg-white px-3 outline-none focus:ring-2 focus:ring-olive/30">
                <option value="">— เลือกสี —</option>
                <option value="แดง"    @selected(old('color') == 'แดง')>แดง</option>
                <option value="ชมพู"    @selected(old('color') == 'ชมพู')>ชมพู</option>
                <option value="น้ำเงิน" @selected(old('color') == 'น้ำเงิน')>น้ำเงิน</option>
                <option value="ฟ้า"     @selected(old('color') == 'ฟ้า')>ฟ้า</option>
                <option value="เขียว"   @selected(old('color') == 'เขียว')>เขียว</option>
                <option value="ดำ"      @selected(old('color') == 'ดำ')>ดำ</option>
                <option value="ขาว"     @selected(old('color') == 'ขาว')>ขาว</option>
                <option value="กากี"    @selected(old('color') == 'กากี')>กากี</option>
                <option value="ครีม"    @selected(old('color') == 'ครีม')>ครีม</option>
                <option value="กรม"     @selected(old('color') == 'กรม')>กรม</option>
                <option value="เนื้อ"     @selected(old('color') == 'เนื้อ')>เนื้อ</option>
                <option value="เทา"     @selected(old('color') == 'เทา')>เทา</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium req">ราคา</label>
              <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" required
                     class="mt-1 w-full h-11 rounded-xl border border-slate-200 bg-white/70 focus:bg-white px-3 outline-none focus:ring-2 focus:ring-olive/30">
            </div>

            <div>
              <label class="block text-sm font-medium req">จำนวนคงเหลือ</label>
              <input type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required
                     class="mt-1 w-full h-11 rounded-xl border border-slate-200 bg-white/70 focus:bg-white px-3 outline-none focus:ring-2 focus:ring-olive/30">
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium">ยี่ห้อ / แบรณด์</label>
              <textarea name="description" rows="2"
                        class="mt-1 w-full rounded-xl border border-slate-200 bg-white/70 focus:bg-white px-3 py-2 outline-none focus:ring-2 focus:ring-olive/30">{{ old('description') }}</textarea>
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium">รูปภาพสินค้า</label>
              <input id="images" type="file" name="images[]" accept="image/*" multiple
                     class="mt-1 block w-full rounded-xl border border-slate-200 bg-white/70 focus:bg-white px-3 py-2 outline-none focus:ring-2 focus:ring-olive/30">
              @error('images') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
              @error('images.*') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror

              <div id="previewWrap" class="mt-3 hidden">
                <div class="flex items-center justify-between mb-2">
                  <p class="text-xs text-slate-500">ตัวอย่างรูปที่จะอัปโหลด:</p>
                  <button type="button" id="clearImages"
                          class="text-xs px-2 py-1 rounded-lg border border-slate-200 hover:bg-slate-50">
                    ล้างรูปที่เลือก
                  </button>
                </div>
                <div id="previewList" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3"></div>
              </div>
            </div>

            <div class="md:col-span-2 flex items-center gap-3 pt-2">
              <button class="rounded-xl bg-ink text-white px-5 h-11 hover:opacity-90">บันทึกสินค้า</button>
              <a href="{{ route('seller.products.index') }}" class="rounded-xl border border-slate-200 h-11 px-4 grid place-items-center hover:bg-slate-50">ยกเลิก</a>
            </div>
          </form>
        </div>
      </section>
      <!-- /Main -->
    </div>
  </main>

  <!-- toggle submenu -->
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

  <!-- พรีวิวหลายรูป + ลบทีละรูป + ล้างทั้งหมด -->
  <script>
    (function () {
      const input = document.getElementById('images');
      const wrap  = document.getElementById('previewWrap');
      const list  = document.getElementById('previewList');
      const clear = document.getElementById('clearImages');

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
