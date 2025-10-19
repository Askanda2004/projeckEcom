<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'My Shop') }}</title>

  <!-- Tailwind via CDN -->
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
<body class="bg-slate-50 text-slate-800 antialiased">

  {{-- ====== (แก้ได้) สินค้าแนะนำเริ่มต้น ถ้า Controller ไม่ส่งมา ====== --}}
  @php
    /** ถ้ามีการส่ง $featuredProducts มาจาก Controller จะใช้ค่านั้นแทน */
    $featuredProducts = $featuredProducts
      ?? [
        // เปลี่ยนลิงก์รูป/ชื่อ/ราคา ได้เองตามต้องการ
        ['name' => 'Delina Series', 'price' => 459,  'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/557933130_713684545081007_4307792067794112513_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=833d8c&_nc_ohc=dTYE1dFGqtsQ7kNvwHi4Brd&_nc_oc=AdkYLlST_yR_2zKefXgrWvU-WoW1RHj6rKSip_XaxyeQOqEZMlGto_QSlvwMl4DBxDk&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=n8By1iZgSFD80mmGpoEpKQ&oh=00_AffVa3_4DgY1TqVIbg6MuYc9SAgSW7I127-KPMNtDcxFOA&oe=68F91F81'],
        ['name' => 'AMEERZAINI',  'price' => 459, 'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/558176949_710047298778065_2231450108234336752_n.jpg?stp=dst-jpg_p960x960_tt6&_nc_cat=110&ccb=1-7&_nc_sid=833d8c&_nc_ohc=2R1H9Ui9nksQ7kNvwGN7rAo&_nc_oc=AdkuyX_8PkwOfWpnySSnFow501RbEScKpsa7J4h-Ro0QSy4RcWGmOK09WN1uJw6fPhk&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=jKbKtzB-mafBKuRfmfYBLw&oh=00_AfeW0VRIZMikx1G_4RDAlRkR5FMkiCtpVPKKE9QJSOFFYg&oe=68F91BEC'],
        ['name' => 'Colour Catalogue','price' => 459,  'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/533590906_669630352819760_8559155399271928294_n.jpg?_nc_cat=111&ccb=1-7&_nc_sid=833d8c&_nc_ohc=aRrSUTQNDS8Q7kNvwHbqI3j&_nc_oc=AdkmNMTV2lXdSRoEBKTRZYCaPS_9qXE1mPqiJLBdr1IoidV8CwnRO7baYM1znGjVKbg&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=yrFlXwbaJhlplZzhMzUkCw&oh=00_Afc_4Tt6fxBe9ExW9wAu60DZ6-y-8c9xNRVBRlHndTYj5A&oe=68ECE6CC'],
        ['name' => 'Colour Catalogue',   'price' => 459,  'image' => 'https://scontent-bkk1-2.xx.fbcdn.net/v/t39.30808-6/533158328_669630259486436_797284571621129789_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=833d8c&_nc_ohc=iHI4qzY5sD4Q7kNvwEzroH7&_nc_oc=AdkZXEZTMwnRzzCWcrgSQM_6K84hoCnbYEsuXyPjWBuf0wC8m4y0oyZpzMcgadixULA&_nc_zt=23&_nc_ht=scontent-bkk1-2.xx&_nc_gid=Cry_ayj9mVdFc0_FW1c60w&oh=00_Aff1ECTSWQFBfPam5Dq9tA17R46fwz8oDzlHnd7h7PUOgg&oe=68ECF730'],
        
        ['name' => 'Colour Catalogue', 'price' => 459,  'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/533175828_669630792819716_312639200855086374_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=833d8c&_nc_ohc=HIlJrP6OgfwQ7kNvwFGkKY3&_nc_oc=AdmE23grzlMjp-vlC1m8tSKizplL3BQVj_8uiUl6So1GSKemVBz6Z6CTqaRdmNGyxvQ&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=9wvHvjVb-67__HsTJvktgA&oh=00_AffGKytReLCiLg4h_faNvSM15aklZUCfQNy8T6E1iHq92A&oe=68ECF718'],
        ['name' => 'Colour Catalogue',  'price' => 459, 'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/532583329_669630619486400_7802033679680961457_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=833d8c&_nc_ohc=D3-nxqdkfeEQ7kNvwHJIwAN&_nc_oc=Adkn8eHxJMD6A5ymxcLeapbEWFq5MKlWPl6WZsqEfqYpJ_gk3EP_mnvEHpNEVhRSKf8&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=8kYHiMD4ToYynx1sZRfl9w&oh=00_AfeQk2abR7EnG0PEAt7sfjHkWZ42gdxQU-Fxi6tM8WWaXw&oe=68ECD27D'],
        ['name' => 'Colour Catalogue','price' => 459,  'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/533590906_669630352819760_8559155399271928294_n.jpg?_nc_cat=111&ccb=1-7&_nc_sid=833d8c&_nc_ohc=aRrSUTQNDS8Q7kNvwHbqI3j&_nc_oc=AdkmNMTV2lXdSRoEBKTRZYCaPS_9qXE1mPqiJLBdr1IoidV8CwnRO7baYM1znGjVKbg&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=yrFlXwbaJhlplZzhMzUkCw&oh=00_Afc_4Tt6fxBe9ExW9wAu60DZ6-y-8c9xNRVBRlHndTYj5A&oe=68ECE6CC'],
        ['name' => 'Colour Catalogue',   'price' => 459,  'image' => 'https://scontent-bkk1-2.xx.fbcdn.net/v/t39.30808-6/533158328_669630259486436_797284571621129789_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=833d8c&_nc_ohc=iHI4qzY5sD4Q7kNvwEzroH7&_nc_oc=AdkZXEZTMwnRzzCWcrgSQM_6K84hoCnbYEsuXyPjWBuf0wC8m4y0oyZpzMcgadixULA&_nc_zt=23&_nc_ht=scontent-bkk1-2.xx&_nc_gid=Cry_ayj9mVdFc0_FW1c60w&oh=00_Aff1ECTSWQFBfPam5Dq9tA17R46fwz8oDzlHnd7h7PUOgg&oe=68ECF730'],
      ];
  @endphp

  {{-- ====== Navbar ====== --}}
  <header class="sticky top-0 z-30 bg-white/80 backdrop-blur shadow-soft">
    <div class="max-w-7xl mx-auto h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-primary/30 to-blue-200 flex items-center justify-center shadow-md">
          <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              d="M12 2a10 10 0 100 20 10 10 0 000-20zM9 12l2 2 4-4"/>
          </svg>
        </div>
        <span class="font-bold">แพลตฟอร์มร้านผ้าคลุม</span>
        {{-- <span class="font-bold">{{ config('app.name', 'My Shop') }}</span> --}}
      </div>

      <nav class="flex items-center gap-2">
        @auth
          <a href="{{ route('dashboard') }}"
             class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-50">Dashboard</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="rounded-lg bg-slate-900 text-white px-3 py-1.5 hover:bg-slate-800">Logout</button>
          </form>
        @else
          <a href="{{ route('login') }}" class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-50">Login</a>
          <a href="{{ route('register') }}" class="rounded-lg bg-primary text-white px-3 py-1.5 hover:bg-blue-700">Register</a>
        @endauth
      </nav>
    </div>
  </header>

  {{-- ====== Hero ====== --}}
  <section class="relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid lg:grid-cols-2 gap-10 items-center">
      <div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight">
          ยินดีต้อนรับสู่ แพลตฟอร์มอีคอมเมิร์ซของเรา
        </h1>
        <p class="mt-4 text-slate-600 text-lg">
          เลือกช้อปสินค้าที่คัดสรรมาแล้วในสไตล์ของคุณ—เรียบง่าย สบายตา และใช้งานง่าย
        </p>
        <div class="mt-6 flex flex-wrap gap-3">
          <a href="#featured"
             class="rounded-xl bg-primary text-white px-5 py-3 hover:bg-blue-700 shadow">
            ดูสินค้าแนะนำ
          </a>
          <a href="{{ route('login') }}"
             class="rounded-xl border border-slate-200 px-5 py-3 hover:bg-slate-50">
            เริ่มต้นใช้งาน
          </a>
        </div>
      </div>

      <div class="relative">
        <div class="aspect-[16/10] rounded-2xl overflow-hidden shadow-soft bg-slate-200">
          {{-- เปลี่ยนรูป Hero ตามชอบ (ใส่ asset('storage/..') ก็ได้) --}}
          <img
            alt="Hero"
            class="w-full h-full object-cover"
            src="https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/529863620_664429120006550_5322723272537951143_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=f727a1&_nc_ohc=ar6zYLaYWwIQ7kNvwHkrani&_nc_oc=Adkgtn-Mt7Al_u3iWYZBfW_nMtIgiZhDAlbZG5NuzC8VWZ3kdSI7uUNjzZXzWBeFcqU&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=Kz5ZXEfh3vUgrvaHCVWmgg&oh=00_AfeUtPgaaBUsQv9lIB6U3ZivWbDjAko5aISyNmcseJdikw&oe=68ECEF0B">
        </div>
      </div>
    </div>
  </section>

  {{-- ====== Featured ====== --}}
  <section id="featured" class="pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-6 flex items-end justify-between">
        <div>
          <h2 class="text-2xl font-bold">สินค้าแนะนำ</h2>
        </div>
        @guest
          <a href="{{ route('register') }}" class="text-primary hover:underline">สมัครสมาชิกเพื่อสั่งซื้อ</a>
        @endguest
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($featuredProducts as $p)
          <div class="bg-white rounded-2xl border shadow-soft overflow-hidden hover:-translate-y-0.5 transition">
            <div class="aspect-[4/3] bg-slate-100">
              <img src="{{ $p['image'] }}" alt="{{ $p['name'] }}" class="w-full h-full object-cover">
            </div>
            <div class="p-4">
              <div class="font-semibold truncate">{{ $p['name'] }}</div>
              <div class="mt-1 text-primary font-bold">฿{{ number_format((float) $p['price'], 2) }}</div>
              <div class="mt-3">
                @auth
                  <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-lg border px-3 py-1.5 hover:bg-slate-50">
                    จัดการ
                  </a>
                @else
                  <a href="{{ route('login') }}" class="inline-flex items-center rounded-lg bg-primary text-white px-3 py-1.5 hover:bg-blue-700">
                    เข้าสู่ระบบเพื่อสั่งซื้อ
                  </a>
                @endauth
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ====== Footer ====== --}}
  <footer class="border-t bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-sm text-slate-500 flex items-center justify-between">
      <span>© {{ date('Y') }} {{ config('app.name', 'My Shop') }}. All rights reserved.</span>
      <div class="flex items-center gap-4">
        <a href="#" class="hover:text-slate-700">Privacy</a>
        <a href="#" class="hover:text-slate-700">Terms</a>
      </div>
    </div>
  </footer>
</body>
</html>



