@extends('layouts.appp')

@section('title', 'Foyers Management')

@section('content')

<body class="bg-gray-50 min-h-screen">

    
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 bg-white p-6 rounded-xl shadow-sm">
    <div class="mb-4 md:mb-0">
        <h1 class="text-2xl font-bold text-gray-900">Gestion Des Foyer</h1>
        <p class="text-gray-600 mt-1 text-sm font-medium">Welcome back, Alex! Here's what's happening today.</p>
    </div>
    
    <div class="relative w-full md:w-64">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fas fa-search h-5 w-5 text-blue-500"></i>
        </div>
        <input
            type="text"
            id="search-input"
            class="w-full pl-10 pr-4 py-2 bg-blue-50 border border-blue-100 text-blue-800 placeholder-blue-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
            placeholder="Search foyers..."
        >
    </div>
</div>   

<main class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <!-- Foyers List Section -->
        <div class="mb-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Foyers List</h2>
                <button
                    id="add-foyer-btn"
                    class="flex items-center bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors"
                >
                    <i class="fas fa-plus h-5 w-5 mr-2"></i>
                    Add New Foyer
                </button>
            </div>

            <!-- New Foyer Form -->
            <div id="new-foyer-form" class="bg-white shadow-md rounded-lg p-6 mb-6 border-l-4 border-indigo-500 hidden">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Foyer</h3>
                <form id="create-foyer-form">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foyer Name</label>
                            <input
                                type="text"
                                id="new-foyer-name"
                                name="name"
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                required
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea
                                id="new-foyer-description"
                                name="description"
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                rows="2"
                            ></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select
                                id="new-foyer-status"
                                name="stat"
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Chief</label>
                            <select
                                id="new-foyer-chief"
                                name="chief_id"
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                required
                            >
                                <option value="">Select Chief</option>
                                <!-- Will be populated via AJAX -->
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            id="cancel-create-btn"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors flex items-center"
                        >
                            <i class="fas fa-save h-5 w-5 mr-2"></i>
                            Create Foyer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Foyers Cards Container -->
            <div id="foyers-container" class="grid grid-cols-1 gap-6">
                <!-- Foyer cards will be generated here -->
                @if(count($foyers) == 0)
                <div class="text-center py-12 bg-white rounded-lg shadow">
                    <p class="text-gray-500 text-lg">No foyers found. Create your first foyer to get started.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Edit Foyer Modal -->
        <div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-screen overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800">Edit Foyer</h3>
                    <button id="close-edit-modal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times h-6 w-6"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <form id="edit-foyer-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit-foyer-id" name="id">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Foyer Name</label>
                                <input
                                    type="text"
                                    id="edit-foyer-name"
                                    name="name"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    required
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea
                                    id="edit-foyer-description"
                                    name="description"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    rows="2"
                                ></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select
                                    id="edit-foyer-status"
                                    name="stat"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Chief</label>
                                <select
                                    id="edit-foyer-chief"
                                    name="chief_id"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    required
                                >
                                    <option value="">Select Chief</option>
                                    <!-- Will be populated via AJAX -->
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button
                                type="button"
                                id="cancel-edit-btn"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors flex items-center"
                            >
                                <i class="fas fa-save h-5 w-5 mr-2"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Worker Modal -->
        <div id="add-worker-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800">Add Worker to Foyer</h3>
                    <button id="close-worker-modal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times h-6 w-6"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <form id="add-worker-form">
                        @csrf
                        <input type="hidden" id="worker-foyer-id" name="foyer_id">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select User</label>
                            <select
                                id="worker-user-id"
                                name="user_id"
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                required
                            >
                                <option value="">Select User</option>
                                <!-- Will be populated via AJAX -->
                            </select>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button
                                type="button"
                                id="cancel-worker-btn"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors flex items-center"
                            >
                                <i class="fas fa-user-plus h-5 w-5 mr-2"></i>
                                Add Worker
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

 

    <script>
        // Keep track of expanded foyers
        let expandedFoyers = new Set();

        // DOM Elements
        const foyersContainer = document.getElementById('foyers-container');
        const searchInput = document.getElementById('search-input');
        const addFoyerBtn = document.getElementById('add-foyer-btn');
        const newFoyerForm = document.getElementById('new-foyer-form');
        const cancelCreateBtn = document.getElementById('cancel-create-btn');
        const createFoyerForm = document.getElementById('create-foyer-form');
        const editModal = document.getElementById('edit-modal');
        const closeEditModal = document.getElementById('close-edit-modal');
        const cancelEditBtn = document.getElementById('cancel-edit-btn');
        const editFoyerForm = document.getElementById('edit-foyer-form');
        const addWorkerModal = document.getElementById('add-worker-modal');
        const closeWorkerModal = document.getElementById('close-worker-modal');
        const cancelWorkerBtn = document.getElementById('cancel-worker-btn');
        const addWorkerForm = document.getElementById('add-worker-form');

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            loadFoyers();
            loadAvailableChiefs();
            setupEventListeners();
        });

        // Load all foyers from the server
        function loadFoyers() {
            const searchTerm = searchInput.value.toLowerCase();
            
            @if(count($foyers) > 0)
                const foyers = @json($foyers);
                renderFoyers(foyers, searchTerm);
            @endif
        }

        // Load available chiefs for dropdowns
        function loadAvailableChiefs() {
            fetch("{{ route('foyers.chiefs.available') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        const newChiefSelect = document.getElementById('new-foyer-chief');
                        const editChiefSelect = document.getElementById('edit-foyer-chief');
                        
                        // Clear existing options except the first one
                        newChiefSelect.innerHTML = '<option value="">Select Chief</option>';
                        editChiefSelect.innerHTML = '<option value="">Select Chief</option>';
                        
                        // Add new options
                        data.data.forEach(chief => {
                            const newOption = document.createElement('option');
                            newOption.value = chief.id;
                            newOption.textContent = `${chief.name} (${chief.email})`;
                            
                            const editOption = newOption.cloneNode(true);
                            
                            newChiefSelect.appendChild(newOption);
                            editChiefSelect.appendChild(editOption);
                        });
                    }
                })
                .catch(error => console.error('Error loading chiefs:', error));
        }

        // Setup event listeners
        function setupEventListeners() {
            // Search functionality
            searchInput.addEventListener('input', function() {
                loadFoyers();
            });

            // Toggle new foyer form
            addFoyerBtn.addEventListener('click', function() {
                newFoyerForm.classList.toggle('hidden');
                addFoyerBtn.innerHTML = newFoyerForm.classList.contains('hidden') 
                    ? '<i class="fas fa-plus h-5 w-5 mr-2"></i> Add New Foyer'
                    : '<i class="fas fa-times h-5 w-5 mr-2"></i> Cancel';
            });

            // Cancel new foyer creation
            cancelCreateBtn.addEventListener('click', function() {
                newFoyerForm.classList.add('hidden');
                addFoyerBtn.innerHTML = '<i class="fas fa-plus h-5 w-5 mr-2"></i> Add New Foyer';
                createFoyerForm.reset();
            });

            // Create new foyer
            createFoyerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                createNewFoyer();
            });

            // Close edit modal
            closeEditModal.addEventListener('click', function() {
                editModal.classList.add('hidden');
            });

            // Cancel edit
            cancelEditBtn.addEventListener('click', function() {
                editModal.classList.add('hidden');
            });

            // Save edits
            editFoyerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                saveEditedFoyer();
            });

            // Close worker modal
            closeWorkerModal.addEventListener('click', function() {
                addWorkerModal.classList.add('hidden');
            });

            // Cancel worker addition
            cancelWorkerBtn.addEventListener('click', function() {
                addWorkerModal.classList.add('hidden');
            });

            // Add worker form submission
            addWorkerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitAddWorker();
            });
        }

        // Render all foyers
        function renderFoyers(foyers, searchTerm = '') {
            const filteredFoyers = foyers.filter(foyer => 
                foyer.name.toLowerCase().includes(searchTerm) ||
                foyer.description?.toLowerCase().includes(searchTerm) ||
                foyer.chief?.name.toLowerCase().includes(searchTerm)
            );

            foyersContainer.innerHTML = '';

            if (filteredFoyers.length === 0) {
                foyersContainer.innerHTML = `
                    <div class="text-center py-12 bg-white rounded-lg shadow">
                        <p class="text-gray-500 text-lg">No foyers found matching your search criteria.</p>
                        ${searchTerm ? '<button onclick="clearSearch()" class="mt-4 text-indigo-600 hover:text-indigo-800">Clear search</button>' : ''}
                    </div>
                `;
                return;
            }

            filteredFoyers.forEach(foyer => {
                const foyerCard = document.createElement('div');
                foyerCard.className = 'bg-white shadow-md rounded-lg overflow-hidden';
                foyerCard.id = `foyer-${foyer.id}`;

                // Foyer Header
                foyerCard.innerHTML = `
                    <div class="bg-white p-6 flex justify-between items-center border-b">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">${foyer.name}</h3>
                            <p class="text-gray-600">${foyer.description || 'No description'} • Status: ${foyer.stat ? 'Active' : 'Inactive'}</p>
                        </div>
                        
                        <div class="flex space-x-2">
                            <button
                                onclick="toggleFoyerExpand(${foyer.id})"
                                class="p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors"
                                aria-label="Expand foyer details"
                            >
                                <i class="fas ${expandedFoyers.has(foyer.id) ? 'fa-chevron-up' : 'fa-chevron-down'} h-5 w-5"></i>
                            </button>
                            <button
                                onclick="editFoyer(${foyer.id})"
                                class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-full transition-colors"
                                aria-label="Edit foyer"
                            >
                                <i class="fas fa-edit h-5 w-5"></i>
                            </button>
                            <button
                                onclick="deleteFoyer(${foyer.id})"
                                class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full transition-colors"
                                aria-label="Delete foyer"
                            >
                                <i class="fas fa-trash-alt h-5 w-5"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Chief Information -->
                    <div class="px-6 py-4 bg-indigo-50 border-b">
                        <div class="flex items-center">
                        
   
<img src="${foyer.chief.profile_picture ? '/storage/' + foyer.chief.profile_picture : '/images/default-avatar.png'}"
     alt="Chief Picture"
     class="h-12 w-12 mr-4  rounded-full object-cover">

        

                           
                            <div>
                                <div class="text-sm text-indigo-600 font-medium">Le Chef</div>
                                <div class="text-gray-800 font-medium">${foyer.chief?.name || 'Not assigned'}</div>
                                <div class="text-gray-600 text-sm">${foyer.chief?.phone || ''}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Expandable Content -->
                    <div class="expandable-content ${expandedFoyers.has(foyer.id) ? '' : 'hidden'}">
                        <!-- Workers List -->
                        <div class="px-6 py-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-md font-medium text-gray-800">Workers</h4>
                                <button 
                                    onclick="openAddWorkerModal(${foyer.id})" 
                                    class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center"
                                >
                                    <i class="fas fa-user-plus h-4 w-4 mr-1"></i> Add Worker
                                </button>
                            </div>
                            
                            <div id="workers-list-${foyer.id}" class="mt-2 divide-y divide-gray-200">
                                ${renderWorkersList(foyer.workers || [])}
                            </div>
                        </div>
                    </div>
                `;

                foyersContainer.appendChild(foyerCard);
            });
        }

        // Render workers list for a foyer
