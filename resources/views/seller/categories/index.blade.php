<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>หมวดหมู่สินค้า • Seller</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
  <main class="max-w-5xl mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">หมวดหมู่สินค้า</h1>

    <div class="bg-white rounded-2xl shadow p-4">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left text-slate-500 border-b">
            <th class="py-2">#</th>
            <th class="py-2">ชื่อหมวดหมู่</th>
            <th class="py-2 text-right">จำนวนสินค้า</th>
            <th class="py-2"></th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $i => $c)
            <tr class="border-b last:border-0">
              <td class="py-2">{{ $i+1 }}</td>
              <td class="py-2">{{ $c->category_name }}</td>
              <td class="py-2 text-right">{{ $c->products_count }}</td>
              <td class="py-2 text-right">
                <a href="{{ route('seller.products.index') }}?category={{ urlencode($c->category_name) }}"
                   class="text-primary hover:underline">ดูสินค้าในหมวดนี้</a>
              </td>
            </tr>
          @empty
            <tr><td colspan="4" class="py-6 text-center text-slate-500">ยังไม่มีหมวดหมู่</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      <a href="{{ route('seller.products.index') }}" class="text-sm text-slate-600 hover:underline">&larr; กลับไปจัดการสินค้า</a>
    </div>
  </main>
</body>
</html>
