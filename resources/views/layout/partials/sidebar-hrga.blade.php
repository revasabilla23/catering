@props(['collapsed'])

<li class="{{ request()->routeIs('hrga.dashboard') ? 'active' : '' }}">
    <a href="{{ route('hrga.dashboard') }}"
       class="flex items-center px-4 py-2 rounded-md hover:bg-blue-100 transition-colors duration-300 ease-in-out"
       :class="{ 'justify-center': {{ $collapsed }} }">
        <!-- Icon: Home untuk Dashboard -->
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4H9v4a2 2 0 01-2 2H3a2 2 0 01-2-2V9z"/>
        </svg>
        <span class="ml-3 whitespace-nowrap sidebar-text" :class="{ 'hidden': {{ $collapsed }} }">Dashboard</span>
    </a>
</li>

<li class="{{ request()->routeIs('hrga.pesanan.*') ? 'active' : '' }}">
    <a href="{{ route('hrga.pesanan.index') }}"
       class="flex items-center px-4 py-2 rounded-md hover:bg-blue-100 transition-colors duration-300 ease-in-out"
       :class="{ 'justify-center': {{ $collapsed }} }">
        <!-- Icon: Clipboard/List untuk Pesanan -->
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2"/>
            <path d="M15 3h-6a2 2 0 00-2 2v0a2 2 0 002 2h6a2 2 0 002-2v0a2 2 0 00-2-2z"/>
            <line x1="9" y1="12" x2="15" y2="12"/>
            <line x1="9" y1="16" x2="15" y2="16"/>
        </svg>
        <span class="ml-3 whitespace-nowrap sidebar-text" :class="{ 'hidden': {{ $collapsed }} }">Pesanan</span>
    </a>
</li>

<li class="{{ request()->routeIs('hrga.karyawan.*') ? 'active' : '' }}">
    <a href="{{ route('hrga.karyawan.index') }}"
       class="flex items-center px-4 py-2 rounded-md hover:bg-blue-100 transition-colors duration-300 ease-in-out"
       :class="{ 'justify-center': {{ $collapsed }} }">
        <!-- Icon: User untuk Karyawan -->
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
             stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="7" r="4"/>
            <path d="M5.5 21a6.5 6.5 0 0113 0"/>
        </svg>
        <span class="ml-3 whitespace-nowrap sidebar-text" :class="{ 'hidden': {{ $collapsed }} }">Karyawan</span>
    </a>
</li>

<li class="{{ request()->routeIs('hrga.konsumsi') ? 'active' : '' }}">
    <a href="{{ route('hrga.konsumsi') }}"
       class="flex items-center px-4 py-2 rounded-md hover:bg-blue-100 transition-colors duration-300 ease-in-out"
       :class="{ 'justify-center': {{ $collapsed }} }">
        <!-- Icon: Coffee Cup untuk Konsumsi -->
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M8 21h8a4 4 0 004-4v-1H4v1a4 4 0 004 4z"/>
            <path d="M16 8V6a4 4 0 00-8 0v2"/>
            <path d="M3 10h18"/>
        </svg>
        <span class="ml-3 whitespace-nowrap sidebar-text" :class="{ 'hidden': {{ $collapsed }} }">Konsumsi</span>
    </a>
</li>

<li class="{{ request()->routeIs('hrga.report') ? 'active' : '' }}">
    <a href="{{ route('hrga.report') }}"
       class="flex items-center px-4 py-2 rounded-md hover:bg-blue-100 transition-colors duration-300 ease-in-out"
       :class="{ 'justify-center': {{ $collapsed }} }">
        <!-- Icon: File Text untuk Report -->
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <line x1="10" y1="9" x2="8" y2="9"/>
        </svg>
        <span class="ml-3 whitespace-nowrap sidebar-text" :class="{ 'hidden': {{ $collapsed }} }">Report</span>
    </a>
</li>

<li>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit"
                class="flex items-center w-full px-4 py-2 rounded-md text-red-600 hover:bg-red-100 transition-colors duration-300 ease-in-out"
                :class="{ 'justify-center': {{ $collapsed }} }">
            <!-- Icon: Log Out -->
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />
            </svg>
            <span class="ml-3 whitespace-nowrap sidebar-text" :class="{ 'hidden': {{ $collapsed }} }">Logout</span>
        </button>
    </form>
</li>