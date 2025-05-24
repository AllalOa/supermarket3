@extends('layouts.app-magazinier')

@section('title', 'Add Product')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header Card -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-xl p-8 mb-8 text-white">
            <div class="flex items-center space-x-4">
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-plus-circle text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold mb-2">Add New Product</h2>
                    <p class="text-blue-100">Fill in the details to add a product to your inventory</p>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white p-6 rounded-2xl shadow-lg mb-8 transform animate-bounce">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                    <div>
                        <h3 class="font-bold text-lg">Success!</h3>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('storeProduct') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Form Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Barcode -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-barcode text-blue-500 mr-2"></i>
                                Barcode
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       name="barcode" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 pl-12" 
                                       required>
                                <i class="fas fa-barcode absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                            </div>
                        </div>

                        <!-- Product Name -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tag text-purple-500 mr-2"></i>
                                Product Name
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       name="name" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-300 pl-12" 
                                       required>
                                <i class="fas fa-tag absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-purple-500 transition-colors"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-folder text-green-500 mr-2"></i>
                            Category
                        </label>
                        <div class="relative">
                            <select name="category" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300 pl-12 appearance-none bg-white">
                                <option value="Food">🍎 Food</option>
                                <option value="Beverages">🥤 Beverages</option>
                                <option value="Electronics">📱 Electronics</option>
                            </select>
                            <i class="fas fa-folder absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-green-500 transition-colors"></i>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Quantity and Price Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Quantity -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-warehouse text-orange-500 mr-2"></i>
                                Quantity
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       name="quantity" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all duration-300 pl-12" 
                                       required>
                                <i class="fas fa-warehouse absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-dollar-sign text-green-500 mr-2"></i>
                                Price
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       name="price" 
                                       step="0.01" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300 pl-12" 
                                       required>
                                <i class="fas fa-dollar-sign absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-green-500 transition-colors"></i>
                            </div>
                        </div>

                        <!-- Unit Price -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-coins text-yellow-500 mr-2"></i>
                                Unit Price
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       name="unit_price" 
                                       step="0.01" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-300 pl-12" 
                                       required>
                                <i class="fas fa-coins absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-yellow-500 transition-colors"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Product Picture -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-camera text-pink-500 mr-2"></i>
                            Product Picture
                        </label>
                        <div class="relative">
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-pink-400 transition-all duration-300 group-hover:bg-pink-50">
                                <div class="flex flex-col items-center space-y-4">
                                    <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-medium">Click to upload or drag and drop</p>
                                        <p class="text-sm text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                    </div>
                                    <input type="file" 
                                           name="product_picture" 
                                           accept="image/*" 
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-3">
                            <i class="fas fa-plus-circle text-xl"></i>
                            <span class="text-lg">Add Product to Inventory</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-4">
                <div class="flex items-center justify-center space-x-2 text-gray-600">
                    <i class="fas fa-shield-alt"></i>
                    <span class="text-sm">All product information is securely stored</span>
                </div>
            </div>
        </div>

        <!-- Quick Tips Card -->
        <div class="mt-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white">
            <h3 class="text-lg font-bold mb-3 flex items-center">
                <i class="fas fa-lightbulb mr-2"></i>
                Quick Tips
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-green-300 mt-0.5"></i>
                    <span>Use clear, descriptive product names</span>
                </div>
                <div class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-green-300 mt-0.5"></i>
                    <span>Ensure barcode is unique and accurate</span>
                </div>
                <div class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-green-300 mt-0.5"></i>
                    <span>Upload high-quality product images</span>
                </div>
                <div class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-green-300 mt-0.5"></i>
                    <span>Double-check prices before submitting</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animations personnalisées */
    @keyframes slideInFromTop {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .content-section {
        animation: slideInFromTop 0.6s ease-out;
    }

    /* Effets de focus pour les inputs */
    input:focus, select:focus {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    /* Effet de survol pour le bouton */
    button[type="submit"]:hover {
        background-size: 200% 200%;
        animation: gradientShift 0.5s ease;
    }

    @keyframes gradientShift {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    /* Effet de drag and drop pour l'upload */
    .group:hover .border-dashed {
        border-color: #ec4899;
        background-color: #fdf2f8;
    }

    /* Animation pour les icônes */
    .group:focus-within i {
        transform: scale(1.1);
    }
</style>
@endsection