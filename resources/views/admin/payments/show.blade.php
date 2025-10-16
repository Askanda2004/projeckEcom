<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ตรวจสอบสลิป • Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
<main class="max-w-4xl mx-auto p-6 space-y-6">
  <a href="{{ route('admin.payments.index') }}" class="text-slate-600 hover:underline">&larr; กลับ</a>

  <div class="bg-white rounded-2xl shadow p-6 space-y-4">
    <h1 class="text-xl font-bold">รายละเอียดการสั่งซื้อ</h1>
    <div class="text-sm text-slate-600">
      <p>ผู้สั่งซื้อ: {{ $order->shipping_name }} ({{ $order->shipping_phone }})</p>
      <p>วันที่สั่ง: {{ \Carbon\Carbon::parse($order->order_date)->setTimezone('Asia/Bangkok')->format('d/m/Y H:i') }}</p>
      <p>ยอดรวม: ฿{{ number_format($order->total_amount,2) }}</p>
      <p>สถานะชำระเงิน: <strong>{{ $order->payment_status }}</strong></p>
    </div>

    @if($order->payment_slip)
      <div class="mt-4">
        <h2 class="font-semibold mb-2">สลิปชำระเงิน</h2>
        <img src="{{ asset('storage/'.$order->payment_slip) }}" class="rounded-lg shadow max-h-[500px]" alt="Payment Slip">
      </div>
    @endif

    <div class="flex gap-3 pt-4">
      <form method="POST" action="{{ route('admin.payments.verify', $order->order_id) }}">
        @csrf @method('PATCH')
        <button class="rounded-lg bg-emerald-600 text-white px-4 py-2 hover:bg-emerald-700">✅ ยืนยันการชำระเงิน</button>
      </form>

      {{-- <form method="POST" action="{{ route('admin.payments.reject', $order->order_id) }}">
        @csrf @method('PATCH')
        <button class="rounded-lg bg-rose-600 text-white px-4 py-2 hover:bg-rose-700">❌ ปฏิเสธ</button>
      </form> --}}
    </div>
  </div>
</main>
</body>
</html>
