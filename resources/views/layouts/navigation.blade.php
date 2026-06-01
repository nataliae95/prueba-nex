<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <div class="flex items-center space-x-8">
                <div class="flex-shrink-0 flex items-center font-bold text-xl text-indigo-600 tracking-wider">
                    💼 MINI-CRM
                </div>
                
                <div class="flex space-x-4">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                        Dashboard
                    </a>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-600 bg-gray-100 px-3 py-1.5 rounded-full">
                    👤 {{ Auth::user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-gray-500 hover:text-red-600 transition duration-150 ease-in-out flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Salir
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>