function renderWorkersList(workers, foyerId) {
    if (workers.length === 0) {
        return '<p class="text-gray-500 text-sm py-2">No workers assigned to this foyer yet.</p>';
    }

    return workers.map(worker => `
        <div class="py-3 flex justify-between items-center">
            <div class="flex items-center">
               
                    
                 <img src="${worker.profile_picture ? '/storage/' + worker.profile_picture : '/images/default-avatar.png'}"alt="Chief Picture"
     class="h-11 w-11 mr-4  rounded-full object-cover">
                   
                <div>
                    <div class="text-gray-800">${worker.name}</div>
                    <div class="text-gray-500 text-sm">${worker.phone}</div>
                </div>
            </div>
            <button 
                onclick="removeWorker(${worker.id}, ${worker.foyer_id})" 
                class="text-red-500 hover:text-red-700 p-1.5 hover:bg-red-50 rounded-full transition-colors"
            >
                <i class="fas fa-times h-4 w-4"></i>
            </button>
        </div>
    `).join('');
}

        // Toggle foyer expansion
        function toggleFoyerExpand(foyerId) {
            const foyerElement = document.getElementById(`foyer-${foyerId}`);
            const expandableContent = foyerElement.querySelector('.expandable-content');
            const toggleIcon = foyerElement.querySelector('.fa-chevron-down, .fa-chevron-up');
            
            expandableContent.classList.toggle('hidden');
            
            if (expandableContent.classList.contains('hidden')) {
                toggleIcon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                expandedFoyers.delete(foyerId);
            } else {
                toggleIcon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                expandedFoyers.add(foyerId);
            }
        }

        // Clear search
        function clearSearch() {
            searchInput.value = '';
            loadFoyers();
        }

       

