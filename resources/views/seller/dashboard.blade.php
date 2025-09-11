<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Seller • Dashboard</title>

  
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { primary: { DEFAULT: '#2563eb' } }, // blue-600
          boxShadow: { soft: '0 8px 30px rgba(0,0,0,0.08)' }
        }
      }
    }
  </script>
</head>
<body class="bg-slate-50 text-slate-800">

  <!-- Header -->
  <header class="sticky top-0 z-30 bg-white/90 backdrop-blur shadow-soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/></svg>
        </div>
        <span class="font-bold">Seller Dashboard</span>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('dashboard') }}" class="text-sm text-slate-600 hover:text-slate-900">Home</a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="px-3 py-1.5 text-sm rounded-lg bg-slate-900 text-white hover:bg-slate-800">Logout</button>
        </form>
      </div>
    </div>
  </header>

  <!-- Layout -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-12 gap-6">
    <!-- Sidebar -->
    <aside class="col-span-12 md:col-span-3">
      <div class="bg-white rounded-2xl shadow-soft p-4 md:p-5">
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Menu</h3>
        <nav class="space-y-1">
          <a href="{{ route('seller.reports.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-900">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
            Analytics & Reports
          </a>
          <a href="{{ route('seller.products.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100">
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

    <!-- Main -->
    {{-- <main class="col-span-12 md:col-span-9 space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Seller Dashboard</h1>
      </div>

      
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-2xl shadow-soft p-6">
          <div class="flex items-start justify-between">
            <h3 class="font-semibold">Today's Sales</h3>
            <span class="text-xs text-slate-500">Updated just now</span>
          </div>
          <div class="mt-4 text-3xl font-bold">฿12,450</div>
          <p class="mt-1 text-sm text-emerald-600">+8.2% from yesterday</p>
          <div class="mt-6 h-28 rounded-xl bg-slate-100"></div> 
        </div>

        
        <div class="bg-white rounded-2xl shadow-soft p-6">
          <div class="flex items-start justify-between">
            <h3 class="font-semibold">Orders</h3>
            <span class="text-xs text-slate-500">Today</span>
          </div>
          <div class="mt-4 text-3xl font-bold">37</div>
          <p class="mt-1 text-sm text-amber-600">3 pending to ship</p>
          <div class="mt-6 h-28 rounded-xl bg-slate-100"></div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-soft p-6">
          <div class="flex items-start justify-between">
            <h3 class="font-semibold">Top Product</h3>
            <span class="text-xs text-slate-500">This week</span>
          </div>
          <div class="mt-4 flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-slate-200"></div> 
            <div>
              <div class="font-semibold">Premium Hijab</div>
              <div class="text-sm text-slate-500">124 sold</div>
            </div>
          </div>
          <div class="mt-6 h-28 rounded-xl bg-slate-100"></div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-soft p-6">
          <div class="flex items-start justify-between">
            <h3 class="font-semibold">Messages</h3>
            <span class="text-xs text-slate-500">Inbox</span>
          </div>
          <div class="mt-4 text-3xl font-bold">5</div>
          <p class="mt-1 text-sm text-slate-500">New customer inquiries</p>
          <div class="mt-6 h-28 rounded-xl bg-slate-100"></div>
        </div>
      </div>
    </main> --}}
  </div>

</body>
</html>


{{-- <!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>Seller • Dashboard</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: { DEFAULT: '#2563eb' }, // blue-600
            ink: '#0f172a'
          },
          boxShadow: { soft: '0 8px 30px rgba(0,0,0,0.08)' },
          backgroundImage: {
            'soft-gradient': 'linear-gradient(135deg, rgba(37,99,235,0.08), rgba(14,165,233,0.08))'
          }
        }
      }
    }
  </script>
