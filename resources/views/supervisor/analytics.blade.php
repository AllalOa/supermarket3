@extends('layouts.appp')

@section('title', 'Analytics')

@section('content')

<style>
    body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
</style>

<div class="max-w-5xl mx-auto bg-white p-6 rounded-xl shadow-lg">
    <h2 class="text-2xl font-semibold text-center text-[#2b2d42] mb-6">Analyse des Ventes</h2>
    
    <!-- Date Filter Form -->
    <form method="GET" action="{{ route('supervisor.analytics') }}" class="flex justify-between mb-6">
        <label class="text-[#2b2d42]">Date de début:
            <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}" class="ml-2 p-2 border border-gray-300 rounded-lg">
        </label>
        <label class="text-[#2b2d42]">Date de fin:
            <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}" class="ml-2 p-2 border border-gray-300 rounded-lg">
        </label>
        <button type="submit" class="bg-[#4361ee] text-white px-4 py-2 rounded-lg">Filtrer</button>
    </form>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-[#4361ee] p-6 rounded-xl text-white">
            <p class="text-sm mb-1">Total des Ventes</p>
            <p class="text-2xl font-bold">
                {{ number_format($transactions->sum('total_sales'), 2, ',', ' ') }} DA
            </p>
        </div>
        <div class="bg-[#4cc9f0] p-6 rounded-xl text-white">
            <p class="text-sm mb-1">Ventes Moyennes</p>
            <p class="text-2xl font-bold">
                {{ number_format($transactions->avg('total_sales'), 2, ',', ' ') }} DA
            </p>
        </div>
        <div class="bg-[#7209b7] p-6 rounded-xl text-white">
            <p class="text-sm mb-1">Meilleur Caissier</p>
            <p class="text-2xl font-bold">
                {{ $transactions->sortByDesc('total_sales')->first()->cashier_name ?? 'N/A' }}
            </p>
        </div>
    </div>

    <!-- Bar Chart -->
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <canvas id="salesChart" class="w-full h-96"></canvas>
    </div>

    <!-- Line Chart (Each Cashier Gets a Curve) -->
    <div class="bg-white p-6 rounded-xl shadow-sm mt-6">
        <canvas id="billsChart" class="w-full h-96"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let transactionsData = @json($transactions);
    let billsData = @json($cashierBills);

    function generateRandomColor() {
        return `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.7)`;
    }

    function loadSalesData() {
        if (!transactionsData.length) {
            alert("Aucune donnée trouvée !");
            return;
        }

        const ctx = document.getElementById("salesChart").getContext("2d");
        const labels = transactionsData.map(item => item.cashier_name);
        const values = transactionsData.map(item => item.total_sales);

        if (window.salesChartInstance) {
            window.salesChartInstance.destroy();
        }

        window.salesChartInstance = new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [{
                    label: "Total des ventes (DA)",
                    data: values,
                    backgroundColor: ["#4361ee", "#4cc9f0", "#7209b7", "#f72585", "#ff9f1c"],
                    borderRadius: 5
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });

        loadBillsChart();
    }

    function groupBillsByCashier(data) {
        let groupedData = {};

        data.forEach(item => {
            let date = new Date(item.created_at).toLocaleDateString("fr-FR");
            let cashier = item.cashier_name;

            if (!groupedData[cashier]) {
                groupedData[cashier] = {};
            }

            if (!groupedData[cashier][date]) {
                groupedData[cashier][date] = 0;
            }

            groupedData[cashier][date] += item.total;
        });

        return groupedData;
    }

    function loadBillsChart() {
        if (!billsData.length) {
            alert("Aucune donnée de facture trouvée !");
            return;
        }

        const ctx = document.getElementById("billsChart").getContext("2d");
        const groupedData = groupBillsByCashier(billsData);

        let allDates = [...new Set(billsData.map(item => new Date(item.created_at).toLocaleDateString("fr-FR")))].sort();

        let datasets = Object.keys(groupedData).map(cashier => {
            let salesData = allDates.map(date => groupedData[cashier][date] || 0);
            return {
                label: cashier,
                data: salesData,
                borderColor: generateRandomColor(),
                backgroundColor: "transparent",
                borderWidth: 2,
                fill: false
            };
        });

        if (window.billsChartInstance) {
            window.billsChartInstance.destroy();
        }

        window.billsChartInstance = new Chart(ctx, {
            type: "line",
            data: {
                labels: allDates,
                datasets: datasets
            },
            options: { 
                responsive: true, 
                plugins: { legend: { display: true } } 
            }
        });
    }

    window.onload = loadSalesData;
</script>

@endsection
