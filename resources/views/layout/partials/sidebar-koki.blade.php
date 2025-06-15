@props(['collapsed'])

<li class="{{ request()->routeIs('koki.dashboard') ? 'active' : '' }}">
    <a href="{{ route('koki.dashboard') }}"
       class="flex items-center px-4 py-2 rounded-md hover:bg-blue-100 transition-colors duration-300 ease-in-out"
       :class="{ 'justify-center': {{ $collapsed }} }">
        <!-- Icon: Home untuk Dashboard -->
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m4 4h-1a2 2 0 00-2 2v5H5v-5a2 2 0 00-2-2H2"/>
        </svg>
        <span class="ml-3 whitespace-nowrap sidebar-text" :class="{ 'hidden': {{ $collapsed }} }">Dashboard</span>
    </a>
</li>

<li class="{{ request()->routeIs('koki.pesanan') ? 'active' : '' }}">
    <a href="{{ route('koki.pesanan') }}"
       class="flex items-center px-4 py-2 rounded-md hover:bg-blue-100 transition-colors duration-300 ease-in-out"
       :class="{ 'justify-center': {{ $collapsed }} }">
        <!-- Icon: Arrow Switch untuk Pesanan -->
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 17v-2a4 4 0 014-4h4m0 0l-4-4m4 4l-4 4"/>
        </svg>
        <span class="ml-3 whitespace-nowrap sidebar-text" :class="{ 'hidden': {{ $collapsed }} }">Pesanan</span>
    </a>
</li>

<li>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit"
                class="flex items-center w-full px-4 py-2 rounded-md text-red-600 hover:bg-red-100 transition-colors duration-300 ease-in-out"
                :class="{ 'justify-center': {{ $collapsed }} }">
            <!-- Icon: Log Out -->
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/>
            </svg>
            <span class="ml-3 whitespace-nowrap sidebar-text" :class="{ 'hidden': {{ $collapsed }} }">Logout</span>
        </button>
    </form>
</li>