<!-- Bill Details Modal -->
<div id="billModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Receipt</h2>
            <span class="close-modal" onclick="closeBillModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="receipt">
                <div class="receipt-header">
                    <h2>{{ $user->foyer->name ?? 'Supermarket Pro' }}</h2>
                    <p>{{ $user->foyer->address ?? 'Main Street, City' }}</p>
                    <p>Tel: (123) 456-7890</p>
                </div>
                <div class="receipt-info">
                    <p>Bill #<span id="modalBillId"></span></p>
                    <p>Date: <span id="modalBillDate"></span></p>
                    <p>Cashier: <span id="modalBillCashier">{{ auth()->user()->name }}</span></p>
                </div>
                <table class="receipt-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="modalBillItems">
                        <!-- Receipt items will be added here -->
                    </tbody>
                </table>
                <div class="receipt-summary">
                    <div class="summary-row">
                        <span>Total:</span>
                        <span id="modalTotal">0.00 DA</span>
                    </div>
                </div>
                <div class="receipt-footer">
                    <p>Thank you for shopping with us!</p>
                    <p>Please come again</p>
                </div>
            </div>
            <div class="receipt-actions">
                <button class="btn-print-now">
                    <i class="fas fa-print"></i> Print
                </button>
                <button class="btn-email">
                    <i class="fas fa-envelope"></i> Email
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function showBillDetails(billId) {
        console.log('Fetching bill details for ID:', billId);
        
        // Show loading state
        const modal = document.getElementById('billModal');
        modal.style.display = 'flex';
        modal.classList.add('visible');
        document.getElementById('modalBillItems').innerHTML = '<tr><td colspan="4" class="text-center py-4">Loading...</td></tr>';
        
        fetch(`/bill/${billId}/transactions`)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.error || 'Failed to load bill details');
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Received bill data:', data);
                const billItems = document.getElementById('modalBillItems');
                
                if (!data.bill || !data.transactions) {
                    throw new Error('Invalid bill data received');
                }
                
                // Update bill info
                document.getElementById('modalBillId').textContent = data.bill.id;
                document.getElementById('modalBillDate').textContent = new Date(data.bill.created_at).toLocaleString();
                document.getElementById('modalBillCashier').textContent = data.bill.cashier_name;
                
                // Clear previous items
                billItems.innerHTML = '';
                
                if (data.transactions.length === 0) {
                    billItems.innerHTML = '<tr><td colspan="4" class="text-center py-4">No items found</td></tr>';
                    return;
                }
                
                // Add each transaction to the table
                data.transactions.forEach(transaction => {
                    billItems.innerHTML += `
                        <tr>
                            <td>${transaction.name}</td>
                            <td>${transaction.quantity}</td>
                            <td>${parseFloat(transaction.unit_price).toFixed(2)}</td>
                            <td>${parseFloat(transaction.total).toFixed(2)}</td>
                        </tr>
                    `;
                });
                
                // Update total amount (keep DA only here)
                document.getElementById('modalTotal').textContent = parseFloat(data.bill.total).toFixed(2) + ' DA';
            })
            .catch(error => {
                console.error('Error fetching bill details:', error);
                document.getElementById('modalBillItems').innerHTML = 
                    `<tr><td colspan="4" class="text-center py-4 text-red-500">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        ${error.message}
                    </td></tr>`;
                showToast(error.message, 'error');
            });
    }

    function closeBillModal() {
        const modal = document.getElementById('billModal');
        modal.classList.remove('visible');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('billModal');
        if (event.target == modal) {
            closeBillModal();
        }
    }
</script> 