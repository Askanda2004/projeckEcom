<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin • Edit User</title>

  {{-- Tailwind via CDN --}}
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
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/></svg>
        </div>
        <span class="font-bold">Admin Panel</span>
      </div>

      <nav class="flex items-center gap-3 text-sm">
        <a href="{{ route('admin.index') }}" class="text-slate-600 hover:text-slate-900">Manage Users</a>
        {{-- <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-slate-900">Dashboard</a> --}}
      </nav>
    </div>
  </header>

  <!-- Main -->
  <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold">Edit User</h1>
        <p class="text-slate-500 text-sm">Update account information and role.</p>
      </div>
      <a href="{{ route('admin.index') }}"
         class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-sm hover:bg-slate-50">
        ← Back
      </a>
    </div>

    @if (session('status'))
      <div class="mb-6 rounded-xl bg-green-50 text-green-800 px-4 py-3 shadow-soft">{{ session('status') }}</div>
    @endif
    @if (session('error'))
      <div class="mb-6 rounded-xl bg-red-50 text-red-700 px-4 py-3 shadow-soft">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-soft p-6 sm:p-8">
      <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
        @csrf
        @method('PATCH')

        <!-- Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-slate-700">Full Name</label>
          <input id="name" name="name" type="text"
                 value="{{ old('name', $user->name) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2
                        focus:border-primary focus:ring-primary/20"
                 required />
          @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
          <input id="email" name="email" type="email"
                 value="{{ old('email', $user->email) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2
                        focus:border-primary focus:ring-primary/20"
                 required />
          @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Role -->
        <div>
          <label for="role" class="block text-sm font-medium text-slate-700">Role</label>
          <select id="role" name="role"
                  class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2
                         focus:border-primary focus:ring-primary/20"
                  required>
            @foreach (['admin','seller','customer'] as $role)
              <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>
                {{ ucfirst($role) }}
              </option>
            @endforeach
          </select>
          @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3">
          <button class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-white
                         hover:bg-blue-700">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M5 13l4 4L19 7" />
            </svg>
            Save changes
          </button>
          <a href="{{ route('admin.index') }}"
             class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 hover:bg-slate-50">
            Cancel
          </a>
        </div>
      </form>
    </div>

    {{-- Optional danger zone: delete user --}}
    <div class="mt-8 bg-white rounded-2xl shadow-soft p-6 sm:p-8">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="font-semibold">Danger Zone</h2>
          <p class="text-sm text-slate-500">Permanently delete this account.</p>
        </div>
        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
              onsubmit="return confirm('Delete this user permanently?')">
          @csrf
          @method('DELETE')
          <button class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-rose-700 hover:bg-rose-100">
            Delete User
          </button>
        </form>
      </div>
    </div>
  </main>

</body>
</html>
