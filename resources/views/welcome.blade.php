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
        ['name' => 'Colour Catalogue', 'price' => 459,  'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/533175828_669630792819716_312639200855086374_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=833d8c&_nc_ohc=q_mf07N8iYQQ7kNvwHaPIW4&_nc_oc=Adkj1b2ExSy62JV1uDAt1HGOttblTG4H22xZayg-qEQ1VefFxooUzDsQmHGDHw2rddY&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=zCKe_xwMWilLRpWEtr959Q&oh=00_AfbXEsny0X9OACsL6Z7DuQkbwLd4zuFnL_9zz9Tj_4tZRg&oe=68C79C98'],
        ['name' => 'Colour Catalogue',  'price' => 459, 'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/533590906_669630352819760_8559155399271928294_n.jpg?_nc_cat=111&ccb=1-7&_nc_sid=833d8c&_nc_ohc=yWTa2Xrrc5oQ7kNvwELQyQo&_nc_oc=AdnR-SIPa-ZAPoU38DvKu9MVChTkO3lpMkYJ3B5iFp_LOrEgEPsGSohfNS5V33sZuXw&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=kdA8ytb_UCXmjbgWboIkJw&oh=00_AfbeOX14qlgVnTN-6lhtf10uGq9K4t-49UBDkCfOE423vw&oe=68C7C48C'],
        ['name' => 'Colour Catalogue','price' => 459,  'image' => 'https://scontent-bkk1-2.xx.fbcdn.net/v/t39.30808-6/533158328_669630259486436_797284571621129789_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=833d8c&_nc_ohc=oeOwy1NX4CYQ7kNvwGfGQgK&_nc_oc=AdnMpzo-LGa5gjuO0yTcrd5jHaGdMOLFMjdFXdf7AH_2dXAbpQnQTwLboGdrs9CaqUQ&_nc_zt=23&_nc_ht=scontent-bkk1-2.xx&_nc_gid=jbAzyj9rqXyXvYNU6g66gg&oh=00_AfYLiwlAS5NL0IBw9xvj1mFWmWSod1iZ_W6Yi4SNafer5A&oe=68C79CB0'],
        ['name' => 'Colour Catalogue',   'price' => 459,  'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/534160880_669630686153060_8974598005241848058_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=833d8c&_nc_ohc=0-JoA1qmVzEQ7kNvwG5dlic&_nc_oc=AdnA4fjHCdXDuMm9xlk1SMeyOIBIF5FRvL_k9jvbb0sej7qWmzl5d5A5WWoyQZ8Sepw&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=nKqyagXCPpP3hY16jpd7vQ&oh=00_AfYKy8qwHE6s2tcw9IQf1DrZBy37AOp89Nimj5AWO7GENA&oe=68C7C3E4'],
        
        ['name' => 'Colour Catalogue', 'price' => 459,  'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/533175828_669630792819716_312639200855086374_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=833d8c&_nc_ohc=q_mf07N8iYQQ7kNvwHaPIW4&_nc_oc=Adkj1b2ExSy62JV1uDAt1HGOttblTG4H22xZayg-qEQ1VefFxooUzDsQmHGDHw2rddY&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=zCKe_xwMWilLRpWEtr959Q&oh=00_AfbXEsny0X9OACsL6Z7DuQkbwLd4zuFnL_9zz9Tj_4tZRg&oe=68C79C98'],
        ['name' => 'Colour Catalogue',  'price' => 459, 'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/533590906_669630352819760_8559155399271928294_n.jpg?_nc_cat=111&ccb=1-7&_nc_sid=833d8c&_nc_ohc=yWTa2Xrrc5oQ7kNvwELQyQo&_nc_oc=AdnR-SIPa-ZAPoU38DvKu9MVChTkO3lpMkYJ3B5iFp_LOrEgEPsGSohfNS5V33sZuXw&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=kdA8ytb_UCXmjbgWboIkJw&oh=00_AfbeOX14qlgVnTN-6lhtf10uGq9K4t-49UBDkCfOE423vw&oe=68C7C48C'],
        ['name' => 'Colour Catalogue','price' => 459,  'image' => 'https://scontent-bkk1-2.xx.fbcdn.net/v/t39.30808-6/533158328_669630259486436_797284571621129789_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=833d8c&_nc_ohc=oeOwy1NX4CYQ7kNvwGfGQgK&_nc_oc=AdnMpzo-LGa5gjuO0yTcrd5jHaGdMOLFMjdFXdf7AH_2dXAbpQnQTwLboGdrs9CaqUQ&_nc_zt=23&_nc_ht=scontent-bkk1-2.xx&_nc_gid=jbAzyj9rqXyXvYNU6g66gg&oh=00_AfYLiwlAS5NL0IBw9xvj1mFWmWSod1iZ_W6Yi4SNafer5A&oe=68C79CB0'],
        ['name' => 'Colour Catalogue',   'price' => 459,  'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/534160880_669630686153060_8974598005241848058_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=833d8c&_nc_ohc=0-JoA1qmVzEQ7kNvwG5dlic&_nc_oc=AdnA4fjHCdXDuMm9xlk1SMeyOIBIF5FRvL_k9jvbb0sej7qWmzl5d5A5WWoyQZ8Sepw&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=nKqyagXCPpP3hY16jpd7vQ&oh=00_AfYKy8qwHE6s2tcw9IQf1DrZBy37AOp89Nimj5AWO7GENA&oe=68C7C3E4'],
      ];
  @endphp

  {{-- ====== Navbar ====== --}}
  <header class="sticky top-0 z-30 bg-white/80 backdrop-blur shadow-soft">
    <div class="max-w-7xl mx-auto h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
          <svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
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
            src="https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/529863620_664429120006550_5322723272537951143_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=f727a1&_nc_ohc=mRcTrzZYKbgQ7kNvwFmGasP&_nc_oc=Adk37dKnExxiXfTeEM-ZyeWVoLDlamYih_bJQ1fvElFS5Q7doIGBnONFQlUJy4VUWOo&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=68L3OV7YvgjvmYr_uZuiIA&oh=00_AfZkN0u-mj9gVy034dHwLlCKDZDtUXZxWhGXIYm6-QiqxA&oe=68C7948B">
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