// Replace the createNewFoyer function with this version:
function createNewFoyer() {
    const formData = new FormData(createFoyerForm);
    
    fetch("{{ route('foyers.store') }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            // Success - reset form and hide it
            createFoyerForm.reset();
            newFoyerForm.classList.add('hidden');
            addFoyerBtn.innerHTML = '<i class="fas fa-plus h-5 w-5 mr-2"></i> Add New Foyer';
            
            // Show SweetAlert for success and then reload the page
            Swal.fire({
                title: 'Success!',
                text: 'Foyer created successfully!',
                icon: 'success',
                confirmButtonText: 'Great!',
                confirmButtonColor: '#4f46e5' // indigo-600 to match your theme
            }).then((result) => {
                // When the user clicks the confirmation button, reload the page
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
        } else {
            // Error
            Swal.fire({
                title: 'Error!',
                text: data.message || 'Failed to create foyer.',
                icon: 'error',
                confirmButtonText: 'Ok',
                confirmButtonColor: '#4f46e5'
            });
        }
    })
    .catch(error => {
        console.error('Error creating foyer:', error);
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred while creating the foyer.',
            icon: 'error',
            confirmButtonText: 'Ok',
            confirmButtonColor: '#4f46e5'
        });
    });
}

        // Open edit foyer modal
