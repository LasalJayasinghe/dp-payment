<!-- resources/views/dashboard.blade.php -->

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Add Suppliers</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Add Suppliers</li>
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
              <form action="{{ route('supplier.create') }}" method="POST" id="addCategoryForm">
                @csrf 
                <div class="form-group">
                  <label for="companyName">Company Name*</label>
                  <input type="text" class="form-control" id="companyName" name="companyName" placeholder="Enter Company Name" required>
                </div>
                <div class="form-group">
                  <label for="supplierName">Supplier Name*</label>
                  <input type="text" class="form-control" id="supplierName" name="supplierName"  placeholder="Enter Supplier Name" required>
                </div>
                <div class="form-group">
                  <label for="email">Email*</label>
                  <input type="email" class="form-control" id="email" name="email"  placeholder="Enter Email"  required>
                </div>
                <div class="form-group">
                  <label for="address">Address*</label>
                  <textarea class="form-control" id="address" name="address" rows="3" required  placeholder="Enter Address" ></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Add Supplier</button>
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
