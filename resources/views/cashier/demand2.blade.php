@extends('layouts.app-cashier')

@section('content')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery & Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <style>
        .select2-container--default .select2-selection--single {
            height: 42px;
            padding: 0;
            border-color: #e5e7eb;
            border-radius: 0.5rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px;
            padding-left: 1rem;
            padding-right: 2rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
            right: 8px;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6;
        }
        .select2-dropdown {
            border-color: #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .select2-search__field {
            border-radius: 0.375rem !important;
            padding: 0.5rem !important;
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            display: flex;
            align-items: center;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }
        .select2-container--default .select2-selection--single {
            display: flex;
            align-items: center;
        }
        .product-select-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .product-select-item img {
            width: 2rem;
            height: 2rem;
            border-radius: 0.375rem;
            object-fit: cover;
        }
        .product-select-item .product-info {
            display: flex;
            flex-direction: column;
        }
        .product-select-item .product-name {
            font-weight: 500;
            color: #1f2937;
        }
    </style>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Nouvelle Demande de Produits</h1>
                <p class="mt-2 text-sm text-gray-600">Créez une nouvelle demande en sélectionnant les produits nécessaires</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 sm:p-8">
                    <form id="request-form" method="POST" action="{{ route('cashier.MakeAnOrder') }}" class="space-y-8">
                        @csrf
                        
                        <!-- Product Selection -->
                        <div class="bg-blue-50 rounded-lg p-6 border border-blue-100">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-2 text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    <h2 class="text-lg font-semibold">Ajouter des Produits</h2>
                                </div>
                                <div class="flex gap-4">
                                    <select id="product-select" class="select2 flex-1 rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="" disabled selected>Choisissez un produit</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" 
                                                data-name="{{ $product->name }}"
                                                data-price="{{ $product->price }}"
                                                data-image="{{ asset('storage/' . $product->product_picture) }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" id="add-product" class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Ajouter
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Products List -->
                        <div class="bg-white rounded-lg border border-gray-200">
                            <div class="p-4 border-b border-gray-200 bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <h2 class="font-semibold text-gray-800">Liste des Produits</h2>
                                    <span id="items-count" class="px-3 py-1 text-sm text-blue-600 bg-blue-50 rounded-full">0 articles</span>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <th class="p-4">Image</th>
                                            <th class="p-4">Produit</th>
                                            <th class="p-4">Quantité</th>
                                            <th class="p-4">Total</th>
                                            <th class="p-4 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-list" class="divide-y divide-gray-200"></tbody>
                                </table>
                            </div>
                            <!-- Empty State -->
                            <div id="empty-state" class="py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 12H4M12 4v16m8-8H4"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Aucun produit ajouté</p>
                                <p class="text-xs text-gray-400">Utilisez le sélecteur ci-dessus pour ajouter des produits</p>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Total de la commande</h3>
                                    <p class="text-sm text-gray-500">Montant total de tous les produits</p>
                                </div>
                                <div class="text-3xl font-bold text-blue-600" id="total-price">0.00 DA</div>
                            </div>
                        </div>

                        <input type="hidden" name="products_json" id="products_json">

                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Envoyer la demande
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function formatProduct(product) {
                if (!product.id) {
                    return product.text;
                }

                let imageUrl = $(product.element).data('image');
                let productName = product.text;

                return $(`
                    <div class="product-select-item">
                        <img src="${imageUrl}" alt="${productName}">
                        <div class="product-info">
                            <span class="product-name">${productName}</span>
                        </div>
                    </div>
                `);
            }

            $('#product-select').select2({
                templateResult: formatProduct,
                templateSelection: formatProduct,
                placeholder: "Rechercher un produit...",
                allowClear: true,
                escapeMarkup: function(markup) {
                    return markup;
                }
            });

            updateEmptyState();
        });

        document.getElementById('add-product').addEventListener('click', function() {
            let select = document.getElementById('product-select');
            let selectedOption = select.options[select.selectedIndex];

            if (!selectedOption.value) return;

            let productId = selectedOption.value;
            let productName = selectedOption.dataset.name;
            let productPrice = parseFloat(selectedOption.dataset.price);
            let productImage = selectedOption.dataset.image;

            // Modern quantity input using SweetAlert2
            Swal.fire({
                title: 'Entrez la quantité',
                input: 'number',
                inputAttributes: {
                    min: 1,
                    step: 1
                },
                inputValue: 1,
                showCancelButton: true,
                confirmButtonText: 'Ajouter',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
            }).then((result) => {
                if (result.isConfirmed && result.value > 0) {
                    let quantity = result.value;
                    let total = (quantity * productPrice).toFixed(2);

                    let tableRow = `
                        <tr data-id="${productId}" class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="p-4">
                                <img src="${productImage}" class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                            </td>
                            <td class="p-4">
                                <div class="font-medium text-gray-900">${productName}</div>
                                <div class="text-sm text-gray-500">ID: #${productId}</div>
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-medium">${quantity}</span>
                            </td>
                            <td class="p-4 font-medium text-gray-900">${total} DA</td>
                            <td class="p-4 text-right">
                                <button class="remove-product inline-flex items-center px-3 py-1 border border-transparent rounded-md text-sm font-medium text-red-600 hover:bg-red-50 focus:outline-none transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    Supprimer
                                </button>
                            </td>
                        </tr>`;

                    document.getElementById("product-list").insertAdjacentHTML('beforeend', tableRow);
                    updateTotal();
                    saveProducts();
                    updateEmptyState();
                }
            });
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-product') || e.target.closest('.remove-product')) {
                const row = e.target.closest('tr');
                // Fade out animation
                row.style.transition = 'opacity 0.3s ease-out';
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                    updateTotal();
                    saveProducts();
                    updateEmptyState();
                }, 300);
            }
        });

        function updateTotal() {
            let total = 0;
            let itemCount = 0;
            document.querySelectorAll("#product-list tr").forEach(row => {
                const totalText = row.cells[3].textContent.replace(' DA', '').trim();
                total += parseFloat(totalText) || 0;
                itemCount++;
            });
            document.getElementById('total-price').textContent = total.toFixed(2) + " DA";
            document.getElementById('items-count').textContent = itemCount + (itemCount === 1 ? ' article' : ' articles');
        }

        function saveProducts() {
            let products = [];
            document.querySelectorAll("#product-list tr").forEach(row => {
                products.push({
                    id: row.dataset.id,
                    name: row.cells[1].querySelector('.font-medium').textContent,
                    quantity: row.cells[2].querySelector('span').textContent,
                    total: row.cells[3].textContent.replace(' DA', '').trim()
                });
            });
            document.getElementById('products_json').value = JSON.stringify(products);
        }

        function updateEmptyState() {
            const emptyState = document.getElementById('empty-state');
            const productList = document.getElementById('product-list');
            if (productList.children.length === 0) {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
            }
        }
    </script>
@endsection