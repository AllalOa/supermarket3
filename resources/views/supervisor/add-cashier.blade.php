
@extends('layouts.appp')


@section('title', 'Add Cashier')

@section('content')



<div class="bg-white p-6 rounded-xl shadow-sm max-w-4xl mx-auto">

{{-- ✅ Success Message --}}
    @if(session('success'))
        <div class="mb-4 flex items-center justify-between bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow">
            <span class="font-medium">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove();" class="text-green-700 hover:text-green-900">
                ✖
            </button>
        </div>
    @endif

    {{-- ❌ Error Message --}}
    @if(session('error'))
        <div class="mb-4 flex items-center justify-between bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow">
            <span class="font-medium">{{ session('error') }}</span>
            <button onclick="this.parentElement.remove();" class="text-red-700 hover:text-red-900">
                ✖
            </button>
        </div>
    @endif

  <h2 class="text-2xl font-semibold text-[#2b2d42] mb-6">Add New Cashier</h2>
  
  <form action="{{ route('store.cashier') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <!-- Profile Picture Upload -->
    <div class="mb-8">
      <label class="block text-sm font-medium text-[#6c757d] mb-2">Profile Picture</label>
      <div class="flex items-center gap-4">
        <div class="w-20 h-20 rounded-full bg-gray-100 border-2 border-dashed border-gray-300 relative">
          <input 
            type="file" 
            id="imageUploadCashier" 
            name="profile_picture"
            class="opacity-0 absolute w-full h-full cursor-pointer" 
            accept="image/*"
          >
          
          <img id="previewImageCashier" src="" alt="" class="w-full h-full object-cover hidden rounded-full">
          <i id="uploadPlaceholderCashier" class="fas fa-camera absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
        <div>
          <span class="text-sm text-gray-500">JPEG or PNG, max 2MB</span>
        </div>
      </div>
    </div>

    <!-- Form Fields (2 fields per row) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-[#6c757d]">Full Name</label>
        <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:border-[#4361ee] focus:ring-1 focus:ring-[#4361ee]">
      </div>

      <div>
        <label class="block text-sm font-medium text-[#6c757d]">Email</label>
        <input type="email" name="email" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:border-[#4361ee] focus:ring-1 focus:ring-[#4361ee]">
      </div>

      <div>
        <label class="block text-sm font-medium text-[#6c757d]">Password</label>
        <input type="password" name="password" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:border-[#4361ee] focus:ring-1 focus:ring-[#4361ee]">
      </div>

      <div>
        <label class="block text-sm font-medium text-[#6c757d]">Phone Number</label>
        <input type="text" name="phone" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:border-[#4361ee] focus:ring-1 focus:ring-[#4361ee]">
      </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="w-full bg-[#4361ee] text-white py-3 px-6 rounded-lg font-semibold hover:bg-[#3f37c9] transition-all">
      <i class="fas fa-user-plus"></i> Add Cashier
    </button>
  </form>
</div>

<script>
  // Image Preview Functionality
  document.getElementById('imageUploadCashier').addEventListener('change', function(e) {
    const [file] = e.target.files;
    const preview = document.getElementById('previewImageCashier');
    const placeholder = document.getElementById('uploadPlaceholderCashier');
    
    if (file) {
      const objectURL = URL.createObjectURL(file);
      preview.src = objectURL;
      preview.classList.remove('hidden');
      placeholder.classList.add('hidden');
    } else {
      preview.classList.add('hidden');
      placeholder.classList.remove('hidden');
    }
  });
</script>
@endsection
