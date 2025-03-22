@extends('layouts.app-cashier')

@section('content')


    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery & Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-3xl mx-auto">

    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-3xl">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Demande de Produits</h2>
        <form id="request-form" method="POST" action="{{ route('cashier.MakeAnOrder') }}">
            @csrf
        <!-- Sélection de produit -->
        <div class="mb-4">
            <label for="product-select" class="block text-gray-700 font-medium mb-1">Sélectionner un produit :</label>
            <select id="product-select" class="select2 w-full border-gray-300 rounded-md">
                <option value="" disabled selected>Choisissez un produit</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                        data-price="{{ $product->price }}"
                        data-image="{{ asset('storage/' . $product->product_picture) }}">
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Bouton Ajouter -->
        <button type="button" id="add-product" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700">
            Ajouter le Produit
        </button>

        <!-- Liste des produits demandés -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Liste des Produits Demandés</h3>
            <table class="w-full border-collapse bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="p-2">Image</th>
                        <th class="p-2">Produit</th>
                        <th class="p-2">Quantité</th>
                        <th class="p-2">Prix</th>
                        <th class="p-2">Total</th>
                        <th class="p-2">Action</th>
                    </tr>
                </thead>
                <tbody id="product-list"></tbody>
            </table>
        </div>

        <!-- Total Commande -->
        <div class="mt-4 text-gray-800 font-medium">
            <strong>Total : </strong><span id="total-price" class="text-lg text-blue-600">0.00 DH</span>
        </div>

        <!-- Champ caché pour envoyer les produits -->
        <input type="hidden" name="products_json" id="products_json">

        <!-- Bouton Soumettre -->
        <button type="submit" class="w-full bg-green-600 text-white mt-4 px-4 py-2 rounded-md shadow hover:bg-green-700">
            Envoyer la Demande
        </button>
       </form>
    </div>
</div>
    <!-- JavaScript -->
    <script>
        $(document).ready(function() {
            function formatProduct(product) {
                if (!product.id) return product.text;

                let imageUrl = $(product.element).data('image');
                let productName = product.text;

                return $(`<span class="flex items-center">
                    <img src="${imageUrl}" class="w-8 h-8 rounded-full mr-2"> ${productName}
                </span>`);
            }

            $('#product-select').select2({
                templateResult: formatProduct,
                templateSelection: formatProduct
            });
        });

        // Ajouter un produit à la liste
        document.getElementById('add-product').addEventListener('click', function() {
            let select = document.getElementById('product-select');
            let selectedOption = select.options[select.selectedIndex];

            if (!selectedOption.value) return;

            let productId = selectedOption.value;
            let productName = selectedOption.dataset.name;
            let productPrice = parseFloat(selectedOption.dataset.price);
            let productImage = selectedOption.dataset.image;

            let quantity = prompt("Entrez la quantité :", 1);
            if (!quantity || quantity <= 0) return;

            let total = (quantity * productPrice).toFixed(2);

            let tableRow = `
                <tr data-id="${productId}" class="border-b">
                    <td class="p-2"><img src="${productImage}" class="w-10 h-10 rounded"></td>
                    <td class="p-2">${productName}</td>
                    <td class="p-2">${quantity}</td>
                    <td class="p-2">${productPrice} </td>
                    <td class="p-2">${total} </td>
                    <td class="p-2">
                        <button class="remove-product bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                            Supprimer
                        </button>
                    </td>
                </tr>`;

            document.getElementById("product-list").insertAdjacentHTML('beforeend', tableRow);
            updateTotal();
            saveProducts();
        });

        // Supprimer un produit
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-product')) {
                e.target.closest('tr').remove();
                updateTotal();
                saveProducts();
            }
        });

        // Mettre à jour le total
        function updateTotal() {
            let total = 0;
            document.querySelectorAll("#product-list tr").forEach(row => {
                total += parseFloat(row.cells[4].textContent);
            });
            document.getElementById('total-price').textContent = total.toFixed(2) + " DA";
        }

        // Sauvegarder les produits sélectionnés
        function saveProducts() {
            let products = [];
            document.querySelectorAll("#product-list tr").forEach(row => {
                products.push({
                    id: row.dataset.id,
                    name: row.cells[1].textContent,
                    quantity: row.cells[2].textContent,
                    price: row.cells[3].textContent.replace( ''),
                    total: row.cells[4].textContent.replace( '')
                });
            });
            document.getElementById('products_json').value = JSON.stringify(products);
        }
    </script>

@endsection