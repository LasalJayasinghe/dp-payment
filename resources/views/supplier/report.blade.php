@extends('layouts.app')

@section('title', 'Supplier List')

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Supplier Report</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Supplier Report</li>
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
                <form action="{{ route('supplier.report') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
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
                        
                        <div class="col-md-4">
                            <a href="{{ route('supplier.report.export', request()->query()) }}" class="btn btn-success">
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
                                    <th>ID</th>
                                    <th>Supplier</th>
                                    <th>Account Name</th>
                                    <th>Bank Name</th>
                                    <th>Full Amount</th>
                                    <th>Due Amount</th>
                                    <th>Paid Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($accounts as $account)
                                <tr>
                                    <td>{{ $account->id }}</td>
                                    <td>{{ $account->supplier }}</td>
                                    <td>{{ $account->account_name }}</td>
                                    <td>{{ $account->bank_name }}</td>
                                    <td>{{ $account->total_amount }}</td>
                                    <td>{{ $account->due_amount }}</td>
                                    <td>{{ $account->total_payed_amount }}</td>
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
