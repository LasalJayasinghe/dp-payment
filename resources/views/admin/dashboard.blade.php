<!-- resources/views/dashboard.blade.php -->

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3 id="pending-count">{{$pending}}</h3>
                    <p>Pending Requests</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 id="checked-count">{{$checked}}</h3>
                    <p>Checked Requests</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 id="waiting-for-signature-count">{{$waiting}}</h3>
                    <p>Waiting for Signature</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-signature"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3 id="approved-count">{{$approved}}</h3>
                    <p>Approved Requests</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3 id="rejected-count">{{$rejected}}</h3>
                    <p>Rejected Requests</p>
                </div>
                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Top 10 Companies by Expenses</h3>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center" style="max-height: 400px; overflow: hidden;">
                    <canvas id="supplierExpensesChart" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>
    
        <div class="col-md-7">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Monthly Total Expenses</h3>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center" style="max-height: 400px; overflow: hidden;">
                    <canvas id="monthlyApprovedChart" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>
    </div>            
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      $.ajax({
    url: '/chart-data',
    method: 'GET',
    success: function(response) {
        console.log(response);
        const monthlyData = response.monthlyExpenses;

        // Initialize an array to store total expenses for each month (Jan-Dec)
        const totalExpensesByMonth = new Array(12).fill(0);

        // Sum up the expenses for each month
        monthlyData.forEach(expense => {
            totalExpensesByMonth[expense.month - 1] += parseFloat(expense.total); // Add to the corresponding month
        });

        // Prepare chart data
        const chartData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Total Expenses',
                data: totalExpensesByMonth,
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Light green
                borderColor: 'rgba(75, 192, 192, 1)',        // Dark green
                borderWidth: 1
            }]
        };

        // Create the Monthly Expenses Bar Chart
        const ctxBar = document.getElementById('monthlyApprovedChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { enabled: true, mode: 'index', intersect: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Expenses ($)' }
                    }
                }
            }
        });


const suppliers = Object.values(response.suppliers);

                const pieLabels = suppliers.map(supplier => supplier.company_name);
                const pieData = suppliers.map(supplier => parseFloat(supplier.total));

                const predefinedColors = [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                    '#FF9F40', '#66FF66', '#6699FF', '#FF66B2', '#FF6666'
                ];
                const pieColors = pieLabels.map((_, i) => predefinedColors[i % predefinedColors.length]);


                // Prepare Pie Chart data
                const pieChartData = {
                    labels: pieLabels,
                    datasets: [{
                        data: pieData,
                        backgroundColor: pieColors,
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                };

                // Create the Pie Chart
                const ctxPie = document.getElementById('supplierExpensesChart').getContext('2d');
                new Chart(ctxPie, {
                    type: 'pie',
                    data: pieChartData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            tooltip: {
                                enabled: true
                            }
                        }
                    }
                });
            }
        });

    </script>
@endpush
