<!-- resources/views/dashboard.blade.php -->

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content-header')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Submit Payment Request</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Submit Payment Request</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <form method="POST" action="{{route('request.create')}}" enctype="multipart/form-data">
                @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group" style="width: 400px;" readonly>
                  <label for="category">Expense account category</label>
                  <select class="form-control" id="category" name="category" onchange="updateSubcategory()" disabled>
                    <option value="default">Default</option>
                    <option value="Current asset">Current asset</option>
                    <option value="Fixed asset">Fixed asset</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group" style="width: 400px;">
                  <label for="subcategory">Payment Category</label>
                  <select class="form-control" id="subcategory" name="subcategory" required>
              <option value="dpdigitalmarketing">DP Digital Marketing</option>
              <option value="dpeducation">DP Education</option>
              <option value="dpitcampus">DP IT Campus</option>
              <option value="dpcode">DP Code</option>
              <option value="dpkids">DP Kids</option>
              <option value="dpuniversity">DP University</option>
              <option value="dpdigitallibrary">DP Digital Library</option>
              <option value="dpofflinemarketing">DP Offline Marketing</option>
              <option value="dpevents">DP Events</option>
              <option value="dptvet">DP TVET Studies</option>
              <option value="dplangscl">DP Language School</option>
              <option value="dpsport">DP Sports</option>
              <option value="dphealth">DP Public Health</option>
              <option value="dpbuddhist">DP Buddhist Studies</option>
              <option value="other">Other</option>
                  </select>
                </div>
              </div>
            </div>  
            <div class="row">
              <div class="col-md-6">
                <div class="form-group" style="width: 400px;">
                    <label for="supplier">Supplier</label>
                    <div class="input-group">

                        <select class="form-control" id="supplier" name="supplier" required>
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $row)
                                <option value="{{ $row['id'] }}">{{ $row['company_name'] }}</option>
                            @endforeach
                        </select>
                        
                        <div class="input-group-append">
                            <a class="btn btn-primary" href="{{route('supplier.create')}}" target="_blank">+</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group" id="supplier-accounts-container" style="display: none;">
                    <label for="account">Accounts</label>
                    <select class="form-control" id="account" name="account" required>
                        <option value="">Select Account</option>
                        <!-- Accounts will be populated dynamically -->
                    </select>
                </div>
            </div>
            </div>
          

            <div class="form-group">
                  <label>Payable bank details</label>
                  <div class="row">
                    <div class="col">
                      <input type="text" class="form-control" placeholder="Account Name" id="account_name" name="account_name" required readonly>
                    </div>
                    <div class="col">
                      <input type="text" class="form-control" placeholder="Account Number" id="account_number" name="account_number" required readonly>
                    </div>
                    <div class="col">
                      <input type="text" class="form-control" placeholder="Bank Name" id="bank_name" name="bank_name" required readonly>
                    </div>
                    <div class="col">
                      <input type="text" class="form-control" placeholder="Branch" id = "branch" name="branch" required readonly>
                    </div>
                  </div>
            </div>
            
            
          <div class="row">
            <div class="col-md-3">         
              <div class="form-group" style="width: 200px;">
                  <label for="due_date">Due Date</label>
                  <input type="date" class="form-control" id="due_date" name="due_date" style="width: 200px;" readonly>
              </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" style="width: 200px;">
                  <label for="payment_type">Payment Type</label>
                  <select class="form-control" id="payment_type" name="payment_type" required>
                    <option value="Final Payment">Final Payment</option>
                    <option value="Advanced 1">Advanced 1</option>
                    <option value="Advanced 2">Advanced 2</option>
                    <option value="Advanced 2">Advanced 3</option>
                  </select>
                </div>
            </div>

            <div class="col-md-3">
            <div class="form-group">
      <label for="priority">Priority:</label>
      <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="priority" id="priorityNormal" value="normal" checked>
          <label class="form-check-label" for="priorityNormal">Normal</label>
      </div>
      <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="priority" id="priorityHigh" value="high">
          <label class="form-check-label" for="priorityHigh">High</label>
      </div>
  </div>
            </div>
          </div>

          <div class="form-group">
            <label for="documents">Upload documents</label>
            <div class="dropzone" id="my-dropzone"></div>
        </div>
        
                <div class="form-group" style="width: 200px;">
                  <label for="amount">Amount (Rs.)</label>
                  <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                </div>

                <div class="form-group">
                  <label for="note">Description (Describe the project)</label>
                  <textarea class="form-control" id="note" name="note" rows="3" required></textarea>
                </div>
                <div class="form-group">
                <label for="vender_invoice">Invoice Number</label>
                <input type="text" class="form-control" id="vender_invoice" name="vender_invoice">
              </div>

              <div class="form-group">
                <label for="type">Local or Foreign</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="Local">Local</option>
                    <option value="Foreign">Foreign</option>
                </select>
              </div>

              <div class="form-group">
                <label for="indicator">Indicator</label>
                <input type="text" class="form-control" id="indicator" name="indicator" required>
              </div>

              <div class="form-group">
                <label for="payment_link">Payment Link</label>
                <input type="url" class="form-control" id="payment_link" name="payment_link" placeholder="https://example.com">
              </div>

                <button type="submit" class="btn btn-primary">Submit</button>
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
  <!-- /.content -->
</div>

@endsection

@push('scripts')
<script>

    $(document).ready(function () {
        // Handle supplier selection change
        $('#supplier').change(function () {
            var supplierId = $(this).val();  // Get selected supplier ID

            if (!supplierId) {
                $('#supplier-accounts-container').hide();
                $('#account').html('<option value="">Select Account</option>');  // Clear account options
                return;
            }

            $('#supplier-accounts-container').show();

            $.ajax({
                url: '{{ route('request.supplierAccounts', ['id' => ':id']) }}'.replace(':id', supplierId),  // Replace :id with the selected supplier ID
                type: 'GET',
                success: function (response) {
                    if (response && response.accounts) {
                        var accountsDropdown = $('#account');
                        accountsDropdown.html('<option value="">Select Account</option>');  // Reset dropdown

                        $.each(response.accounts, function (index, account) {
                            accountsDropdown.append('<option value="' + account.id + '">' + account.account_name + '</option>');
                        });
                    } else {
                        $('#account').html('<option value="">No accounts available</option>');
                    }
                },
                error: function () {
                    alert('Error fetching accounts. Please try again.');
                }
            });
        });
    });


    $(document).ready(function () {
    // Handle account selection change
    $('#account').change(function () {
        var accountId = $(this).val();  // Get selected account ID
        if (!accountId) {
            // Clear bank details if no account is selected
            $('#account_name').val('');
            $('#bank_name').val('');
            $('#branch').val('');
            $('#account_number').val('');
            return;
        }

        // Trigger AJAX request to fetch bank details for the selected account
        $.ajax({
            url: '{{ route('request.accountDetails', ['id' => ':id']) }}'.replace(':id', accountId),  // Replace :id with the selected account ID
            type: 'GET',
            success: function (response) {
                if (response && response.bank_details) {
                  console.log("test" , response);
                  $('#account_name').val(response.bank_details.account_name);
                    $('#bank_name').val(response.bank_details.bank_name);
                    $('#branch').val(response.bank_details.branch);
                    $('#account_number').val(response.bank_details.account_number);

                } else {
                    // Clear bank details if no data is returned
                    $('#account_name').val('');
                    $('#bank_name').val('');
                    $('#account_number').val('');
                    $('#branch').val('');
                }
            },
            error: function () {
                alert('Error fetching bank details. Please try again.');
            }
        });
    });
});

</script>
@endpush

