<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Kelola Kategori Global</h1>
                <p class="text-sm text-slate-400">Atur kategori produk secara terstruktur untuk memudahkan navigasi pembeli.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm shadow-indigo-100 transition">
                Tambah Kategori Baru
            </a>
        </div>

        <!-- Categories Table List -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            @if($categories->isEmpty())
                <div class="text-center py-12 text-slate-400">
                    <p class="text-sm">Kategori global belum terdaftar.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th class="px-6 py-4">Nama Kategori</th>
                                <th class="px-6 py-4">Slug (URL)</th>
                                <th class="px-6 py-4">Kategori Induk</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($categories as $category)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="px-6 py-4 font-bold text-slate-800 text-sm">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                        {{ $category->slug }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($category->parent)
                                            <span class="text-xs bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded font-semibold">{{ $category->parent->name }}</span>
                                        @else
                                            <span class="text-xs font-semibold text-slate-400 italic">Induk (Root)</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition border border-indigo-100">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Sub-kategori yang berada di bawahnya akan otomatis dipindahkan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition border border-rose-100">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
