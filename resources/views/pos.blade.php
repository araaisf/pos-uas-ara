<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS | {{ $setting->shop_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        /* FONT SETUP */
        body { font-family: 'Outfit', sans-serif; color: #4A5568; }
        .font-serif { font-family: 'Playfair Display', serif; }
        
        /* 1. ANIMASI BACKGROUND GERAK */
        .bg-animated {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab, #ffafbd, #ffc3a0);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            height: 100vh;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* 2. EFEK KACA */
        .glass-panel {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }

        /* 3. ANIMASI ELEMENT MUNCUL */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px) scale(0.9); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-enter {
            animation: fadeInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            opacity: 0;
        }
        
        /* 4. ANIMASI KATEGORI SLIDE MASUK (BARU) */
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); filter: blur(10px); }
            to { opacity: 1; transform: translateX(0); filter: blur(0); }
        }
        .category-pill {
            animation: slideInRight 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            opacity: 0; 
        }

        /* 5. HOVER EFFECT PRODUK */
        .product-card { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .product-card:hover { 
            transform: translateY(-8px) scale(1.02); 
            box-shadow: 0 20px 30px -10px rgba(0,0,0,0.15);
            border-color: #d6bcfa;
        }

        /* UTILS */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scroll::-webkit-scrollbar { display: none; } /* Untuk kategori */
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #d6bcfa; border-radius: 10px; }
        .sidebar-transition { transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="h-screen flex overflow-hidden bg-animated" x-data="posSystem()">

    @if(session('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition.duration.500ms
         class="fixed top-8 left-1/2 transform -translate-x-1/2 glass-panel px-8 py-4 rounded-full z-[60] flex items-center gap-3 border border-white/50 shadow-2xl animate-enter">
         <span class="text-2xl animate-bounce">✨</span>
         <span class="font-bold text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-600 tracking-wide">{{ session('success') }}</span>
    </div>
    @endif

    <nav :class="sidebarOpen ? 'w-72' : 'w-24 items-center'" 
         class="glass-panel h-full z-30 flex flex-col py-8 border-r border-white/40 sidebar-transition shrink-0 relative transition-all duration-500">
        
        <button @click="sidebarOpen = !sidebarOpen" class="absolute top-6 -right-4 bg-white shadow-lg p-2 rounded-full text-purple-600 hover:scale-110 hover:rotate-180 transition-all duration-500 z-50 border border-purple-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>

        <div class="mb-12 flex flex-col items-center justify-center h-20 text-center transition-all duration-500">
            <span x-show="!sidebarOpen" class="text-5xl font-serif font-bold text-transparent bg-clip-text bg-gradient-to-br from-pink-500 to-purple-600 leading-none drop-shadow-sm transition-all duration-500">I</span>
            <div x-show="sidebarOpen" class="flex flex-col items-center transition-all duration-500 animate-enter">
                <span class="text-5xl font-serif font-bold text-transparent bg-clip-text bg-gradient-to-br from-pink-500 to-purple-600 leading-none drop-shadow-sm">ISNA</span>
                <span class="text-[10px] text-purple-400 font-bold tracking-[0.4em] uppercase mt-3 px-4 border-t border-purple-200 pt-2">{{ $setting->shop_name }}</span>
            </div>
        </div>

        <div class="flex flex-col gap-3 w-full px-4 h-full">
            <a href="{{ route('pos.index') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 group overflow-hidden relative {{ Route::is('pos.index') ? 'bg-white shadow-lg text-purple-600' : 'hover:bg-white/50 text-gray-500 hover:text-purple-600' }}" :class="!sidebarOpen ? 'justify-center' : ''">
                <div class="absolute inset-y-0 left-0 w-1 bg-purple-500 rounded-r-full transition-all duration-300" :class="Route::is('pos.index') ? 'h-full' : 'h-0 group-hover:h-2/3 top-1/2 -translate-y-1/2'"></div>
                <svg class="w-6 h-6 shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold whitespace-nowrap transition-all duration-300">POS System</span>
            </a>

            <a href="{{ route('pos.report') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 group overflow-hidden relative hover:bg-white/50 text-gray-500 hover:text-purple-600" :class="!sidebarOpen ? 'justify-center' : ''">
                <div class="absolute inset-y-0 left-0 w-1 bg-purple-500 rounded-r-full h-0 group-hover:h-2/3 top-1/2 -translate-y-1/2 transition-all duration-300"></div>
                <svg class="w-6 h-6 shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold whitespace-nowrap transition-all duration-300">Reports</span>
            </a>

            <a href="{{ route('supplier.index') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 group overflow-hidden relative hover:bg-white/50 text-gray-500 hover:text-purple-600" :class="!sidebarOpen ? 'justify-center' : ''">
                <div class="absolute inset-y-0 left-0 w-1 bg-purple-500 rounded-r-full h-0 group-hover:h-2/3 top-1/2 -translate-y-1/2 transition-all duration-300"></div>
                <svg class="w-6 h-6 shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold whitespace-nowrap transition-all duration-300">Suppliers</span>
            </a>

            <button @click="openSettings = true" class="flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 group overflow-hidden relative hover:bg-white/50 text-gray-500 hover:text-purple-600" :class="!sidebarOpen ? 'justify-center' : ''">
                <div class="absolute inset-y-0 left-0 w-1 bg-purple-500 rounded-r-full h-0 group-hover:h-2/3 top-1/2 -translate-y-1/2 transition-all duration-300"></div>
                <svg class="w-6 h-6 shrink-0 transition-transform group-hover:rotate-90 duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold whitespace-nowrap transition-all duration-300">Settings</span>
            </button>

            <form action="{{ route('logout') }}" method="POST" class="mt-auto w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 group overflow-hidden relative hover:bg-red-50 text-gray-500 hover:text-red-500" :class="!sidebarOpen ? 'justify-center' : ''">
                    <svg class="w-6 h-6 shrink-0 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span x-show="sidebarOpen" class="text-sm font-bold whitespace-nowrap transition-all duration-300">Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <main class="flex-1 p-6 overflow-hidden flex flex-col relative z-10">
        <header class="h-24 flex justify-between items-center glass-panel rounded-[2.5rem] px-10 mb-6 shrink-0 animate-enter">
            <div>
                <h1 class="text-3xl font-serif text-gray-800">
                    Hello, <span class="bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent font-bold">{{ auth()->user()->name }}</span>
                </h1>
                <p class="text-[11px] text-gray-500 uppercase font-bold tracking-[0.2em] mt-1">{{ now()->format('l, d F Y') }}</p>
            </div>
            
            <div class="flex items-center gap-6">
                <form action="{{ route('pos.index') }}" method="GET" class="relative group">
                    <input type="text" name="search" placeholder="Search product..." value="{{ request('search') }}" 
                           class="bg-white/60 border-none rounded-full px-6 py-3 w-64 focus:w-80 transition-all duration-300 focus:ring-2 focus:ring-purple-300 outline-none text-sm shadow-inner">
                    <button class="absolute right-4 top-3 text-gray-400 group-focus-within:text-purple-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>
                <div class="h-12 w-12 rounded-full bg-gradient-to-tr from-pink-300 to-purple-400 p-[3px] shadow-lg shadow-purple-300/50 animate-pulse">
                    <div class="h-full w-full rounded-full bg-white flex items-center justify-center font-bold text-purple-600 text-xl overflow-hidden">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 flex gap-6 overflow-hidden">
            <div class="flex-1 flex flex-col glass-panel rounded-[3rem] p-8 overflow-hidden animate-enter delay-1">
                
                <div class="relative w-full mb-8 group">
                    <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-white/40 to-transparent z-10 pointer-events-none rounded-l-2xl"></div>
                    <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white/40 to-transparent z-10 pointer-events-none rounded-r-2xl"></div>

                    <div class="flex gap-3 overflow-x-auto hide-scroll pb-4 px-2 snap-x">
                        
                        <a href="{{ route('pos.index') }}" 
                           class="category-pill shrink-0 px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-widest border transition-all duration-300 relative overflow-hidden group snap-start
                           {{ !request('category') 
                               ? 'bg-gradient-to-r from-gray-800 to-gray-900 text-white shadow-lg shadow-gray-500/30 scale-105 border-transparent' 
                               : 'bg-white/40 border-white text-gray-500 hover:bg-white hover:text-purple-600 hover:shadow-lg hover:-translate-y-1' }}"
                           style="animation-delay: 0.1s">
                           <span class="relative z-10 flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                All
                           </span>
                        </a>

                        @foreach($categories as $index => $cat)
                        <a href="{{ route('pos.index', ['category' => $cat->id]) }}" 
                           class="category-pill shrink-0 px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-widest border transition-all duration-300 relative overflow-hidden group snap-start
                           {{ request('category') == $cat->id 
                               ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white shadow-lg shadow-purple-500/30 scale-105 border-transparent ring-2 ring-purple-200 ring-offset-2 ring-offset-transparent' 
                               : 'bg-white/40 border-white text-gray-500 hover:bg-white hover:text-purple-600 hover:shadow-lg hover:-translate-y-1' }}"
                           style="animation-delay: {{ ($index + 2) * 100 }}ms">
                           
                           @if(request('category') == $cat->id)
                           <div class="absolute inset-0 bg-white/20 skew-x-12 animate-[shimmer_2s_infinite] -translate-x-full"></div>
                           @endif

                           <span class="relative z-10 flex items-center gap-2">
                                @if(str_contains(strtolower($cat->name), 'skin')) 🧴
                                @elseif(str_contains(strtolower($cat->name), 'make')) 💄
                                @elseif(str_contains(strtolower($cat->name), 'hair')) 🎀
                                @elseif(str_contains(strtolower($cat->name), 'bag')) 👜
                                @elseif(str_contains(strtolower($cat->name), 'shoe')) 👠
                                @elseif(str_contains(strtolower($cat->name), 'fragrance')) 🌸
                                @else ✨
                                @endif
                                {{ $cat->name }}
                           </span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 overflow-y-auto pr-2 flex-1 pb-20 no-scrollbar">
                    
                    <div @click="openProductModal = true; isEdit = false; productForm = {category_name: ''}" 
                         class="product-card bg-white/30 border-2 border-dashed border-purple-200 rounded-[2rem] p-4 flex flex-col items-center justify-center gap-3 cursor-pointer group min-h-[280px] hover:border-purple-500 hover:bg-purple-50/50 animate-enter delay-2">
                        <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center text-purple-300 group-hover:text-white group-hover:bg-gradient-to-r group-hover:from-pink-500 group-hover:to-purple-600 transition-all duration-300 shadow-lg">
                            <svg class="w-10 h-10 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest group-hover:text-purple-600 transition-colors">Add Product</span>
                    </div>

                    @foreach($products as $index => $product)
                    <div class="product-card bg-white rounded-[2rem] p-4 relative group shadow-lg shadow-gray-100/50 border border-transparent hover:border-purple-100 flex flex-col h-full animate-enter" style="animation-delay: {{ ($index * 0.05) + 0.2 }}s">
                        
                        <div class="absolute top-4 right-4 z-20 translate-x-10 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300 flex flex-col gap-2">
                            <button @click.stop="openEditModal({{ $product }})" class="bg-white w-9 h-9 flex items-center justify-center rounded-full text-blue-500 shadow-lg hover:scale-110 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                            <form action="{{ route('product.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete item?');">
                                @csrf @method('DELETE')
                                <button type="submit" @click.stop class="bg-white w-9 h-9 flex items-center justify-center rounded-full text-red-500 shadow-lg hover:scale-110 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </form>
                        </div>

                        <div class="aspect-square rounded-[1.5rem] overflow-hidden mb-4 relative bg-gray-50 shadow-inner group-hover:shadow-none transition-all cursor-pointer" @click="addToCart({{ $product }})">
                            @if($product->image)
                            <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 font-serif italic">No IMG</div>
                            @endif
                            <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur px-3 py-1.5 rounded-xl text-[10px] font-bold text-gray-600 shadow-sm">
                                {{ $product->stock }} left
                            </div>
                        </div>

                        <div class="mt-auto px-1 cursor-pointer" @click="addToCart({{ $product }})">
                            <h3 class="font-bold text-gray-800 text-sm leading-snug mb-2 line-clamp-2 h-10 group-hover:text-purple-600 transition-colors">{{ $product->name }}</h3>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-[9px] text-gray-400 uppercase tracking-widest mb-0.5">{{ $product->category->name ?? 'Other' }}</p>
                                    <p class="text-purple-600 font-bold text-lg">Rp {{ number_format($product->price/1000, 0) }}k</p>
                                </div>
                                <button class="bg-gray-900 text-white w-10 h-10 rounded-full flex items-center justify-center shadow-lg hover:bg-purple-600 hover:scale-110 active:scale-95 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="w-96 glass-panel rounded-[3rem] flex flex-col shrink-0 overflow-hidden shadow-2xl relative border-l border-white/60 animate-enter delay-3">
                <div class="p-8 border-b border-white/50 bg-white/30 backdrop-blur-md z-10">
                    <h2 class="font-serif text-3xl text-gray-800 italic">My Bag</h2>
                    <p class="text-[10px] text-purple-600 uppercase font-bold tracking-[0.2em] mt-1 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        Order #{{ rand(1000,9999) }}
                    </p>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-4 no-scrollbar" id="cart-items">
                    <template x-if="cart.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 opacity-60">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <p class="font-serif italic text-lg">Your bag is empty</p>
                            <p class="text-xs mt-2">Start adding some items!</p>
                        </div>
                    </template>
                    
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="bg-white/70 p-4 rounded-[1.5rem] flex items-center gap-4 shadow-sm border border-white hover:shadow-md transition-shadow animate-enter">
                            <div class="w-16 h-16 rounded-2xl bg-gray-100 overflow-hidden shrink-0 shadow-inner">
                                <img :src="item.image" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-gray-800 text-sm truncate" x-text="item.name"></h4>
                                <p class="text-xs text-purple-600 font-bold mt-1">Rp <span x-text="formatRupiah(item.price)"></span></p>
                            </div>
                            <div class="flex flex-col items-center gap-1 bg-white rounded-xl p-1 shadow-sm border border-gray-100">
                                <button @click="updateQty(item.id, 1)" class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-green-500 hover:bg-green-50 rounded-lg transition text-xs">+</button>
                                <span class="text-xs font-bold w-4 text-center py-1" x-text="item.qty"></span>
                                <button @click="updateQty(item.id, -1)" class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition text-xs">-</button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="p-8 bg-white/60 backdrop-blur-xl border-t border-white/50 z-20">
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Subtotal</span>
                            <span class="font-bold">Rp <span x-text="formatRupiah(subtotal())"></span></span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Tax (0%)</span>
                            <span class="font-bold">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-2xl font-serif font-bold text-gray-800 pt-4 border-t border-gray-300/50">
                            <span>Total</span>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-600">Rp <span x-text="formatRupiah(subtotal())"></span></span>
                        </div>
                    </div>
                    <button @click="if(cart.length > 0) openCheckout = true" 
                            :disabled="cart.length === 0"
                            class="w-full bg-gray-900 text-white py-5 rounded-2xl font-bold uppercase tracking-[0.2em] shadow-xl hover:shadow-2xl hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-3 overflow-hidden relative group">
                        <span class="relative z-10 group-hover:text-purple-200 transition-colors">Checkout Now</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-pink-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <div x-show="openProductModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/30 backdrop-blur-md" style="display: none;">
        <div class="glass-panel w-full max-w-lg p-10 rounded-[3rem] shadow-2xl relative bg-white/90 animate-enter" @click.away="openProductModal = false">
            <h2 class="font-serif text-4xl text-gray-800 mb-8 text-center italic" x-text="isEdit ? 'Edit Asset' : 'New Asset'"></h2>
            <form :action="isEdit ? '/product/' + productForm.id : '{{ route('product.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                <div class="grid grid-cols-2 gap-5">
                    <input type="text" name="name" x-model="productForm.name" placeholder="Item Name" class="col-span-2 w-full bg-white border-none rounded-2xl px-6 py-4 focus:ring-2 focus:ring-purple-300 outline-none shadow-sm" required>
                    <input type="text" name="category_name" x-model="productForm.category_name" placeholder="Category" class="w-full bg-white border-none rounded-2xl px-6 py-4 focus:ring-2 focus:ring-purple-300 outline-none shadow-sm" required list="catList">
                    <datalist id="catList">@foreach($categories as $c) <option value="{{ $c->name }}"> @endforeach</datalist>
                    <input type="number" name="price" x-model="productForm.price" placeholder="Price" class="w-full bg-white border-none rounded-2xl px-6 py-4 focus:ring-2 focus:ring-purple-300 outline-none shadow-sm" required>
                    <input type="number" name="stock" x-model="productForm.stock" placeholder="Qty" class="w-full bg-white border-none rounded-2xl px-6 py-4 focus:ring-2 focus:ring-purple-300 outline-none shadow-sm" required>
                    <div class="col-span-2 relative p-1 bg-white rounded-2xl shadow-sm">
                        <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition">
                    </div>
                </div>
                <button class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white py-4 rounded-2xl font-bold uppercase tracking-widest text-xs shadow-lg hover:shadow-purple-300/50 hover:scale-[1.02] transition">Save Changes</button>
            </form>
            <button @click="openProductModal = false" class="absolute top-6 right-8 text-2xl text-gray-400 hover:text-red-500 transition">×</button>
        </div>
    </div>

    <div x-show="openSettings" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/30 backdrop-blur-md" style="display: none;">
        <div class="glass-panel w-full max-w-md p-10 rounded-[3rem] shadow-2xl relative bg-white/90 h-[600px] overflow-y-auto no-scrollbar animate-enter" @click.away="openSettings = false">
            <h2 class="font-serif text-3xl text-gray-800 mb-2 text-center italic">Settings</h2>
            
            <div class="mb-10 border-b border-gray-100 pb-8">
                <p class="text-center text-[10px] text-purple-400 uppercase tracking-[0.3em] mb-6 font-bold">Store Identity</p>
                <form action="{{ route('settings.update') }}" method="POST" class="space-y-4">
                    @csrf
                    <div><label class="text-[10px] text-gray-400 font-bold uppercase ml-3 mb-1 block">Shop Name</label><input type="text" name="shop_name" value="{{ $setting->shop_name }}" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 focus:ring-2 focus:ring-purple-300 outline-none text-sm" required></div>
                    <div><label class="text-[10px] text-gray-400 font-bold uppercase ml-3 mb-1 block">Slogan</label><input type="text" name="shop_slogan" value="{{ $setting->shop_slogan }}" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 focus:ring-2 focus:ring-purple-300 outline-none text-sm"></div>
                    <div><label class="text-[10px] text-gray-400 font-bold uppercase ml-3 mb-1 block">Address</label><input type="text" name="shop_address" value="{{ $setting->shop_address }}" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 focus:ring-2 focus:ring-purple-300 outline-none text-sm" required></div>
                    <button class="w-full bg-white border-2 border-purple-100 text-purple-600 hover:bg-purple-50 transition py-3 rounded-xl font-bold tracking-widest uppercase text-xs mt-2 shadow-sm">Update Profile</button>
                </form>
            </div>

            <div>
                <p class="text-center text-[10px] text-red-400 uppercase tracking-[0.3em] mb-6 font-bold">Security</p>
                <form action="{{ route('settings.password') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="password" name="current_password" placeholder="Current Password" class="w-full bg-red-50/50 border-none rounded-2xl px-5 py-3 focus:ring-2 focus:ring-red-200 outline-none text-sm" required>
                    <div class="flex gap-3">
                        <input type="password" name="new_password" placeholder="New Password" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 focus:ring-2 focus:ring-purple-300 outline-none text-sm" required>
                        <input type="password" name="new_password_confirmation" placeholder="Confirm" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 focus:ring-2 focus:ring-purple-300 outline-none text-sm" required>
                    </div>
                    <button type="submit" class="w-full bg-gray-800 text-white hover:bg-red-500 transition py-3 rounded-xl font-bold tracking-widest uppercase text-xs mt-2 shadow-lg">Change Password</button>
                </form>
            </div>
            <button @click="openSettings = false" class="absolute top-6 right-8 text-2xl text-gray-400 hover:text-red-500 transition">×</button>
        </div>
    </div>

    <div x-show="openCheckout" class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/40 backdrop-blur-md" style="display: none;">
        <div class="glass-panel w-full max-w-sm p-10 rounded-[3rem] shadow-2xl relative bg-white animate-enter" @click.away="openCheckout = false">
            <h2 class="font-serif text-3xl text-gray-800 mb-8 text-center italic">Payment</h2>
            <form action="{{ route('pos.checkout') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="cart_json" :value="JSON.stringify(cart)">
                <input type="hidden" name="total_input" :value="subtotal()">
                
                <div class="bg-gray-50 p-6 rounded-[2rem] mb-4 text-center border border-gray-100">
                    <span class="text-xs text-gray-500 uppercase tracking-widest block mb-1">Total Bill</span>
                    <span class="font-bold text-gray-800 text-3xl">Rp <span x-text="formatRupiah(subtotal())"></span></span>
                </div>

                <input type="text" name="customer_name" placeholder="Customer Name (Optional)" class="w-full bg-white border-2 border-gray-100 rounded-2xl px-5 py-3 text-sm focus:border-purple-300 outline-none transition-colors">
                <input type="number" name="cash_amount" x-model="cash" placeholder="Cash Received" class="w-full bg-white border-2 border-gray-100 rounded-2xl px-5 py-3 text-xl font-bold text-gray-800 focus:border-green-400 outline-none transition-colors" required>
                
                <div class="flex justify-between items-center px-4 py-2 bg-green-50 rounded-xl">
                    <span class="text-[10px] font-bold text-green-600 uppercase tracking-wider">Change</span>
                    <span class="text-lg font-bold text-green-700">Rp <span x-text="formatRupiah(Math.max(0, cash - subtotal()))"></span></span>
                </div>

                <button class="w-full bg-gradient-to-r from-green-400 to-green-600 text-white py-4 rounded-2xl font-bold uppercase tracking-widest shadow-xl hover:shadow-green-200 hover:scale-[1.02] transition">Process Payment</button>
            </form>
            <button @click="openCheckout = false" class="absolute top-6 right-8 text-2xl text-gray-400 hover:text-red-500 transition">×</button>
        </div>
    </div>

    <script>
        function posSystem() {
            return {
                sidebarOpen: true,
                openProductModal: false,
                openSettings: false,
                openCheckout: false,
                isEdit: false,
                cart: [],
                cash: '',
                productForm: { id: null, name: '', category_name: '', price: '', stock: '' },
                
                formatRupiah(number) { return new Intl.NumberFormat('id-ID').format(number); },
                
                addToCart(product) {
                    if(product.stock <= 0) return alert('Out of stock!');
                    let existing = this.cart.find(item => item.id === product.id);
                    if (existing) {
                        if(existing.qty < product.stock) existing.qty++;
                        else alert('Max stock reached!');
                    } else {
                        this.cart.push({ ...product, qty: 1, image: product.image && product.image.startsWith('http') ? product.image : '/storage/' + product.image });
                    }
                },
                
                updateQty(id, change) {
                    let item = this.cart.find(i => i.id === id);
                    if (item) {
                        item.qty += change;
                        if (item.qty <= 0) this.cart = this.cart.filter(i => i.id !== id);
                    }
                },
                
                subtotal() { return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0); },
                
                openEditModal(product) {
                    this.isEdit = true;
                    this.productForm = {
                        id: product.id,
                        name: product.name,
                        category_name: product.category ? product.category.name : '',
                        price: product.price,
                        stock: product.stock
                    };
                    this.openProductModal = true;
                }
            }
        }
    </script>
</body>
</html>