function editFoyer(foyerId) {
    console.log("Edit function called for foyer ID:", foyerId);
    
    fetch(`{{ url('foyers') }}/${foyerId}/edit`)
        .then(response => {
            console.log("Response received:", response);
            return response.json();
        })
        .then(data => {
            console.log("Data received:", data);
            if (data.status) {
                const foyer = data.data;
                
                // Fill the form
                document.getElementById('edit-foyer-id').value = foyer.id;
                document.getElementById('edit-foyer-name').value = foyer.name;
                document.getElementById('edit-foyer-description').value = foyer.description || '';
                document.getElementById('edit-foyer-status').value = foyer.stat;
                document.getElementById('edit-foyer-chief').value = foyer.chief_id || '';
                
                // Show the modal
                editModal.classList.remove('hidden');
                console.log("Modal should be visible now");
            } else {
                console.error("Error in response:", data.message);
                toastr.error(data.message || 'Failed to load foyer data.');
            }
        })
        .catch(error => {
            console.error('Error loading foyer data:', error);
            toastr.error('An error occurred while loading foyer data.');
        });
}

        // Save edited foyer
function saveEditedFoyer() {
    const foyerId = document.getElementById('edit-foyer-id').value;
    const formData = new FormData(editFoyerForm);
    
    // Debug what's being sent
    console.log("Form data being sent:");
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    // Add this for Laravel to process it as PUT
    formData.append('_method', 'PUT');
    
    fetch(`{{ url('foyers') }}/${foyerId}`, {
        method: 'POST', // Keep as POST since we're using _method field
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            // Success
            Swal.fire({
                title: 'Success!',
                text: 'Foyer updated successfully!',
                icon: 'success',
                confirmButtonText: 'Great!',
                confirmButtonColor: '#4f46e5'
            }).then(() => {
                editModal.classList.add('hidden');
                window.location.reload(); // Reload to see changes
            });
        } else {
            // Error
            Swal.fire({
                title: 'Error!',
                text: data.message || 'Failed to update foyer.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4f46e5'
            });
        }
    })
    .catch(error => {
        console.error('Error updating foyer:', error);
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred while updating the foyer.',
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#4f46e5'
        });
    });
}

        // Delete foyer
