<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Seller • Edit Product</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { colors:{ primary:{DEFAULT:'#2563eb'} }, boxShadow:{soft:'0 8px 30px rgba(0,0,0,0.08)'} } } }
  </script>
</head>
<body class="bg-slate-50 text-slate-800">
  <header class="sticky top-0 z-30 bg-white/90 backdrop-blur shadow-soft">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="font-bold">Edit Product</div>
      <a href="{{ route('seller.products.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Back to list</a>
    </div>
  </header>

  <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if ($errors->any())
      <div class="mb-6 rounded-xl bg-red-50 text-red-700 px-4 py-3 shadow-soft">
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

        {{-- <div>
          <label class="block text-sm font-medium">ขนาด</label>
          <input name="size" value="{{ old('size', $product->size) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
        </div> --}}
        <div>
          <label class="block text-sm font-medium">ขนาด</label>
          <select name="size"
                  class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
            <option value="">— เลือกขนาด —</option>
            <option value="S"  @selected(old('size', $product->size) == 'S')>S</option>
            <option value="M"  @selected(old('size', $product->size) == 'M')>M</option>
            <option value="L"  @selected(old('size', $product->size) == 'L')>L</option>
            <option value="XL" @selected(old('size', $product->size) == 'XL')>XL</option>
          </select>
        </div>


        {{-- <div>
          <label class="block text-sm font-medium">สี</label>
          <input name="color" value="{{ old('color', $product->color) }}"
                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
        </div> --}}
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

        <div class="md:col-span-2">
          <label class="block text-sm font-medium">รายละเอียดสินค้า</label>
          <textarea name="description" rows="4"
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

    {{-- ส่วนรูปเดิม: ฟอร์มที่ 2..n (อยู่นอกฟอร์มหลัก) --}}
    <div class="mt-6 bg-white rounded-2xl shadow-soft p-6 sm:p-8">
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

              {{-- ฟอร์มอัปเดตรูปนี้ --}}
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

                {{-- <label class="inline-flex items-center gap-2 text-xs">
                  <input type="checkbox" name="set_primary" value="1" @checked($img->is_primary) class="rounded border-slate-300">
                  ตั้งเป็นรูปหลัก
                </label> --}}

                <div class="flex items-center gap-2 pt-1">
                  <button class="text-xs rounded-lg bg-primary text-white px-3 py-1.5 hover:bg-blue-700">
                    อัปเดตรูปนี้
                  </button>
                </div>
              </form>

              {{-- ฟอร์มลบรูป (ฟอร์มแยก) --}}
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
  </main>

  <script>
    // พรีวิวหลายรูป (ฟอร์มเพิ่มรูปใหม่)
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
