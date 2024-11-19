@extends('layouts.app')

@section('title', 'Dashboard')

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Add Account</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Add Account</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')

<section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- Left: Add Account Form -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <form action="{{ route('supplier.account') }}" method="POST">
                @csrf 
                <div class="form-group">
                  <label for="supplierId">Supplier</label>
                  <select class="form-control" id="supplierId" name="supplierId" required>
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                      <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                    @endforeach
                  </select>
                </div>                
                <div class="form-group">
                  <label for="accountName">Account Name</label>
                  <input type="text" class="form-control" id="accountName" name="accountName" required>
                </div>
                <div class="form-group">
                  <label for="accountNumber">Account Number</label>
                  <input type="text" class="form-control" id="accountNumber" name="accountNumber" required>
                </div>
                <div class="form-group">
                  <label for="bankName">Bank Name</label>
                  <input type="text" class="form-control" id="bankName" name="bankName" required>
                </div>
                <div class="form-group">
                  <label for="branch">Branch</label>
                  <input type="text" class="form-control" id="branch" name="branch" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Account</button>
              </form>
            </div>
          </div>
        </div>

        <!-- Right: Supplier Accounts Table -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <label for="supplierId">Supplier Accounts</label>
              <table class="table table-bordered" id="accountsTable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Account Name</th>
                    <th>Account Number</th>
                    <th>Bank Name</th>
                    <th>Branch</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="5" class="text-center">Select a supplier to view accounts.</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.getElementById('supplierId').addEventListener('change', function() {
    const supplierId = this.value;

    // Clear the table if no supplier is selected
    if (!supplierId) {
        document.querySelector('#accountsTable tbody').innerHTML = `
          <tr>
            <td colspan="5" class="text-center">Select a supplier to view accounts.</td>
          </tr>`;
        return;
    }

    // Fetch supplier accounts via AJAX
    fetch(`/supplier/${supplierId}/accounts`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#accountsTable tbody');
            tbody.innerHTML = ''; // Clear existing rows

            if (data.length === 0) {
                tbody.innerHTML = `
                  <tr>
                    <td colspan="5" class="text-center">No accounts found for the selected supplier.</td>
                  </tr>`;
            } else {
                data.forEach(account => {
                    tbody.innerHTML += `
                      <tr>
                        <td>${account.id}</td>
                        <td>${account.account_name}</td>
                        <td>${account.account_number}</td>
                        <td>${account.bank_name}</td>
                        <td>${account.branch}</td>
                      </tr>`;
                });
            }
        })
        .catch(error => {
            console.error('Error fetching accounts:', error);
            const tbody = document.querySelector('#accountsTable tbody');
            tbody.innerHTML = `
              <tr>
                <td colspan="5" class="text-center text-danger">Failed to load accounts.</td>
              </tr>`;
        });
});
</script>
@endpush
