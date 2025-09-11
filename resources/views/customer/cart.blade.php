<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Cart</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
<main class="max-w-4xl mx-auto px-4 py-8">
  <div class="mb-4 flex items-center justify-between">
    <h1 class="text-xl font-bold">ตะกร้าสินค้า</h1>
    <a href="{{ route('customer.shop') }}" class="text-blue-600 hover:underline">เลือกซื้อสินค้าต่อ</a>
  </div>

  @if (session('status'))
    <div class="mb-4 rounded bg-green-50 text-green-700 px-4 py-2">{{ session('status') }}</div>
  @endif
  @if (session('error'))
    <div class="mb-4 rounded bg-rose-50 text-rose-700 px-4 py-2">{{ session('error') }}</div>
  @endif

  @php
    // ป้องกันกรณีไม่ได้ส่งตัวแปรมา
    $cart  = $cart  ?? [];
    $total = $total ?? 0;
  @endphp

  @if(empty($cart))
    <div class="rounded-xl bg-white border p-6 text-center text-slate-500">ตะกร้ายังว่างอยู่</div>
  @else
    <div class="bg-white border rounded-xl overflow-hidden">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-50">
          <tr>
            <th class="px-4 py-2 text-left">สินค้า</th>
            <th class="px-4 py-2 text-left">ราคา</th>
            <th class="px-4 py-2 text-left">จำนวน</th>
            <th class="px-4 py-2 text-left">รวม</th>
            <th class="px-4 py-2"></th>
          </tr>
        </thead>
        <tbody>
          {{-- $cart เก็บเป็น: product_id => [name, price, qty, image_url, ...] --}}
          @foreach($cart as $product_id => $row)
            @php
              $price = (float)($row['price'] ?? 0);
              $qty   = (int)  ($row['qty']   ?? 0);
              $sum   = $price * $qty;

              // รองรับทั้ง image_url และ image (เผื่อ view เก่า)
              $img    = $row['image_url'] ?? ($row['image'] ?? null);
              $imgSrc = $img
                        ? (\Illuminate\Support\Str::startsWith($img, ['http://','https://'])
                              ? $img
                              : asset('storage/'.$img))
                        : null;
            @endphp

            <tr class="border-t">
              <td class="px-4 py-2">
                <div class="flex items-center gap-3">
                  @if($imgSrc)
                    <img src="{{ $imgSrc }}" class="w-12 h-12 object-cover rounded border" alt="{{ $row['name'] ?? 'product' }}">
                  @else
                    <div class="w-12 h-12 rounded border bg-slate-100"></div>
                  @endif
                  <div>
                    <div class="font-medium">{{ $row['name'] ?? '-' }}</div>
                    <div class="text-xs text-slate-500">
                      ขนาด: {{ $row['size'] ?? '—' }} | สี: {{ $row['color'] ?? '—' }}
                    </div>
                  </div>
                </div>
              </td>

              <td class="px-4 py-2">฿{{ number_format($price, 2) }}</td>

              <td class="px-4 py-2">
                <form method="POST" action="{{ route('customer.cart.update', $product_id) }}" class="flex items-center gap-2">
                  @csrf @method('PATCH')
                  <input type="number" name="qty" value="{{ $qty ?: 1 }}" min="1"
                         class="w-20 rounded border px-2 py-1">
                  <button class="rounded border px-2 py-1 hover:bg-slate-50">อัปเดต</button>
                </form>
              </td>

              <td class="px-4 py-2">฿{{ number_format($sum, 2) }}</td>

              <td class="px-4 py-2">
                <form method="POST" action="{{ route('customer.cart.remove', $product_id) }}"
                      onsubmit="return confirm('ลบรายการนี้?')">
                  @csrf @method('DELETE')
                  <button class="text-rose-600 hover:underline">ลบ</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="p-4 border-t flex items-center justify-between">
        <form method="POST" action="{{ route('customer.cart.clear') }}"
              onsubmit="return confirm('ล้างตะกร้าทั้งหมด?')">
          @csrf @method('DELETE')
          <button class="text-slate-600 hover:underline">ล้างตะกร้า</button>
        </form>

        <div class="text-right">
          <div class="text-slate-500 text-sm">ยอดรวม</div>
          <div class="text-2xl font-bold">฿{{ number_format($total, 2) }}</div>
          <a href="{{ route('customer.checkout') }}"
             class="mt-2 inline-block rounded bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">
            ไปชำระเงิน
          </a>
        </div>
      </div>
    </div>
  @endif
</main>
</body>
</html>
