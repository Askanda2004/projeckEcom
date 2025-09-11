<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Seller • Add Product</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { colors:{ primary:{DEFAULT:'#2563eb'} }, boxShadow:{soft:'0 8px 30px rgba(0,0,0,0.08)'} } } }
  </script>
</head>
<body class="bg-slate-50 text-slate-800">
  <header class="sticky top-0 z-30 bg-white/90 backdrop-blur shadow-soft">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="font-bold">Add Product</div>
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

    <div class="bg-white rounded-2xl shadow-soft p-6 sm:p-8">
      {{-- ✅ ต้องใส่ enctype เพื่อส่งไฟล์ --}}
      <form method="POST"
            action="{{ route('seller.products.store') }}"
            enctype="multipart/form-data"
            class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @csrf

        <div class="md:col-span-2">
          <label class="block text-sm font-medium">ชื่อสินค้า</label>
          <input name="name" value="{{ old('name') }}" required
                 class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
        </div>

        <div>
          <label class="block text-sm font-medium">ขนาด</label>
          <input name="size" value="{{ old('size') }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
        </div>

        <div>
          <label class="block text-sm font-medium">สี</label>
          <input name="color" value="{{ old('color') }}"
                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
        </div>

        <div>
          <label class="block text-sm font-medium">ราคา</label>
          <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" required
                 class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
        </div>

        <div>
          <label class="block text-sm font-medium">จำนวนคงเหลือ</label>
          <input type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required
                 class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
        </div>

        <div>
          <label class="block text-sm font-medium">หมวดหมู่</label>
          <select name="category_id"
                  class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
            <option value="">— ไม่ระบุ —</option>
            @foreach ($categories as $c)
              <option value="{{ $c->category_id }}" @selected(old('category_id') == $c->category_id)>
                {{ $c->category_name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium">แบรนด์สินค้าชื่อ</label>
          <textarea name="description" rows="4"
                    class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">{{ old('description') }}</textarea>
        </div>

        {{-- ✅ อัปโหลดรูปจากไฟล์ + preview --}}
        <div class="md:col-span-2">
          <label class="block text-sm font-medium">รูปภาพสินค้า</label>
          <input id="image" type="file" name="image" accept="image/*"
                 class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-primary focus:ring-primary/20">
          @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror

          <div id="previewWrap" class="mt-3 hidden">
            <p class="text-xs text-slate-500 mb-1">ตัวอย่างรูปที่จะอัปโหลด:</p>
            <img id="previewImg" class="h-24 rounded border">
          </div>
        </div>

        <div class="md:col-span-2 flex items-center gap-3 pt-2">
          <button class="rounded-xl bg-primary text-white px-5 py-2.5 hover:bg-blue-700">บันทึกสินค้า</button>
          <a href="{{ route('seller.products.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 hover:bg-slate-50">ยกเลิก</a>
        </div>
      </form>
    </div>
  </main>

  <script>
    // แสดง preview รูป
    const input = document.getElementById('image');
    const wrap  = document.getElementById('previewWrap');
    const img   = document.getElementById('previewImg');

    input?.addEventListener('change', (e) => {
      const file = e.target.files?.[0];
      if (!file) { wrap.classList.add('hidden'); return; }
      img.src = URL.createObjectURL(file);
      wrap.classList.remove('hidden');
    });
  </script>
</body>
</html>
