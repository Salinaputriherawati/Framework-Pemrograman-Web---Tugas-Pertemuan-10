<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container p-4 mx-auto">
        <div class="overflow-x-auto">
            @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-500">
                {{ session('success') }}
            </div>
            @elseif (session('error'))
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-500">
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <script>
                alert("{{ session('success') }}");
            </script>
            @endif

            @if(session('error'))
            <script>
                alert("{{ session('error') }}");
            </script>
            @endif

            <form method="GET" action="{{ route('product-index') }}" class="mb-4 flex items-center">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari produk..." class="w-1/4 rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <button type="submit" class="ml-2 rounded-lg bg-green-500 px-4 py-2 text-white shadow-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">Cari</button>
            </form>

            <a href="{{ route('product-create')}}">
                <button class="px-6 py-4 text-white bg-green-500 border
                    border-green-500 rounded-lg shadow-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Add product data
                </button>
            </a>

            <a href="{{ route('product-export-excel') }}">
                <button class="px-6 py-4 text-white bg-blue-500 border
            border-blue-500 rounded-lg shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Export ke Excel
                </button>
            </a>

            <a href="{{ route('product-export-pdf') }}">
                <button class="px-6 py-4 text-white bg-red-500 border border-red-500 rounded-lg shadow-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Export ke PDF
                </button>
            </a>

            <a href="{{ route('product-export-jpg') }}">
                <button class="px-6 py-4 text-white bg-yellow-500 border border-yellow-500 rounded-lg shadow-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    Export ke JPG
                </button>
            </a>

            <table id="productTable" class="min-w-full border border-collapse border-gray-200">

                <thead>
                    <tr class="bg-gray-100">
                        {{-- ID --}}
                        <th class="px-4 py-2 border border-gray-200">
                            <a class="flex items-center gap-1"
                                href="{{ request()->fullUrlWithQuery([
                    'sort_by' => 'id',
                    'order' => (request('sort_by') === 'id' && request('order') === 'asc') ? 'desc' : 'asc'
               ]) }}">
                                ID
                                {!! request('sort_by') === 'id'
                                ? (request('order') === 'asc' ? '🔼' : '🔽')
                                : '⬍' !!}
                            </a>
                        </th>

                        {{-- Product Name --}}
                        <th class="px-4 py-2 border border-gray-200">
                            <a class="flex items-center gap-1"
                                href="{{ request()->fullUrlWithQuery([
                    'sort_by' => 'product_name',
                    'order' => (request('sort_by') === 'product_name' && request('order') === 'asc') ? 'desc' : 'asc'
               ]) }}">
                                Product Name
                                {!! request('sort_by') === 'product_name'
                                ? (request('order') === 'asc' ? '🔼' : '🔽')
                                : '⬍' !!}
                            </a>
                        </th>

                        {{-- Unit --}}
                        <th class="px-4 py-2 border border-gray-200">
                            <a class="flex items-center gap-1"
                                href="{{ request()->fullUrlWithQuery([
                    'sort_by' => 'unit',
                    'order' => (request('sort_by') === 'unit' && request('order') === 'asc') ? 'desc' : 'asc'
               ]) }}">
                                Unit
                                {!! request('sort_by') === 'unit'
                                ? (request('order') === 'asc' ? '🔼' : '🔽')
                                : '⬍' !!}
                            </a>
                        </th>

                        {{-- Type --}}
                        <th class="px-4 py-2 border border-gray-200">
                            <a class="flex items-center gap-1"
                                href="{{ request()->fullUrlWithQuery([
                    'sort_by' => 'type',
                    'order' => (request('sort_by') === 'type' && request('order') === 'asc') ? 'desc' : 'asc'
               ]) }}">
                                Type
                                {!! request('sort_by') === 'type'
                                ? (request('order') === 'asc' ? '🔼' : '🔽')
                                : '⬍' !!}
                            </a>
                        </th>

                        {{-- Information --}}
                        <th class="px-4 py-2 border border-gray-200">
                            <a class="flex items-center gap-1"
                                href="{{ request()->fullUrlWithQuery([
                    'sort_by' => 'information',
                    'order' => (request('sort_by') === 'information' && request('order') === 'asc') ? 'desc' : 'asc'
               ]) }}">
                                Information
                                {!! request('sort_by') === 'information'
                                ? (request('order') === 'asc' ? '🔼' : '🔽')
                                : '⬍' !!}
                            </a>
                        </th>

                        {{-- Qty --}}
                        <th class="px-4 py-2 border border-gray-200">
                            <a class="flex items-center gap-1"
                                href="{{ request()->fullUrlWithQuery([
                    'sort_by' => 'qty',
                    'order' => (request('sort_by') === 'qty' && request('order') === 'asc') ? 'desc' : 'asc'
               ]) }}">
                                Qty
                                {!! request('sort_by') === 'qty'
                                ? (request('order') === 'asc' ? '🔼' : '🔽')
                                : '⬍' !!}
                            </a>
                        </th>

                        {{-- Producer --}}
                        <th class="px-4 py-2 border border-gray-200">
                            <a class="flex items-center gap-1"
                                href="{{ request()->fullUrlWithQuery([
                    'sort_by' => 'producer',
                    'order' => (request('sort_by') === 'producer' && request('order') === 'asc') ? 'desc' : 'asc'
               ]) }}">
                                Producer
                                {!! request('sort_by') === 'producer'
                                ? (request('order') === 'asc' ? '🔼' : '🔽')
                                : '⬍' !!}
                            </a>
                        </th>

                        <th class="px-4 py-2 border border-gray-200">Aksi</th>
                    </tr>
                </thead>


                <tbody>
                    @forelse ($data as $item)
                    <tr class="bg-white">
                        <td class="px-4 py-2 border border-gray-200">{{ $item->id }}</td>
                        <td class=" px-4 py-2 border border-gray-200:hover-text-blue-500 hover:underline">
                            <a href="{{ route('product-detail', [$item->id]) }}">
                                {{ $item->product_name }}
                            </a>
                        </td>
                        <td class="px-4 py-2 border border-gray-200">{{$item->unit }}</td>
                        <td class="px-4 py-2 border border-gray-200">{{$item->type }}</td>
                        <td class="px-4 py-2 border border-gray-200">{{$item->information }}</td>
                        <td class="px-4 py-2 border border-gray-200">{{$item->qty }}</td>
                        <td class="px-4 py-2 border border-gray-200">{{$item->producer }}</td>
                        <td class="px-4 py-2 border border-gray-200">
                            <a href="{{ route('product-edit', $item->id) }}"
                                class="px-2 text-blue-600 hover:text-blue-800">Edit</a>
                            <button
                                class="px-2 text-red-600 hover:text-red-Menampilkan data dari database kedalam tabel 800"
                                onclick="confirmDelete('{{ route('product-deleted', $item->id) }}')">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <p class="mb-4 text-center text-2xl font-bold text-red-600">No products found</p>
                    @endforelse
                    <!-- Tambahkan baris lainnya sesuai kebutuhan -->
                </tbody>
            </table>
            <!-- pagination -->
            <div class="mt-4">
                <!-- {{ $data -> links() }} -->
                {{ $data->appends(['search' => request('search')])->links() }}
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(deleteUrl) {
            console.log(deleteUrl);
            if (confirm('Apakah Anda yakin ingin menghapus data ini ? ')) {
                // Jika user mengonfirmasi, kita dapat membuat form dan mengirimkan permintaan delete
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                // Tambahkan CSRF token
                let csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                // Tambahkan method spoofing untuk DELETE (karena HTML form hanya mendukung GET dan POST)
                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                // Tambahkan form ke body dan submit
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    <script>
        let sortDirections = {}; // Simpan arah sorting untuk tiap kolom

        function sortTable(columnIndex) {
            const table = document.getElementById("productTable");
            const tbody = table.querySelector("tbody");
            const rows = Array.from(tbody.querySelectorAll("tr"));

            // Tentukan default arah jika belum ada
            if (sortDirections[columnIndex] === undefined) {
                sortDirections[columnIndex] = true; // ASC pertama kali
            }

            const asc = sortDirections[columnIndex];

            rows.sort((a, b) => {
                const valA = a.children[columnIndex].innerText.trim().toLowerCase();
                const valB = b.children[columnIndex].innerText.trim().toLowerCase();

                // Jika angka, urutkan sebagai angka
                if (!isNaN(valA) && !isNaN(valB)) {
                    return asc ? valA - valB : valB - valA;
                }

                return asc ? valA.localeCompare(valB) : valB.localeCompare(valA);
            });

            sortDirections[columnIndex] = !asc; // toggle arah

            // Replace isi tbody
            tbody.innerHTML = "";
            rows.forEach(row => tbody.appendChild(row));
        }
    </script>
</x-app-layout>