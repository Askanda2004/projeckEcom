<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'แพลตฟอร์มร้านผ้าคลุม') }}</title>

  <!-- Tailwind via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // โทนมินิมอล: sand(พื้น), ink(ข้อความ), olive(แอคเซนต์)
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            sand: '#FAFAF7',
            ink: '#111827',
            olive: '#7C8B6A',
          },
          boxShadow: {
            soft: '0 6px 24px rgba(0,0,0,0.06)',
          },
          borderRadius: {
            xl2: '1rem',
          }
        }
      }
    }
  </script>
</head>
<body class="bg-sand text-ink antialiased">

  {{-- ====== (แก้ได้) สินค้าแนะนำเริ่มต้น ถ้า Controller ไม่ส่งมา ====== --}}
  @php
    /** ถ้ามีการส่ง $featuredProducts มาจาก Controller จะใช้ค่านั้นแทน */
    $featuredProducts = $featuredProducts
      ?? [
        ['name' => 'สินค้าแนะนำ',    'price' => 890, 'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/543511326_686152997834162_653027445134899577_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=833d8c&_nc_ohc=sfEMIWREZ8cQ7kNvwGJ3aIP&_nc_oc=Adnd98oN98nOyzF6k6DcmfIhsCcno3sqnN7c-9joV3qNPxe2antXFH9yx4N5hGoyNm4&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=ZJ4dackIQD-fSU05zofDmQ&oh=00_Afdb61fQlT4EZ2PaAVmf7_Xo2WY5EmRLkNJaf_ZampOwUw&oe=68FA627F'],
        ['name' => 'สินค้าแนะนำ',    'price' => 159, 'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/562052870_721281290987999_1659636245073653228_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=f727a1&_nc_ohc=HJE9lImVRyIQ7kNvwFFU9f7&_nc_oc=Adm5oluAjUaXHoJnm51DTJYHggD79fGs2SUJkN21yidsWUr_e_ZLBSdh0Ns7MUbIOic&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=10hJoJ_3wYFTSDEzsMFD9Q&oh=00_AffXZlb8oD8D0JTt0fTHxWzJxnG9vj_wmq7cqWLi25435A&oe=68FA7FEB'],
        ['name' => 'สินค้าแนะนำ',    'price' => 349, 'image' => 'https://scontent-bkk1-2.xx.fbcdn.net/v/t39.30808-6/565177023_721268264322635_2855992648106859369_n.jpg?stp=c0.83.1027.1027a_dst-jpg_s552x414_tt6&_nc_cat=109&ccb=1-7&_nc_sid=92e838&_nc_ohc=C_TeWZJJHsUQ7kNvwHarR-b&_nc_oc=AdkGcj-JvTXlMYDJbhT5W5vQpo7cLEKJyP3X2274oMByOgZ3ZFPxSBkt4TBAGXb5Enc&_nc_zt=23&_nc_ht=scontent-bkk1-2.xx&_nc_gid=wX7vZTNyLV5vDl5opkT9UQ&oh=00_AfdNy11bcPp1Kdus_afzMXz2v92zSMaU97QR1KgMOYTp2w&oe=68FA6D54'],
        ['name' => 'สินค้าแนะนำ',    'price' => 459, 'image' => 'https://scontent-bkk1-2.xx.fbcdn.net/v/t39.30808-6/557642457_705564482559680_4938703705640271840_n.jpg?stp=dst-jpg_s552x414_tt6&_nc_cat=104&ccb=1-7&_nc_sid=714c7a&_nc_ohc=XlqPtkEJlRcQ7kNvwEdkfll&_nc_oc=Adme93vaIsKxraGrqj5GGQwcofXHyoC1JILBFdrAaoJ4U5QwaLgI9P8aI236su-bZZ4&_nc_zt=23&_nc_ht=scontent-bkk1-2.xx&_nc_gid=clUj7GgKVXiRAkTBYyevSA&oh=00_Afe_zF4V9Mq-qe4WkbdZ8Pjnrw4NWnoFlyS6FP9JQsOKUw&oe=68FA9522'],

        ['name' => 'สินค้าแนะนำ',    'price' => 199, 'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/534160880_669630686153060_8974598005241848058_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=833d8c&_nc_ohc=hOoe3V7Ylv0Q7kNvwGASq7Z&_nc_oc=AdldnmrAl_e5vpuKm-yf1Iz2SpBNl1-XiBYOMmaTYARKJtgYbIJgSpS9yQntJ5k5yi8&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=cFREKY0PlLwL46bydVJqrA&oh=00_Aff9NRPrzE3tMlnqNRNPI_CJmluEya5bXwj_4SOLh6zwdg&oe=68FA85A4'],
        ['name' => 'สินค้าแนะนำ',    'price' => 239, 'image' => 'https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/555628281_701504792965649_1590756232358708746_n.jpg?stp=dst-jpg_s552x414_tt6&_nc_cat=110&ccb=1-7&_nc_sid=714c7a&_nc_ohc=a3u40TTZCZsQ7kNvwFB5j9H&_nc_oc=AdnpxWrQHPvPZfJkCraiFByCCxin9rfK2j08ETxZXBrmCIAU4H-tZBiuVfw-DVr0YJA&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=E4jSy0n3qX1U3OJd7PD7tA&oh=00_AfdkN45Yud5WFErnc_F6W9ksKZIg9DDVdk_cJ_sXbrh1wA&oe=68FA9095'],
        ['name' => 'สินค้าแนะนำ',    'price' => 2499, 'image' => 'https://scontent-bkk1-2.xx.fbcdn.net/v/t39.30808-6/557304104_705558459226949_9018066486250290772_n.jpg?stp=dst-jpg_s552x414_tt6&_nc_cat=102&ccb=1-7&_nc_sid=714c7a&_nc_ohc=ZXfHuSuHUG4Q7kNvwHnHzUD&_nc_oc=Adnof8WhtrNeqwKGFPZjQJeb2Lsa2XlIUBmZFYBp1BhYCUbWYnhlGZWh8H9ZvBhvnIg&_nc_zt=23&_nc_ht=scontent-bkk1-2.xx&_nc_gid=qYUkpzIvSKKB03ztI7JP5Q&oh=00_Aff0hmHS1W6htDqbB-bbEqhl-T7JlszTbGDgpdvbLJPyPQ&oe=68FA9710'],
        ['name' => 'สินค้าแนะนำ',    'price' => 459, 'image' => 'https://scontent-bkk1-2.xx.fbcdn.net/v/t39.30808-6/554080766_701504126299049_6480003358265294630_n.jpg?stp=dst-jpg_s552x414_tt6&_nc_cat=105&ccb=1-7&_nc_sid=714c7a&_nc_ohc=BIChVpb6OSYQ7kNvwHvLBak&_nc_oc=AdlKCBOZlVWZCz4e4PLnSHGnymh2omIATkNrCpua_LKpgHwuHGQvOktncTSyV15qd2Q&_nc_zt=23&_nc_ht=scontent-bkk1-2.xx&_nc_gid=74FrOmFklq7U82q891X1eg&oh=00_Affo2FQtlj9lxx4kWCDxtK3S485aIDGRp0dlWsgWJlfm4g&oe=68FA99DB'],
      ];
  @endphp

  {{-- ====== Navbar (มินิมอล เรียบ สะอาด) ====== --}}
  <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-slate-100">
    <div class="max-w-7xl mx-auto h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
      <a href="{{ url('/') }}" class="flex items-center gap-2 group">
        <div class="w-9 h-9 rounded-xl bg-olive/10 flex items-center justify-center shadow">
          <svg class="w-5 h-5 text-olive" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M4 12h16M4 7h16M4 17h16" stroke-width="2"/>
          </svg>
        </div>
        <span class="font-semibold tracking-wide group-hover:opacity-80 transition">
          แพลตฟอร์มร้านผ้าคลุม
          {{-- {{ config('app.name', 'แพลตฟอร์มร้านผ้าคลุม') }} --}}
        </span>
      </a>

      <nav class="hidden md:flex items-center gap-6 text-sm">
        <a href="#featured" class="hover:text-olive transition">สินค้าแนะนำ</a>
        {{-- <a href="#" class="hover:text-olive transition">มาใหม่</a>
        <a href="#" class="hover:text-olive transition">โปรโมชั่น</a> --}}
      </nav>

      <div class="flex items-center gap-2">
        @auth
          {{-- <a href="{{ route('dashboard') }}"
             class="rounded-xl border border-slate-200 px-3 py-1.5 hover:border-ink/30">Dashboard</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="rounded-xl bg-ink text-white px-3 py-1.5 hover:opacity-90">Logout</button>
          </form> --}}
        @else
          <a href="{{ route('login') }}" class="rounded-xl border border-slate-200 px-3 py-1.5 hover:border-ink/30">เข้าสู่ระบบ</a>
          <a href="{{ route('register') }}" class="rounded-xl bg-ink text-white px-3 py-1.5 hover:opacity-90">สมัครสมาชิก</a>
        @endauth
      </div>
    </div>
  </header>

  {{-- ====== Hero (โทนอบอุ่น ภาพเด่น เว้นพื้นที่หายใจ) ====== --}}
  <section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20 grid lg:grid-cols-2 gap-10 items-center">
      <div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-semibold leading-tight">
          เรียบง่าย มีระดับ<br>ผ้าคลุมสำหรับทุกวัน
        </h1>
        <p class="mt-4 text-slate-600 text-base sm:text-lg">
          โทนสีกลางแมทง่าย เนื้อผ้านุ่ม ใส่สบาย เหมาะกับทุกโอกาส
        </p>
        <div class="mt-6 flex flex-wrap gap-3">
          <a href="#featured"
             class="rounded-xl2 bg-ink text-white px-5 h-11 inline-flex items-center justify-center hover:opacity-90 shadow">
            เลือกดูสินค้า
          </a>
          <a href="{{ route('login') }}"
             class="rounded-xl2 border border-slate-200 px-5 h-11 inline-flex items-center justify-center hover:border-ink/30">
            เริ่มต้นใช้งาน
          </a>
        </div>
      </div>

      <div class="relative">
        <div class="aspect-[16/10] rounded-xl2 overflow-hidden shadow-soft bg-sand">
          {{-- เปลี่ยนรูป Hero ตามชอบ --}}
          <img
            alt="Minimal Hijab Hero"
            class="w-full h-full object-cover"
            src="https://scontent-bkk1-1.xx.fbcdn.net/v/t39.30808-6/558058041_710047355444726_1473482335204980937_n.jpg?stp=c0.104.1290.1290a_dst-jpg_s552x414_tt6&_nc_cat=111&ccb=1-7&_nc_sid=50ad20&_nc_ohc=WjkrDLdGaQwQ7kNvwGMkXHT&_nc_oc=Adnp3pgpiXSZXXwn-AtzSjBG2Xvv4BnbLmgMrqXDkhhgvKudKhKCr5dPsEyxJ3D77tE&_nc_zt=23&_nc_ht=scontent-bkk1-1.xx&_nc_gid=Onrmbv8OPbC0SBBIgqadEg&oh=00_Afe89Wdz5z6qTcvCFUoXGIJ1SH9zF_gvJyKEa3mJYngvsw&oe=68FA77C6">
        </div>
      </div>
    </div>
  </section>

  {{-- ====== Featured (การ์ดมินิมอล เน้นรูป/ราคา) ====== --}}
  <section id="featured" class="pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-6 flex items-end justify-between">
        <h2 class="text-xl sm:text-2xl font-semibold">สินค้าแนะนำ</h2>
        @guest
          <a href="{{ route('register') }}" class="text-olive hover:opacity-80 text-sm">สมัครสมาชิกเพื่อสั่งซื้อ</a>
        @endguest
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
        @foreach ($featuredProducts as $p)
          <div class="bg-white rounded-xl2 border border-slate-100 overflow-hidden shadow-soft hover:shadow transition group">
            <div class="aspect-square bg-sand overflow-hidden">
              <img src="{{ $p['image'] }}" alt="{{ $p['name'] }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition">
            </div>
            <div class="p-3">
              <div class="flex items-center justify-between gap-2">
                <div class="font-medium truncate">{{ $p['name'] }}</div>
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-olive text-white">ใหม่</span>
              </div>
              <div class="mt-2 font-semibold">฿{{ number_format((float) $p['price']) }}</div>
              <div class="mt-3 flex gap-2">
                @auth
                  <a href="{{ route('dashboard') }}"
                     class="inline-flex items-center rounded-xl border border-slate-200 px-3 h-9 hover:border-ink/30">
                    จัดการ
                  </a>
                @else
                  <a href="{{ route('login') }}"
                     class="inline-flex items-center rounded-xl bg-ink text-white px-3 h-9 hover:opacity-90">
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
  <footer class="border-t border-slate-100 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid md:grid-cols-3 gap-8 text-sm text-slate-600">
      <div>
        <div class="font-semibold mb-2 text-ink">เกี่ยวกับเรา</div>
        <p>ผ้าคลุมสไตล์มินิมอล เนื้อดี ใส่สบาย แมทช์ง่ายทุกวัน</p>
      </div>
      <div>
        {{-- <div class="font-semibold mb-2 text-ink">บริการลูกค้า</div> --}}
        <ul class="space-y-1">
          {{-- <li><a class="hover:text-olive" href="#">การจัดส่ง</a></li>
          <li><a class="hover:text-olive" href="#">การคืนสินค้า</a></li> --}}
        </ul>
      </div>
      <div>
        <div class="font-semibold mb-2 text-ink">ติดตามเรา</div>
        <div class="flex gap-3">
          <a class="hover:text-olive" href="https://www.facebook.com/profile.php?id=100093184035157&sk=photos&locale=th_TH">Facebook</a>
          {{-- <a class="hover:text-olive" href="#">Instagram</a> --}}
        </div>
      </div>
    </div>
    <div class="border-t border-slate-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-xs text-slate-500 flex items-center justify-between">
        <span>© {{ date('Y') }} {{ config('app.name', 'แพลตฟอร์มร้านผ้าคลุม') }}. All rights reserved.</span>
        <div class="flex items-center gap-4">
          <a href="#" class="hover:text-ink">Privacy</a>
          <a href="#" class="hover:text-ink">Terms</a>
        </div>
      </div>
    </div>
  </footer>
</body>
</html>
