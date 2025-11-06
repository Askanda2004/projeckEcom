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
  @php
    $featuredProducts = $featuredProducts
      ?? [
        ['name' => 'สินค้าแนะนำ',    'price' => 890, 'image' => 'https://scontent.fbkk22-4.fna.fbcdn.net/v/t39.30808-6/565177023_721268264322635_2855992648106859369_n.jpg?stp=dst-jpg_p960x960_tt6&_nc_cat=109&ccb=1-7&_nc_sid=f727a1&_nc_ohc=ZvkcRD0Of-oQ7kNvwGoyaoF&_nc_oc=AdkBSgL5vSAdHbLoduQAPaSYvxeRxNB3lfGwzoUFSq9_hSiVY7LY6Ae8KW-k-6XPNoq3WtKRQIIh4lsDooMj3qsB&_nc_zt=23&_nc_ht=scontent.fbkk22-4.fna&_nc_gid=H9hv6rl865rWQqRE6YZ0aQ&oh=00_AfjqVNh4m_4bpfIT1-6B6qJGHeu9bmLciICWjFuSr2ATSw&oe=69109E94'],
        ['name' => 'สินค้าแนะนำ',    'price' => 159, 'image' => 'https://scontent.fbkk22-2.fna.fbcdn.net/v/t39.30808-6/565703371_720768821039246_6050754381254674923_n.jpg?stp=dst-jpg_p960x960_tt6&_nc_cat=105&ccb=1-7&_nc_sid=127cfc&_nc_ohc=LcYTOhyA42sQ7kNvwG1hlGd&_nc_oc=AdnQNoNPJQN7w_7LLzDT7eLxx_WSckTMGQpLklGGdxNAdTtUfqyM8PH6iPUHgjpcJFRhUa8ihPlg9NjK3ap4Zpw1&_nc_zt=23&_nc_ht=scontent.fbkk22-2.fna&_nc_gid=mlBAOc_qVsYra6ixzfLB5w&oh=00_Afh7MbVCmNFbkVdcDmT9wOZBil-h4OtW1oQg_MmdeV1egg&oe=691071A0'],
        ['name' => 'สินค้าแนะนำ',    'price' => 349, 'image' => 'https://scontent.fbkk22-1.fna.fbcdn.net/v/t39.30808-6/557417956_706398075809654_6359685745200862717_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=833d8c&_nc_ohc=dOIrvinw6F4Q7kNvwFjwEdK&_nc_oc=Adk5tzBqSJhN0Uk3oGhnXw_yD4Od_SD09_33geEhLl-48GfedshIAYmF9a2Vfac56sLsyzFI5H7EaJUrWUmnALLo&_nc_zt=23&_nc_ht=scontent.fbkk22-1.fna&_nc_gid=hU7K3RABEv1mbNZk_1F-vw&oh=00_AfiWYdNBFFxAO2d9c4QsWubrScSOUOLL4KjG6k4gvDPxIA&oe=691097B4'],
        ['name' => 'สินค้าแนะนำ',    'price' => 459, 'image' => 'https://scontent.fbkk22-4.fna.fbcdn.net/v/t39.30808-6/555072281_701499732966155_2182234913646490760_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=127cfc&_nc_ohc=893iy6SsDWIQ7kNvwEjwIrM&_nc_oc=AdmbyISQRk3XqjkiX3EjOK0gjj7Xja3FuA9w-HL1OWXMcwie5FFwcINEZIapzWPwUZOVA-8oSH9si_d0BDJA6zKv&_nc_zt=23&_nc_ht=scontent.fbkk22-4.fna&_nc_gid=ZYIRceT5lfnQny3Gaa5QCg&oh=00_AfimyGW3Ol0EJkfnQ4lwbprCdo4WZ8IE_rofwTv41YbiXw&oe=69108BC0'],

        ['name' => 'สินค้าแนะนำ',    'price' => 199, 'image' => 'https://scontent.fbkk22-8.fna.fbcdn.net/v/t39.30808-6/554086329_701499836299478_4898083085511445486_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=127cfc&_nc_ohc=vfVCbJkGLdAQ7kNvwF6M_er&_nc_oc=Adl2pzspFWDAmTvnaWvFuYylDfjRXWrc49XaJjTc8KqGutKLfLDB11DdjBxBx0UdUdWF2efTL8EvahlJU6iZ2Nmf&_nc_zt=23&_nc_ht=scontent.fbkk22-8.fna&_nc_gid=5zr7vy4WI92DxXKJof2uYQ&oh=00_Afj16imBIHmcAW-ypdO-ypcjYg4em5GKd3D_WShSaCuwZA&oe=6910A09F'],
        ['name' => 'สินค้าแนะนำ',    'price' => 239, 'image' => 'https://scontent.fbkk22-7.fna.fbcdn.net/v/t39.30808-6/540699914_681892071593588_2577620848296378713_n.jpg?stp=dst-jpg_s1080x2048_tt6&_nc_cat=107&ccb=1-7&_nc_sid=127cfc&_nc_ohc=mShQLSsKdeoQ7kNvwHUTKYN&_nc_oc=AdmR5bpB9SPuqEa2qydgeooZdtzHoRitAY8um-Wdc9LAO9zrm1QydWoErQBTqVHDNQCmXsZKlgb-R05XGo9SI1li&_nc_zt=23&_nc_ht=scontent.fbkk22-7.fna&_nc_gid=hjRat7yIk6j1Wyopo9S_Zw&oh=00_Afhcf29joGRAtaKjvwlb0j97XwIfrDLnoHEuQ2_7q_tUkg&oe=69109C5D'],
        ['name' => 'สินค้าแนะนำ',    'price' => 499, 'image' => 'https://scontent.fbkk22-7.fna.fbcdn.net/v/t39.30808-6/533525948_669617189487743_4878692035594660326_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=833d8c&_nc_ohc=1duWbNprhf0Q7kNvwH4chse&_nc_oc=AdntANx7vZ0V46f-PL5Jzp3k0Fb9XexfiflKJ4FfQgtKkCY51WpflrQWeiJ2MQA_ADkIQCAsWdkQSSwx5EFvkwoH&_nc_zt=23&_nc_ht=scontent.fbkk22-7.fna&_nc_gid=Fjy_or_kYi23PKR9E7zCpQ&oh=00_Afid5NRuRceA5JQKlaHnuOscH5lWe8RgurpCsLhufJTnFw&oe=6910A5FA'],
        ['name' => 'สินค้าแนะนำ',    'price' => 459, 'image' => 'https://scontent.fbkk22-8.fna.fbcdn.net/v/t39.30808-6/532934551_668097252973070_2372766549662057794_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=833d8c&_nc_ohc=cZD1dYcMV7MQ7kNvwGwk6aD&_nc_oc=AdnoIaqG8vjvPWHul9pxPWnlZJ3NIOcOs3sVPXaFmfiktyzRZmQZTzXO6xNqtoOSDKv9ynntxjzr1eTcgexGFS-9&_nc_zt=23&_nc_ht=scontent.fbkk22-8.fna&_nc_gid=lisa9iL0NSvCFKC2P9m0ow&oh=00_Afil1z8riK0P8pycbgOmIUY8TN5MlcHTGskQzZYiSx00BQ&oe=691080EB'],
      ];
  @endphp

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
        </span>
      </a>

      <nav class="hidden md:flex items-center gap-6 text-sm">
        <a href="#featured" class="hover:text-olive transition">สินค้าแนะนำ</a>
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
            src="https://scontent.fbkk22-6.fna.fbcdn.net/v/t39.30808-6/572057238_728551330260995_1428492837862035333_n.jpg?stp=dst-jpg_p960x960_tt6&_nc_cat=104&ccb=1-7&_nc_sid=833d8c&_nc_ohc=kMIXjSdgdugQ7kNvwF_nk7t&_nc_oc=Adn3cvEpq_qQ53NY_ocQRqWmkP3450s8vu7uUfVLaf6DlM_FY_JkycF4rG9FHgd6v1GBJdcEACs0g4W-WXTCPbOe&_nc_zt=23&_nc_ht=scontent.fbkk22-6.fna&_nc_gid=qXXw-fMONSj4UmURMjHYSg&oh=00_AfjBTx36DqzXMTklJvrDE_gIfXv2ckoBAO6j1RPEF2ThKQ&oe=691075D0">
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
