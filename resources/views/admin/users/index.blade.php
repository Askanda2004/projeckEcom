<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <title>Admin • Manage User Accounts</title>

    {{-- Tailwind via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- กำหนด theme เล็กน้อย --}}
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: { DEFAULT: '#2563eb' }, // blue-600
            },
            boxShadow: {
              soft: '0 6px 30px rgba(0,0,0,0.08)',
            }
          }
        }
      }
    </script>
</head>
<body class="bg-slate-50 text-slate-800">

    <!-- HEADER -->
    <header class="sticky top-0 bg-white/90 backdrop-blur shadow-soft z-30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
            <!-- simple logo -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/></svg>
          </div>
          <span class="font-bold">Admin Panel</span>
        </div>

        <div class="flex items-center gap-2">
          <form method="POST" action="{{ route('logout') }}" class="ml-2">
            @csrf
            <button class="px-3 py-1.5 text-sm rounded-lg bg-slate-900 text-white hover:bg-slate-800">Logout</button>
          </form>
        </div>
      </div>
    </header>

    @php
      // ใช้กำหนด active ของเมนู
      $activeAll      = !isset($filterRole) || is_null($filterRole);
      $activeCustomer = (isset($filterRole) && $filterRole === 'customer');
      $activeSeller   = (isset($filterRole) && $filterRole === 'seller');
      $activeAdmin    = (isset($filterRole) && $filterRole === 'admin');

      $linkBase = 'flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100';
      $activeCls = 'bg-slate-100 text-slate-900 font-medium';
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-12 gap-6">
      <!-- SIDEBAR -->
      <aside class="col-span-12 md:col-span-3 lg:col-span-3">
        <div class="bg-white rounded-2xl shadow-soft p-4 md:p-5">
          <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Navigation</h3>
          <nav class="space-y-1">
            <a href="{{ route('admin.users.index') }}"
               class="{{ $linkBase }} {{ $activeAll ? $activeCls : '' }}">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
              </svg>
              Manage Users (All)
            </a>

            <a href="{{ route('admin.users.byRole','customer') }}"
               class="{{ $linkBase }} {{ $activeCustomer ? $activeCls : '' }}">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.7 0 4.88-2.18 4.88-4.88S14.7 2.24 12 2.24 7.12 4.42 7.12 7.12 9.3 12 12 12zm0 2.4c-3.25 0-9.6 1.63-9.6 4.88V22h19.2v-2.72c0-3.25-6.35-4.88-9.6-4.88z"/>
              </svg>
              Users Customer
            </a>

            <a href="{{ route('admin.users.byRole','seller') }}"
               class="{{ $linkBase }} {{ $activeSeller ? $activeCls : '' }}">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 6l3 7h11l3-7H3zm3 9c-1.66 0-3 1.34-3 3v1h18v-1c0-1.66-1.34-3-3-3H6z"/>
              </svg>
              Users Seller
            </a>

            <a href="{{ route('admin.users.byRole','admin') }}"
               class="{{ $linkBase }} {{ $activeAdmin ? $activeCls : '' }}">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 11.99V5.5l6 2.67V11c0 4.02-2.68 7.96-6 9.27V12.99z"/>
              </svg>
              Users Admin
            </a>
          </nav>
        </div>
      </aside>

      <!-- MAIN -->
      <main class="col-span-12 md:col-span-9 lg:col-span-9 space-y-6">
        <!-- Title + actions -->
        <div class="flex items-center justify-between">
          <h1 class="text-2xl font-bold">
            {{ $activeAll ? 'Manage User Accounts' : 'Manage User Accounts • '.ucfirst($filterRole) }}
          </h1>

          <!-- Search -->
          <form method="GET"
                action="{{ $activeAll ? route('admin.users.index') : route('admin.users.byRole',$filterRole) }}"
                class="relative max-w-xl w-full">
            <div class="rounded-full bg-gradient-to-b from-slate-50 to-white p-1 shadow-[0_8px_30px_rgba(0,0,0,0.06)]">
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                  <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="11" cy="11" r="7" stroke-width="2"></circle>
                    <path d="M20 20l-3.5-3.5" stroke-width="2"></path>
                  </svg>
                </span>

                <input
                  id="q"
                  type="text"
                  name="q"
                  value="{{ request('q') }}"
                  placeholder="Search name or email..."
                  class="peer w-full pr-28 pl-12 py-3.5 rounded-full border border-slate-200
                         bg-white/90 placeholder-slate-400 text-slate-800
                         focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500
                         transition-all" />

                <button type="button" id="clearBtn"
                        class="hidden absolute right-28 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                        aria-label="Clear search">
                  <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18.3 5.7a1 1 0 0 0-1.4 0L12 10.6 7.1 5.7A1 1 0 1 0 5.7 7.1L10.6 12l-4.9 4.9a1 1 0 1 0 1.4 1.4L12 13.4l4.9 4.9a1 1 0 0 0 1.4-1.4L13.4 12l4.9-4.9a1 1 0 0 0 0-1.4z"/>
                  </svg>
                </button>

                <button class="absolute right-1 top-1/2 -translate-y-1/2 rounded-full px-5 py-2.5
                               bg-blue-600 text-white text-sm font-medium hover:bg-blue-700
                               active:scale-[.98] transition-all shadow-md">
                  Search
                </button>
              </div>
            </div>
          </form>

          <script>
            (function () {
              const input = document.getElementById('q');
              const clearBtn = document.getElementById('clearBtn');
              const toggleClear = () => clearBtn.classList.toggle('hidden', !input.value);
              toggleClear();
              input.addEventListener('input', toggleClear);
              clearBtn.addEventListener('click', () => { input.value=''; input.focus(); toggleClear(); });
            })();
          </script>
        </div>

        <!-- Flash messages -->
        @if (session('status'))
          <div class="rounded-xl bg-green-50 text-green-800 px-4 py-3 shadow-soft">{{ session('status') }}</div>
        @endif
        @if (session('error'))
          <div class="rounded-xl bg-red-50 text-red-700 px-4 py-3 shadow-soft">{{ session('error') }}</div>
        @endif

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-soft">
          <div class="p-4 sm:p-6 border-b border-slate-100">
            <div class="flex items-center justify-between">
              <div class="text-slate-500 text-sm">
                Total users: <strong>{{ $users->total() }}</strong>
                @if(!$activeAll)
                  <span class="ml-2 text-slate-400">(Role: {{ ucfirst($filterRole) }})</span>
                @endif
              </div>
              <div class="md:hidden">
                <form method="GET" action="{{ $activeAll ? route('admin.users.index') : route('admin.users.byRole',$filterRole) }}">
                  <input type="text" name="q" value="{{ request('q') }}" placeholder="Search…"
                         class="w-56 rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" />
                </form>
              </div>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead>
                <tr class="bg-slate-50 text-slate-600">
                  <th class="text-left font-semibold px-4 py-3 border-b">Name</th>
                  <th class="text-left font-semibold px-4 py-3 border-b">Email</th>
                  <th class="text-left font-semibold px-4 py-3 border-b">Role</th>
                  <th class="text-left font-semibold px-4 py-3 border-b w-28">Edit</th>
                  <th class="text-left font-semibold px-4 py-3 border-b w-28">Delete</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($users as $user)
                  <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 border-b">{{ $user->username ?? $user->name ?? '-' }}</td>
                    <td class="px-4 py-3 border-b">{{ $user->email }}</td>
                    <td class="px-4 py-3 border-b">
                      @php
                        $badge = [
                          'admin'    => 'bg-rose-100 text-rose-700',
                          'seller'   => 'bg-amber-100 text-amber-700',
                          'customer' => 'bg-emerald-100 text-emerald-700',
                        ][$user->role] ?? 'bg-slate-100 text-slate-700';
                      @endphp
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                        {{ ucfirst($user->role) }}
                      </span>
                    </td>
                    <td class="px-4 py-3 border-b">
                      <a href="{{ route('admin.users.edit', $user) }}"
                         class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                        Edit
                      </a>
                    </td>
                    <td class="px-4 py-3 border-b">
                      <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                            onsubmit="return confirm('Delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-rose-200 text-rose-700 hover:bg-rose-50">
                          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H4v2h15V4z"/></svg>
                          Delete
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-slate-500">No users found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="p-4 sm:p-6 border-t border-slate-100">
            <div class="flex items-center justify-between">
              <div class="text-xs text-slate-500">
                Showing
                <span class="font-medium">{{ $users->firstItem() ?? 0 }}</span>
                to
                <span class="font-medium">{{ $users->lastItem() ?? 0 }}</span>
                of
                <span class="font-medium">{{ $users->total() }}</span>
                results
              </div>
              <div>{{ $users->links() }}</div>
            </div>
          </div>
        </div>
      </main>
    </div>

</body>
</html>