</head>
<body class="bg-slate-50 text-slate-800">

  <!-- Header -->
  <header class="sticky top-0 z-30 bg-white/90 backdrop-blur shadow-soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/></svg>
        </div>
        <span class="font-bold">Seller Dashboard</span>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('dashboard') }}" class="text-sm text-slate-600 hover:text-slate-900">Home</a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="px-3 py-1.5 text-sm rounded-lg bg-slate-900 text-white hover:bg-slate-800">Logout</button>
        </form>
      </div>
    </div>
  </header>

  <!-- Layout -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-12 gap-6">
    <!-- Sidebar -->
    <aside class="col-span-12 md:col-span-3">
      <div class="bg-white rounded-2xl shadow-soft p-4 md:p-5">
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Menu</h3>
        <nav class="space-y-1">
          <a href="{{ route('seller.reports.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-900">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
            Analytics & Reports
          </a>
          <a href="{{ route('seller.products.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100">
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

    <!-- Main -->
    <main class="col-span-12 md:col-span-9 space-y-6">

      <!-- Welcome / Hero -->
      <section class="rounded-2xl bg-soft-gradient p-6 md:p-8 shadow-soft">
        <div class="flex items-start md:items-center gap-4 md:gap-6">
          <div class="w-12 h-12 rounded-2xl bg-white/80 flex items-center justify-center shadow-soft">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-width="2" d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7"/>
            </svg>
          </div>
          <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-ink">
              ยินดีต้อนรับสู่ร้านของฉัน
              <span class="font-semibold text-primary">
                {{ auth()->user()->name ?? 'Seller' }}
              </span>
            </h1>
            <p class="mt-2 text-slate-600 leading-relaxed">
              ขอบคุณที่ไว้วางใจให้แพลตฟอร์มของเราช่วยดูแลการขายของคุณ
              หน้านี้ถูกออกแบบให้เรียบง่าย อ่านง่าย และโฟกัสกับสิ่งสำคัญ—เนื้อหาของร้านคุณเอง
              ขอให้วันนี้เป็นวันที่ยอดขายดี มีลูกค้าใหม่ ๆ และลูกค้าเก่ากลับมาอีกครั้ง 🎉
            </p>
            <div class="mt-4 inline-flex items-center gap-2 text-xs text-slate-500">
              <span class="px-2 py-1 rounded-full bg-white/70 shadow-soft">โหมดผู้ขาย</span>
              <span class="px-2 py-1 rounded-full bg-white/70 shadow-soft"> UI แบบเรียบง่าย</span>
            </div>
          </div>
        </div>
      </section>

      <!-- Simple Info blocks (text-only, no buttons) -->
      <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <article class="bg-white rounded-2xl shadow-soft p-6">
          <h2 class="text-lg font-semibold text-ink">แนวทางการดูแลร้านแบบสั้น ๆ</h2>
          <ul class="mt-3 space-y-2 text-slate-600">
            <li class="flex gap-2">
              <span class="mt-1 w-1.5 h-1.5 rounded-full bg-primary/60"></span>
              อัปเดตรายละเอียดสินค้าให้ครบถ้วนและอ่านง่าย
            </li>
            <li class="flex gap-2">
              <span class="mt-1 w-1.5 h-1.5 rounded-full bg-primary/60"></span>
              ตอบข้อความลูกค้าอย่างสุภาพและรวดเร็ว
            </li>
            <li class="flex gap-2">
              <span class="mt-1 w-1.5 h-1.5 rounded-full bg-primary/60"></span>
              จัดการสต็อกให้พร้อมส่งเสมอ ลดโอกาสพลาดการขาย
            </li>
          </ul>
          <p class="mt-4 text-sm text-slate-500">
            *เคล็ดลับ: คำอธิบายสินค้าที่ดีและรูปภาพชัดเจนช่วยเพิ่มโอกาสในการตัดสินใจของลูกค้าได้มาก*
          </p>
        </article>

        <article class="bg-white rounded-2xl shadow-soft p-6">
          <h2 class="text-lg font-semibold text-ink">บันทึกจากร้านของคุณ</h2>
          <p class="mt-3 text-slate-600 leading-relaxed">
            สร้างมาตรฐานบริการที่ดีในแบบของคุณเอง—รักษาเอกลักษณ์ของแบรนด์
            และทำให้ลูกค้ารู้สึก “อยากกลับมาอีก” ด้วยประสบการณ์ที่ราบรื่นตั้งแต่หน้าแรกจนถึงหลังการขาย
          </p>
          <blockquote class="mt-4 border-l-4 border-primary/30 pl-4 text-slate-500 italic">
            “รายละเอียดเล็ก ๆ ที่ใส่ใจ กลายเป็นความประทับใจที่ลูกค้าจดจำ”
          </blockquote>
        </article>
      </section>

      <!-- Footer note (text-only) -->
      <section class="bg-white rounded-2xl shadow-soft p-5">
        <p class="text-sm text-slate-500">
          หน้านี้เป็นพื้นที่ต้อนรับแบบเรียบง่าย ไม่มีปุ่มหรือกราฟ เพื่อให้คุณโฟกัสกับสารสำคัญของร้านได้เต็มที่
        </p>
      </section>

    </main>
  </div>

</body>
</html> --}}
