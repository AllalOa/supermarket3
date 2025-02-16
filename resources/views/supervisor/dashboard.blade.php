@extends('layouts.appp')

@section('title', 'Supervisor Dashboard')

@section('content')

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        {{-- 📌 Total Cashiers --}}
        <div class="bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md cursor-pointer"
            onclick="openModal('cashiers')">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-[#6c757d]">Total Cashiers</p>
                    <p class="text-3xl font-bold text-[#2b2d42]">{{ $totalCashiers }}</p>
                </div>
                <i class="fas fa-user-tie text-2xl text-[#4361ee]"></i>
            </div>
        </div>

        {{-- 📌 Total Magaziniers --}}
        <div class="bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md cursor-pointer"
            onclick="openModal('magaziniers')">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-[#6c757d]">Total Magaziniers</p>
                    <p class="text-3xl font-bold text-[#2b2d42]">{{ $totalMagaziniers }}</p>
                </div>
                <i class="fas fa-user-cog text-2xl text-[#4361ee]"></i>
            </div>
        </div>

        {{-- 📌 Total Products (Redirects to Inventory) --}}
        <a href="{{ route('supervisor.inventory') }}"
            class="bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-[#6c757d]">Total Products</p>
                    <p class="text-3xl font-bold text-[#2b2d42]">{{ $totalProducts }}</p>
                </div>
                <i class="fas fa-boxes text-2xl text-[#4361ee]"></i>
            </div>
        </a>

        {{-- 📌 Low Stock Items --}}
        <div class="bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md cursor-pointer"
            onclick="openLowStockModal()">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-[#6c757d]">Low Stock Items</p>
                    <p class="text-3xl font-bold text-[#e63946]">{{ $lowStockItems }}</p>
                </div>
                <i class="fas fa-exclamation-triangle text-2xl text-[#ff4d4d]"></i>
            </div>
        </div>

        {{-- 📌 Promoted Users --}}
        <div class=" bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md cursor-pointer"
            onclick="openPromotedUsersModal()">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-[#6c757d]">Promoted Users</p>
                    <p class="text-3xl font-bold text-[#1d3557]">{{ $totalPromotedUsers }}</p>
                </div>
                <i class="fas fa-user-check text-2xl text-[#ff9f1c]"></i>
            </div>
        </div>
        {{-- 🔹 Suspended Users Section --}}
        {{-- 🔹 Suspended Users Card --}}
        <div class="bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md cursor-pointer"
            onclick="openSuspendedUsersModal()">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-[#6c757d]">Suspended Users</p>
                    <p class="text-3xl font-bold text-[#2b2d42]">{{ $totalSuspendedUsers }}</p>
                </div>
                <i class="fas fa-user-slash text-2xl text-[#4361ee]"></i>
            </div>
        </div>
    </div>




    {{-- 🔹 Suspended Users Modal --}}

    </div>
    <div id="suspendedUsersModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg w-2/3 max-w-xl shadow-xl">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Suspended Users</h2>
                <button onclick="closeSuspendedUsersModal()" class="text-gray-600 hover:text-gray-800 text-lg">✖</button>
            </div>
            <div id="suspendedUsersContent" class="max-h-96 overflow-y-auto">
                <p class="text-center text-gray-500">Loading suspended users...</p>
            </div>
        </div>
    </div>
    {{-- 🔹 General Modal for Cashiers & Magaziniers --}}
    <div id="generalModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg w-2/3 max-w-xl shadow-xl">
            <div class="flex justify-between items-center mb-4">
                <h2 id="generalModalTitle" class="text-xl font-semibold text-gray-800"></h2>
                <button onclick="closeGeneralModal()" class="text-gray-600 hover:text-gray-800 text-lg">✖</button>
            </div>
            <div id="generalModalContent" class="max-h-96 overflow-y-auto">
                <p class="text-center text-gray-500">Loading...</p>
            </div>
        </div>
    </div>

    {{-- 🔹 Dedicated Modal for Low Stock Items --}}
    <div id="lowStockModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg w-2/3 max-w-xl shadow-xl">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Low Stock Products</h2>
                <button onclick="closeLowStockModal()" class="text-gray-600 hover:text-gray-800 text-lg">✖</button>
            </div>
            <div class="max-h-96 overflow-y-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="p-3 text-sm font-semibold text-gray-700 uppercase">Image</th>
                            <th class="p-3 text-sm font-semibold text-gray-700 uppercase">Product Name</th>
                            <th class="p-3 text-sm font-semibold text-gray-700 uppercase">Quantity</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($lowStockItem as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-3">
                                    @if($item->product_picture)
                                        <img src="{{ asset('storage/' . $item->product_picture) }}" 
                                             alt="{{ $item->name }}" 
                                             class="w-16 h-16 object-cover rounded-lg shadow-sm">
                                    @else
                                        <img src="{{ asset('images/placeholder.jpg') }}" 
                                             alt="No Image" 
                                             class="w-16 h-16 object-cover rounded-lg shadow-sm">
                                    @endif
                                </td>
                                <td class="p-3 text-sm text-gray-800">{{ $item->name }}</td>
                                <td class="p-3 text-sm text-red-500 font-medium">{{ $item->quantity }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-3 text-center text-gray-500">No low stock items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


        {{-- 🔹 Promoted Users Modal --}}
        <div id="promotedUsersModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg w-2/3 max-w-xl shadow-xl">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Promoted Users</h2>
                    <button onclick="closePromotedUsersModal()" class="text-gray-600 hover:text-gray-800 text-lg">✖</button>
                </div>
                <div id="promotedUsersContent" class="max-h-96 overflow-y-auto">
                    <p class="text-center text-gray-500">Loading...</p>
                </div>
            </div>
        </div>
        <!-- Suspension Modal -->

        <!-- Suspension Modal -->
        <div id="suspendModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg w-2/3 max-w-xl shadow-xl">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Suspend User</h2>
                    <button onclick="closeSuspendModal()" class="text-gray-600 hover:text-gray-800 text-lg">✖</button>
                </div>

                <form action="{{ route('supervisor.suspendUser') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="suspendUserId">

                    <div class="mb-4">
                        <label class="block text-gray-700">Reason for Suspension</label>
                        <textarea name="reason" class="w-full p-2 border rounded-lg" required></textarea>
                    </div>

                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Suspend
                    </button>
                </form>
            </div>
        </div>



        {{-- ✅ JavaScript --}}
        <script>
            // General Modal (For Cashiers & Magaziniers)




            function closeGeneralModal() {
                document.getElementById('generalModal').classList.add('hidden');
            }

            // Dedicated Modal for Low Stock Items
            // Open Low Stock Modal
            function openLowStockModal() {
                document.getElementById('lowStockModal').classList.remove('hidden');
            }

            // Close Low Stock Modal
            function closeLowStockModal() {
                document.getElementById('lowStockModal').classList.add('hidden');
            }


            function openModal(type) {
                let url, title;

                if (type === 'cashiers') {
                    url = "{{ route('supervisor.cashiers') }}";
                    title = "List of Cashiers";
                } else if (type === 'magaziniers') {
                    url = "{{ route('supervisor.magaziniers') }}";
                    title = "List of Magaziniers";
                }

                document.getElementById('generalModalTitle').innerText = title;
                document.getElementById('generalModal').classList.remove('hidden');

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        let content = `<table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b bg-gray-100">
                        <th class="p-2">ID</th>
                        <th class="p-2">Name</th>
                        <th class="p-2">Phone</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>`;

                        data.forEach(item => {
                            content += `
                <tr class="border-b">
                    <td class="p-2">${item.id}</td>
                    <td class="p-2">${item.name}</td>
                    <td class="p-2">${item.phone}</td>
                    <td class="p-2">
                        <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700"
                            onclick="promoteUser(${item.id}, '${type}')">Promote</button>
                        <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700"
                            onclick="openSuspendModal(${item.id})">Suspend</button>
                    </td>
                </tr>`;
                        });

                        content += `</tbody></table>`;
                        document.getElementById('generalModalContent').innerHTML = content;
                    })
                    .catch(error => {
                        document.getElementById('generalModalContent').innerHTML =
                            `<p class="text-red-500">Error loading data</p>`;
                    });
            }








            function closeLowStockModal() {
                document.getElementById('lowStockModal').classList.add('hidden');
            }


            function openPromotedUsersModal() {
                let url = "{{ route('supervisor.promotedUserss') }}";

                document.getElementById('promotedUsersModal').classList.remove('hidden');

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        let content = `<table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b bg-gray-100">
                    <th class="p-2">ID</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Role</th>
                    <th class="p-2">Action</th>
                </tr>
            </thead>
            <tbody>`;

                        // Show only promoted users
                        data.forEach(user => {
                            content += `
                <tr class="border-b bg-yellow-100">
                    <td class="p-2">${user.id}</td>
                    <td class="p-2">${user.name} <span class="text-xs text-gray-500">(Promoted)</span></td>
                    <td class="p-2">${user.role}</td>
                    <td class="p-2">
                        <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700"
                            onclick="demoteUser(${user.id})">Demote</button>
                    </td>
                </tr>`;
                        });

                        content += `</tbody></table>`;
                        document.getElementById('promotedUsersContent').innerHTML = content;
                    })
                    .catch(error => {
                        document.getElementById('promotedUsersContent').innerHTML =
                            `<p class="text-red-500">Error loading data</p>`;
                    });
            }


            function closePromotedUsersModal() {
                document.getElementById('promotedUsersModal').classList.add('hidden');
            }

            function demoteUser(userId) {
                if (!confirm("Are you sure you want to demote this user?")) return;

                fetch("{{ route('supervisor.demoteUser') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            user_id: userId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("User demoted successfully!");
                            openPromotedUsersModal(); // Refresh the modal
                        } else {
                            alert("Failed to demote user: " + data.message);
                        }
                    })
                    .catch(error => {
                        alert("Error demoting user.");
                    });
            }




            function promoteUser(userId, role) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to promote this user to ${role}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, promote!',
                    timer: 3000,

                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('supervisor.promoteUser') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    user_id: userId,
                                    role: role
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {

                                    Swal.fire({
                                        title: 'Promoted!',
                                        text: 'User has been promoted.',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false, // Cache le bouton "OK"

                                    });

                                    openModal(role);
                                } else {
                                    Swal.fire('Error!', 'Failed to promote user: ' + data.message, 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                            });
                    }
                });
            }

            function demoteUser(userId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will demote the user!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, demote!',
                    cancelButtonColor: '#3085d6',
                    timer: 3000,


                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('supervisor.demoteUser') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    user_id: userId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {

                                    Swal.fire({
                                        title: 'Demoted!',
                                        text: 'User has been demoted.',
                                        icon: 'success',
                                        timer: 1500, // Temps en millisecondes (3 secondes)
                                        showConfirmButton: false, // Barre de progression du timer
                                    });
                                    openPromotedUsersModal();
                                } else {
                                    Swal.fire('Error!', 'Failed to demote user: ' + data.message, 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                            });
                    }
                });
            }



            function openSuspendModal(userId) {
                document.getElementById("suspendUserId").value = userId;
                document.getElementById("suspendModal").classList.remove("hidden");
            }

            function closeSuspendModal() {
                document.getElementById("suspendModal").classList.add("hidden");
            }

            function submitSuspension() {
                let userId = document.getElementById("suspendUserId").value;
                let reason = document.getElementById("suspendReason").value;

                if (!userId || !reason) {
                    alert("Please provide a reason!");
                    return;
                }

                let suspendButton = document.querySelector("button[onclick='submitSuspension()']");
                let route = suspendButton.getAttribute("data-route");

                fetch(route, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify({
                            user_id: userId,
                            reason: reason
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: "User Suspended!",
                                text: "This user has been suspended successfully.",
                                icon: "success",
                                timer: 1500,
                                showConfirmButton: false
                            });

                            closeSuspendModal();
                            openModal('cashiers'); // Refresh list
                        } else {
                            Swal.fire("Error!", "Failed to suspend user: " + data.message, "error");
                        }
                    })
                    .catch(error => {
                        Swal.fire("Error!", "Something went wrong.", "error");
                    });
            }



            function loadSuspendedUsers() {
                let url = "{{ route('supervisor.suspendedUsers') }}";

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        let content = '';

                        if (data.length > 0) {
                            data.forEach(user => {
                                content += `
                    <tr class="border-b">
                        <td class="p-2">${user.name}</td>
                        <td class="p-2">${user.role}</td>
                        <td class="p-2">${user.reason}</td>
                        <td class="p-2">${user.suspended_since}</td>
                        <td class="p-2">
                            <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-700"
                                onclick="reinstateUser(${user.id})">Reinstate</button>
                        </td>
                    </tr>`;
                            });
                        } else {
                            content =
                                `<tr><td colspan="5" class="p-2 text-center text-gray-500">No suspended users found.</td></tr>`;
                        }

                        document.getElementById('suspendedUsersContent').innerHTML = content;
                    })
                    .catch(error => {
                        document.getElementById('suspendedUsersContent').innerHTML =
                            `<tr><td colspan="5" class="p-2 text-center text-red-500">Error loading suspended users.</td></tr>`;
                    });
            }

            // Call the function to load suspended users when the page loads
            document.addEventListener('DOMContentLoaded', function() {
                loadSuspendedUsers();
            });


            function reinstateUser(userId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to reinstate this user?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reinstate!',
                    timer: 3000,
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('supervisor.reinstateUser') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    user_id: userId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Reinstated!',
                                        text: 'User has been reinstated.',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false,
                                    });
                                    loadSuspendedUsers(); // Refresh the suspended users list
                                } else {
                                    Swal.fire('Error!', 'Failed to reinstate user: ' + data.message, 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                            });
                    }
                });
            }

            // Open Suspended Users Modal
            function openSuspendedUsersModal() {
                document.getElementById('suspendedUsersModal').classList.remove('hidden');
                loadSuspendedUsers();
            }

            // Close Suspended Users Modal
            function closeSuspendedUsersModal() {
                document.getElementById('suspendedUsersModal').classList.add('hidden');
            }

            // Load Suspended Users
            function loadSuspendedUsers() {
                let url = "{{ route('supervisor.suspendedUsers') }}";

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        let content = '';

                        if (data.length > 0) {
                            content = `<table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-100">
                            <th class="p-2">Name</th>
                            <th class="p-2">Role</th>
                            <th class="p-2">Reason</th>
                            <th class="p-2">Suspended Since</th>
                            <th class="p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>`;

                            data.forEach(user => {
                                content += `
                        <tr class="border-b">
                            <td class="p-2">${user.name}</td>
                            <td class="p-2">${user.role}</td>
                            <td class="p-2">${user.reason}</td>
                            <td class="p-2">${user.suspended_since}</td>
                            <td class="p-2">
                                <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-700"
                                    onclick="reinstateUser(${user.id})">Reinstate</button>
                            </td>
                        </tr>`;
                            });

                            content += `</tbody></table>`;
                        } else {
                            content = `<p class="text-center text-gray-500">No suspended users found.</p>`;
                        }

                        document.getElementById('suspendedUsersContent').innerHTML = content;
                    })
                    .catch(error => {
                        document.getElementById('suspendedUsersContent').innerHTML =
                            `<p class="text-center text-red-500">Error loading suspended users.</p>`;
                    });
            }

            // Reinstate User
            function reinstateUser(userId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to reinstate this user?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reinstate!',
                    timer: 3000,
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('supervisor.reinstateUser') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    user_id: userId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Reinstated!',
                                        text: 'User has been reinstated.',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false,
                                    });
                                    loadSuspendedUsers(); // Refresh the suspended users list
                                } else {
                                    Swal.fire('Error!', 'Failed to reinstate user: ' + data.message, 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                            });
                    }
                });
            }
        </script>

    @endsection