function deleteFoyer(foyerId) {
    // Use SweetAlert for confirmation instead of default confirm
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed, proceed with deletion
            fetch(`{{ url('foyers') }}/${foyerId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    // Success
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Foyer has been deleted successfully.',
                        icon: 'success',
                        confirmButtonColor: '#4f46e5'
                    }).then(() => {
                        // Refresh the page
                        window.location.reload();
                    });
                } else {
                    // Error
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to delete foyer.',
                        icon: 'error',
                        confirmButtonColor: '#4f46e5'
                    });
                }
            })
            .catch(error => {
                console.error('Error deleting foyer:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while deleting the foyer.',
                    icon: 'error',
                    confirmButtonColor: '#4f46e5'
                });
            });
        }
    });
}

        // Open add worker modal
function openAddWorkerModal(foyerId) {
    document.getElementById('worker-foyer-id').value = foyerId;
    
    // Load available users
    fetch(`{{ url('foyers') }}/${foyerId}/available-users`)
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                const userSelect = document.getElementById('worker-user-id');
                
                // Clear existing options
                userSelect.innerHTML = '<option value="">Select User</option>';
                
                // Add new options
                data.data.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = `${user.name} (${user.email})`;
                    userSelect.appendChild(option);
                });
                
                // Show the modal
                addWorkerModal.classList.remove('hidden');
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'Failed to load available users.',
                    icon: 'error',
                    confirmButtonColor: '#4f46e5'
                });
            }
        })
        .catch(error => {
            console.error('Error loading available users:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred while loading available users.',
                icon: 'error',
                confirmButtonColor: '#4f46e5'
            });
        });
}

        // Submit add worker form
 function submitAddWorker() {
    const formData = new FormData(addWorkerForm);
    const foyerId = document.getElementById('worker-foyer-id').value;
    
    fetch(`{{ url('foyers') }}/${foyerId}/workers`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            // Success
            Swal.fire({
                title: 'Success!',
                text: 'Worker added successfully!',
                icon: 'success',
                confirmButtonColor: '#4f46e5'
            }).then(() => {
                addWorkerModal.classList.add('hidden');
                window.location.reload(); // Reload to see changes
            });
        } else {
            // Error
            Swal.fire({
                title: 'Error!',
                text: data.message || 'Failed to add worker.',
                icon: 'error',
                confirmButtonColor: '#4f46e5'
            });
        }
    })
    .catch(error => {
        console.error('Error adding worker:', error);
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred while adding the worker.',
            icon: 'error',
            confirmButtonColor: '#4f46e5'
        });
    });
}

        // Remove worker
function removeWorker(workerId, foyerId) {
    
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to remove this worker from the foyer?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, remove them!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ url('foyers') }}/${foyerId}/workers/${workerId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    // Success
                    Swal.fire({
                        title: 'Removed!',
                        text: 'Worker removed successfully!',
                        icon: 'success',
                        confirmButtonColor: '#4f46e5'
                    }).then(() => {
                        window.location.reload(); // Reload to see changes
                    });
                } else {
                    // Error
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to remove worker.',
                        icon: 'error',
                        confirmButtonColor: '#4f46e5'
                    });
                }
            })
            .catch(error => {
                console.error('Error removing worker:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while removing the worker.',
                    icon: 'error',
                    confirmButtonColor: '#4f46e5'
                });
            });
        }
    });
}
    </script>
</body>

@endsection