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
        ['name' => 'Colour Catalogue', 'price' => 459,  'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/532583329_669630619486400_7802033679680961457_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=833d8c&_nc_ohc=V0TXFcMfRBEQ7kNvwFJJGcF&_nc_oc=AdmJPCJ_19fyo6vZ_yXZDKDgW7mgygPiuiJO-VMEzCm65SB7jrIbqxWgJEazxRXO1jM&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=mHkyDAljdj83m-YrEsmxEA&oh=00_AfYlVggSfRMjotvEl9fshLShjOcchOUSPlAGY2LopAl4wg&oe=68DA253D'],
        ['name' => 'Colour Catalogue',  'price' => 459, 'image' => 'https://scontent-bkk1-2.xx.fbcdn.net/v/t39.30808-6/533953780_669630426153086_3314111719373890184_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=833d8c&_nc_ohc=a__kHn5A0wsQ7kNvwFOkqXx&_nc_oc=AdnLtef4q9UmpxWBH36Sermzv07rbyUeS_b1Sj7FkMoqy-dC1sAdc829gfLQZGBGgGU&_nc_zt=23&_nc_ht=scontent-bkk1-2.xx&_nc_gid=nKx_-lpD378phWbA0s-NWQ&oh=00_AfYQdMqKJWAjWdbXxUkvdAr3gNGtlk3zoDv4LsEEU3ycoA&oe=68DA41B7'],
        ['name' => 'Colour Catalogue','price' => 459,  'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/533665708_669630159486446_6378569838781900844_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=833d8c&_nc_ohc=6xhysC6Dw7AQ7kNvwEeTBc6&_nc_oc=Adn2jk8zqUr3fbQNRDX4q_4VWTPL2f74L86a5BXcQRiRQc5Iwn5xLnSfZq171Q-pn0k&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=CgdtUe3gzxjJVdVHNruvkw&oh=00_Afb6JP19cDowHRa7SFYrGmm_JkcrTUWK7bMCCXzQMSOnWg&oe=68DA2262'],
        ['name' => 'Colour Catalogue',   'price' => 459,  'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/532583329_669630619486400_7802033679680961457_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=833d8c&_nc_ohc=V0TXFcMfRBEQ7kNvwFJJGcF&_nc_oc=AdmJPCJ_19fyo6vZ_yXZDKDgW7mgygPiuiJO-VMEzCm65SB7jrIbqxWgJEazxRXO1jM&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=mHkyDAljdj83m-YrEsmxEA&oh=00_AfYlVggSfRMjotvEl9fshLShjOcchOUSPlAGY2LopAl4wg&oe=68DA253D'],
        
        ['name' => 'Colour Catalogue', 'price' => 459,  'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/533175828_669630792819716_312639200855086374_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=833d8c&_nc_ohc=3fkKY7NHSAsQ7kNvwHpVIl1&_nc_oc=Admo47aQsdvYWauK7hXsiyyVCM4BrZGZkCJltrZU7wbSzipGvK_2vuCZrV44GPwH-bE&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=FJ-Bk98YuiY5HujFSU9YXw&oh=00_AfbVkOXMa0mxa2Gq6wxlidGcl7eq6ahYf33YhX3fycoDWQ&oe=68DA49D8'],
        ['name' => 'Colour Catalogue',  'price' => 459, 'image' => 'https://scontent-bkk1-2.xx.fbcdn.net/v/t39.30808-6/533158328_669630259486436_797284571621129789_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=833d8c&_nc_ohc=ufFMNLKiKn4Q7kNvwFFedyT&_nc_oc=AdmQD2NT7aUjEbRy6SskYt_j5Swd6KGAgu3NduXg4fv3Yex4vpJr6eN9etmNj-_Fsug&_nc_zt=23&_nc_ht=scontent-bkk1-2.xx&_nc_gid=SXoU2zG-06_l0M303g3zOQ&oh=00_AfZCQ7qxV7nWRaoh8F4xsGSFhTldCjqOHy2FznUp9pXE5A&oe=68DA49F0'],
        ['name' => 'Colour Catalogue','price' => 459,  'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/533590906_669630352819760_8559155399271928294_n.jpg?_nc_cat=111&ccb=1-7&_nc_sid=833d8c&_nc_ohc=-udYy_2ond8Q7kNvwFDpasZ&_nc_oc=AdnwtvCwMibHwmjR5iOGdYvO9cnbRqZZg2CMs2D_jDh9vmGKVT3d2fJC7HQtF_pghEM&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=BsAdPlTZZ2ltzeN7uwY1LQ&oh=00_AfZePHogXVPKPHenrPYqUN-qEJAPzAEQdIRwRtJW5UYnXQ&oe=68DA398C'],
        ['name' => 'Colour Catalogue',   'price' => 459,  'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/534160880_669630686153060_8974598005241848058_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=833d8c&_nc_ohc=DdXblkIRlCoQ7kNvwH8HiRW&_nc_oc=AdnYYweGM6opJ65f5BnEFj-RoVz_rMTOHP1YglSs-jDxWH1-f1N77Izfrd-Y2IHwStI&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=oxpds8rFhcYaS8c4cv5ykA&oh=00_AfaKdxGx_WdqP1dcn5jvJhXeCa8LDjJXlpoXWjKBKGQfYA&oe=68DA38E4'],
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
            src="https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/529863620_664429120006550_5322723272537951143_n.jpg?stp=dst-jpg_p960x960_tt6&_nc_cat=108&ccb=1-7&_nc_sid=f727a1&_nc_ohc=NqmpksKB4qQQ7kNvwF45nG5&_nc_oc=AdkzNCC9LdABjf2xTfq0JjToOPOSzaNR_oZr3Gen5DLSaLsR7o4e45kLRpqbmgecX0k&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=uK5Yff33bcorPkGxWOeZGw&oh=00_AfZ8ketqUQC4nB0VyMHch3Leeks3B5MbNVBnWoIJ6-Z-Og&oe=68DA41CB">
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



