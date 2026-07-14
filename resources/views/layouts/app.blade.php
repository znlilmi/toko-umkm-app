<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TokoKita') }} - UMKM E-Commerce</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS / JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation -->
    <nav x-data="{ open: false, userDropdownOpen: false }" class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left Side: Logo & Main Navigation -->
                <div class="flex flex-1 items-center">
                    <!-- Brand Logo -->
                    <div class="shrink-0 flex items-center mr-6">
                        <a href="{{ route('home') }}" class="flex items-center space-x-1">
                            <span class="text-2xl font-black text-indigo-600 tracking-tighter">toko</span>
                            <span class="text-[9px] font-bold bg-orange-500 text-white px-2 py-0.5 rounded-lg uppercase tracking-wider">UMKM</span>
                        </a>
                    </div>

                    <!-- Centered Search Bar -->
                    <div class="hidden md:flex flex-1 items-center max-w-md lg:max-w-lg mx-6">
                        <form action="{{ route('products.index') }}" method="GET" class="w-full flex items-center bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-1.5 focus-within:border-indigo-500 focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-100 transition duration-150">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk lokal terbaik di Toko UMKM..." class="flex-1 bg-transparent border-0 focus:ring-0 p-0 text-xs text-slate-700 placeholder-slate-400">
                            <button type="submit" class="text-indigo-600 hover:text-indigo-700 focus:outline-none pl-2 border-l border-slate-200">
                                <svg class="w-4 h-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="sr-only">Cari</span>
                            </button>
                        </form>
                    </div>

                    <!-- Navigation Links (Public / Customer) -->
                    <div class="hidden space-x-6 sm:-my-px sm:flex h-16">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out {{ request()->routeIs('products.*') ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                            Katalog Produk
                        </a>
                        
                        @auth
                            @if(auth()->user()->role === 'customer' || auth()->user()->role === 'merchant')
                                <a href="{{ route('cart.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out {{ request()->routeIs('cart.*') ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                                    Keranjang
                                </a>
                                <a href="{{ route('wishlist.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out {{ request()->routeIs('wishlist.*') ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                                    Wishlist
                                </a>
                                <a href="{{ route('orders.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out {{ request()->routeIs('orders.*') ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                                    Pesanan Saya
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Right Side: User Menu / CTA -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    @auth
                        <!-- CTA Action based on Role -->
                        <div class="mr-4">
                            @if(auth()->user()->role === 'customer')
                                <a href="{{ route('shop.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition duration-150 ease-in-out shadow-sm shadow-indigo-100">
                                    Buka Toko Gratis
                                </a>
                            @elseif(auth()->user()->role === 'merchant' && !request()->is('merchant*'))
                                <a href="{{ route('merchant.dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition duration-150 ease-in-out border border-indigo-100">
                                    Panel Toko Saya
                                </a>
                            @elseif(auth()->user()->role === 'admin' && !request()->is('admin*'))
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition duration-150 ease-in-out border border-slate-200">
                                    Panel Admin
                                </a>
                            @endif
                        </div>

                        <!-- User Profile Dropdown -->
                        <div class="relative">
                            <button @click="userDropdownOpen = !userDropdownOpen" @click.away="userDropdownOpen = false" class="inline-flex items-center px-3 py-2 border border-slate-200 text-sm font-medium rounded-lg text-slate-600 bg-white hover:text-slate-800 hover:bg-slate-50 focus:outline-none transition duration-150 ease-in-out">
                                <span class="flex items-center space-x-2">
                                    <span class="w-2 h-2 rounded-full {{ auth()->user()->role === 'admin' ? 'bg-rose-500' : (auth()->user()->role === 'merchant' ? 'bg-amber-500' : 'bg-emerald-500') }}"></span>
                                    <span>{{ auth()->user()->name }}</span>
                                    <span class="text-[10px] uppercase font-bold text-slate-400">({{ auth()->user()->role }})</span>
                                </span>
                                <svg class="ms-2 -me-0.5 h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <div x-show="userDropdownOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white border border-slate-100 py-1 ring-1 ring-black ring-opacity-5 focus:outline-none" style="display: none;">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profil Saya</a>
                                
                                <a href="{{ route('addresses.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Daftar Alamat</a>
                                
                                <hr class="border-slate-100 my-1" />

                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">
                                        Keluar Halaman
                                    </a>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="space-x-4">
                            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Masuk</a>
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm shadow-indigo-100 transition duration-150">Daftar</a>
                        </div>
                    @endauth
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-400 hover:text-slate-500 hover:bg-slate-50 focus:outline-none transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-b border-slate-100">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('products.index') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition duration-150 {{ request()->routeIs('products.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-slate-500 hover:bg-slate-50 hover:border-slate-300' }}">Katalog Produk</a>
                @auth
                    @if(auth()->user()->role === 'customer' || auth()->user()->role === 'merchant')
                        <a href="{{ route('cart.index') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition duration-150 {{ request()->routeIs('cart.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-slate-500 hover:bg-slate-50 hover:border-slate-300' }}">Keranjang</a>
                        <a href="{{ route('wishlist.index') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition duration-150 {{ request()->routeIs('wishlist.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-slate-500 hover:bg-slate-50 hover:border-slate-300' }}">Wishlist</a>
                        <a href="{{ route('orders.index') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition duration-150 {{ request()->routeIs('orders.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-slate-500 hover:bg-slate-50 hover:border-slate-300' }}">Pesanan Saya</a>
                    @endif
                @endauth
            </div>

            <!-- Mobile Auth Actions -->
            <div class="pt-4 pb-1 border-t border-slate-100">
                @auth
                    <div class="px-4 py-2">
                        <div class="font-medium text-base text-slate-800">{{ auth()->user()->name }}</div>
                        <div class="font-medium text-sm text-slate-500">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="mt-2 space-y-1">
                        @if(auth()->user()->role === 'customer')
                            <a href="{{ route('shop.create') }}" class="block w-full pl-3 pr-4 py-2 text-left text-base font-medium text-indigo-600 hover:bg-slate-50">Buka Toko Gratis</a>
                        @elseif(auth()->user()->role === 'merchant')
                            <a href="{{ route('merchant.dashboard') }}" class="block w-full pl-3 pr-4 py-2 text-left text-base font-medium text-indigo-600 hover:bg-slate-50">Panel Toko Saya</a>
                        @elseif(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block w-full pl-3 pr-4 py-2 text-left text-base font-medium text-indigo-600 hover:bg-slate-50">Panel Admin</a>
                        @endif
                        
                        <a href="{{ route('profile.edit') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-slate-600 hover:bg-slate-50">Profil Saya</a>
                        <a href="{{ route('addresses.index') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-slate-600 hover:bg-slate-50">Daftar Alamat</a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left pl-3 pr-4 py-2 text-base font-medium text-rose-600 hover:bg-rose-50">
                                Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <div class="px-4 py-2 space-y-2">
                        <a href="{{ route('login') }}" class="block text-center w-full px-4 py-2 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">Masuk</a>
                        <a href="{{ route('register') }}" class="block text-center w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Layout Wrapper: Sidebar + Main Content -->
    @php
        $isAdminPanel = auth()->check() && auth()->user()->role === 'admin' && request()->is('admin*');
        $isMerchantPanel = auth()->check() && auth()->user()->role === 'merchant' && request()->is('merchant*');
    @endphp

    <div class="flex-1 flex flex-col md:flex-row">
        <!-- Sidebar for Admin / Merchant Panels -->
        @if ($isAdminPanel || $isMerchantPanel)
            <aside class="w-full md:w-64 bg-white border-r border-slate-100 flex-shrink-0">
                <div class="p-6">
                    <span class="text-xs font-semibold tracking-wider text-slate-400 uppercase">
                        {{ $isAdminPanel ? 'Panel Administrator' : 'Manajemen Toko' }}
                    </span>
                </div>
                <nav class="px-4 pb-6 space-y-1">
                    @if ($isAdminPanel)
                        <!-- Admin Menus -->
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150 {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a1.125 1.125 0 001.59 0l4.318-4.317a1.125 1.125 0 000-1.591L9.581 3.66a1.125 1.125 0 00-1.593 0z" />
                            </svg>
                            Kelola Kategori
                        </a>
                        <a href="{{ route('admin.shops.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150 {{ request()->routeIs('admin.shops.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72M6.75 18h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .414.336.75.75.75z" />
                            </svg>
                            Moderasi Toko
                        </a>
                        <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150 {{ request()->routeIs('admin.reviews.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.12 2.83 2.78 2.83h1.37l3.52 3.52c.22.22.58.22.8 0V17.1c3.15-.15 5.75-2.6 5.75-5.85a5.99 5.99 0 00-6-6 6 6 0 00-6 6v.26z" />
                            </svg>
                            Kelola Ulasan
                        </a>
                    @elseif ($isMerchantPanel)
                        <!-- Merchant Menus -->
                        <a href="{{ route('merchant.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150 {{ request()->routeIs('merchant.dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                            </svg>
                            Dashboard Toko
                        </a>
                        <a href="{{ route('merchant.products.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150 {{ request()->routeIs('merchant.products.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            Katalog Produk
                        </a>
                        <a href="{{ route('merchant.orders.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150 {{ request()->routeIs('merchant.orders.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            Pesanan Masuk
                        </a>
                        <a href="{{ route('merchant.inventory.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150 {{ request()->routeIs('merchant.inventory.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125H5.625c-.621 0-1.125-.504-1.125-1.125v-3.75c0-.621.504-1.125 1.125-1.125z" />
                            </svg>
                            Stok & Mutasi
                        </a>
                        <a href="{{ route('merchant.shop.edit') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150 {{ request()->routeIs('merchant.shop.edit') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827a1.125 1.125 0 01.26 1.43l-1.297 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.936 6.936 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Pengaturan Toko
                        </a>
                        <a href="{{ route('merchant.reviews.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150 {{ request()->routeIs('merchant.reviews.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.12 2.83 2.78 2.83h1.37l3.52 3.52c.22.22.58.22.8 0V17.1c3.15-.15 5.75-2.6 5.75-5.85a5.99 5.99 0 00-6-6 6 6 0 00-6 6v.26z" />
                            </svg>
                            Ulasan Produk
                        </a>
                    @endif
                </nav>
            </aside>
        @endif

        <!-- Main Content Area -->
        <main class="flex-1 w-full min-w-0">
            <!-- Flash Message System -->
            <div x-data="{ showSuccess: true, showError: true, showWarning: true, showInfo: true }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <!-- Success Notification -->
                @if (session('success'))
                    <div x-show="showSuccess" x-transition class="flex items-center p-4 mb-4 text-emerald-800 border-l-4 border-emerald-500 bg-emerald-50 rounded-r-xl shadow-sm" role="alert">
                        <svg class="flex-shrink-0 w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                        </svg>
                        <div class="ms-3 text-sm font-medium">
                            {{ session('success') }}
                        </div>
                        <button type="button" @click="showSuccess = false" class="ms-auto -mx-1.5 -my-1.5 bg-emerald-50 text-emerald-500 rounded-lg p-1.5 hover:bg-emerald-100 inline-flex items-center justify-center h-8 w-8">
                            <span class="sr-only">Close</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                        </button>
                    </div>
                @endif

                <!-- Error Notification -->
                @if (session('error') || $errors->any())
                    <div x-show="showError" x-transition class="flex items-start p-4 mb-4 text-rose-800 border-l-4 border-rose-500 bg-rose-50 rounded-r-xl shadow-sm" role="alert">
                        <svg class="flex-shrink-0 w-4 h-4 mr-3 mt-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                        </svg>
                        <div class="ms-3 text-sm font-medium">
                            @if(session('error'))
                                {{ session('error') }}
                            @else
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <button type="button" @click="showError = false" class="ms-auto -mx-1.5 -my-1.5 bg-rose-50 text-rose-500 rounded-lg p-1.5 hover:bg-rose-100 inline-flex items-center justify-center h-8 w-8">
                            <span class="sr-only">Close</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                        </button>
                    </div>
                @endif

                <!-- Warning Notification -->
                @if (session('warning'))
                    <div x-show="showWarning" x-transition class="flex items-center p-4 mb-4 text-amber-800 border-l-4 border-amber-500 bg-amber-50 rounded-r-xl shadow-sm" role="alert">
                        <svg class="flex-shrink-0 w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                        </svg>
                        <div class="ms-3 text-sm font-medium">
                            {{ session('warning') }}
                        </div>
                        <button type="button" @click="showWarning = false" class="ms-auto -mx-1.5 -my-1.5 bg-amber-50 text-amber-500 rounded-lg p-1.5 hover:bg-amber-100 inline-flex items-center justify-center h-8 w-8">
                            <span class="sr-only">Close</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Page Slot -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-4">
                    <span class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-violet-500 bg-clip-text text-transparent">TokoKita</span>
                    <p class="text-sm text-slate-500">Platform e-commerce pendukung UMKM lokal Indonesia. Belanja aman, mudah, dan terpercaya langsung dari produsen lokal.</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4">Belanja</h4>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="{{ route('products.index') }}" class="hover:text-indigo-600">Semua Produk</a></li>
                        <li><a href="#" class="hover:text-indigo-600">Promo UMKM</a></li>
                        <li><a href="#" class="hover:text-indigo-600">Produk Terbaru</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="#" class="hover:text-indigo-600">Hubungi Kami</a></li>
                        <li><a href="#" class="hover:text-indigo-600">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-indigo-600">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4">Toko UMKM</h4>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="{{ route('shop.create') }}" class="hover:text-indigo-600">Buka Toko Gratis</a></li>
                        <li><a href="#" class="hover:text-indigo-600">Panduan Penjual</a></li>
                        <li><a href="#" class="hover:text-indigo-600">Akademi UMKM</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} TokoKita. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 sm:mt-0">
                    <a href="#" class="hover:text-slate-600">Facebook</a>
                    <a href="#" class="hover:text-slate-600">Instagram</a>
                    <a href="#" class="hover:text-slate-600">Twitter</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
