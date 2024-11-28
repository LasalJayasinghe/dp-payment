@extends('layouts.app')

@section('title', 'Transaction List')

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Transaction Report</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Transaction Report</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')

<section class="content">
    <div class="container-fluid">
        <!-- Filter Form -->

        <div class="row mb-3">
            <div class="col-12">
            <form action="{{ route('transaction.report') }}" method="GET">
                    <div class="row">
                        <!-- Supplier Filter -->
                        <div class="col-md-3">
                            <label for="supplier-dropdown">Supplier</label>
                            <select 
                                id="supplier-dropdown" 
                                name="supplier_id" 
                                class="form-control"
                                onchange="this.form.submit()">
                                <option value="">All Suppliers</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" 
                                        {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
        
                        <!-- Category Filter -->
                        <div class="col-md-3">
                            <label for="category-dropdown">Category</label>
                            <select 
                                id="category-dropdown" 
                                name="category" 
                                class="form-control"
                                onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                <option value="DP Code">DP Code</option>
                                <option value="DP Kids">DP Kids</option>
                                <option value="DP University">DP University</option>
                                <option value="DP Digital Library">DP Digital Library</option>
                                <option value="DP Offline Marketing">DP Offline Marketing</option>
                                <option value="DP Events">DP Events</option>
                                <option value="DP TVET Studies">DP TVET Studies</option>
                                <option value="DP Language School">DP Language School</option>
                                <option value="DP Sports">DP Sports</option>
                                <option value="DP Public Health">DP Public Health</option>
                                <option value="DP Buddhist Studies">DP Buddhist Studies</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
        
                        <!-- Start Date Filter -->
                        <div class="col-md-3">
                            <label for="start-date">Start Date</label>
                            <input 
                                type="date" 
                                id="start-date" 
                                name="start_date" 
                                class="form-control" 
                                value="{{ request('start_date') }}"
                                onchange="this.form.submit()">
                        </div>
        
                        <!-- End Date Filter -->
                        <div class="col-md-3">
                            <label for="end-date">End Date</label>
                            <input 
                                type="date" 
                                id="end-date" 
                                name="end_date" 
                                class="form-control" 
                                value="{{ request('end_date') }}"
                                onchange="this.form.submit()">
                        </div>
                    </div>
        
                    <div class="row mt-2">
                        <div class="col-md-12 text-left">
                        <a href="{{ route('transaction.report') }}" class="btn btn-secondary">Clear Filters</a>
                        <a href="{{ route('transaction.report.export', request()->query()) }}" class="btn btn-success">
                                Export as CSV
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>        

        <!-- Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Category</th>
                                    <th>Supplier</th>
                                    <th>Requested By</th>
                                    <th>Amount</th>
                                    <th>Check No.</th>
                                    <th>Voucher</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->subcategory }}</td>
                                    <td>{{ $transaction->supplier }}</td>
                                    <td>{{ $transaction->created_user }}</td>
                                    <td>{{ $transaction->paid_amount }}</td>
                                    <td>{{ $transaction->check_no ?? 'Not Given' }}</td>
                                    <td>{{ $transaction->voucher ?? 'Not Given' }}</td>
                                    <td>{{ $transaction->updated_at->format('F j, Y g:i A') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No results found.</td>
                                </tr>
                                @endforelse 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
