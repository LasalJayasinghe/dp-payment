<!-- resources/views/dashboard.blade.php -->

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Add Account</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Add Account</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')

<section class="content">
    <div class="container-fluid">
      <div class="row">
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
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>

@endsection
