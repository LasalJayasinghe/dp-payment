@extends('layouts.app')

@section("All Requests")

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">All Requests</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">All Requests</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Supplier Name</th>
                                        <th>Amount</th>
                                        <th>Requested at</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Checked By</th>
                                        <th>Approved By</th>
                                        <th>View Request</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if($requests->isNotEmpty())
                                    @forelse ($requests as $request)
                                        <tr>
                                            <td>{{ $request->id }}</td>
                                            <td>{{ $request->supplier_name }}</td>
                                            <td>{{ number_format($request->amount, 2) }}</td>
                                            <td>{{ $request->created_at }}</td>
                                            <td>{{ $request->due_date }}</td>
                                            <td>
                                                <span class="badge badge-{{ $statusClasses[$request->status] ?? 'secondary' }}">
                                                    {{ $request->status }}
                                                </span>
                                            </td>
                                            <td>{{ $request->checked }}</td>
                                            <td>{{ $request->approved }}</td>
                                            <td>
                                                <div class="flex flex-row space-x-3">
                                                    <button onclick="viewRequest({{ $request->id }})" class="btn btn-info">View</button>
                                                   @if(!$request->is_payment_settled)
                                                        <button class="p-2 bg-blue-500 text-white rounded" onclick="payPendingAmount({{$request->id}})">Pay Pending Balance</button>
                                                        <div class="modal fade" id="{{$request->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                       <form id="form-{{$request->id}}" method="post">
                                                                           @csrf
                                                                           <div class="row">
                                                                               <div class="col-md-3">
                                                                                   <div class="form-group">
                                                                                       <label for="due_amount">Due Amount</label>
                                                                                       <input type="number" class="form-control" id="due_amount" name="due_amount" value="{{$request->due_amount}}" readonly>
                                                                                   </div>
                                                                               </div>
                                                                           </div>
                                                                           <div class="row">
                                                                               <div class="col-md-3">
                                                                                   <div class="form-group">
                                                                                       <label for="pay_amount">Pay Amount</label>
                                                                                       <input type="number" class="form-control" id="pay_amount" name="pay_amount">
                                                                                   </div>
                                                                               </div>
                                                                           </div>
                                                                       </form>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                   @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <x-request-details-modal
                                        :category="$request->category"
                                        :subcategory="$request->subcategory"
                                        :supplier_name="$request->supplier_name"
                                        :amount="$request->amount"
                                        :status="$request->status"
                                        :requested_date="$request->requested_date"
                                        :requested_by="$request->requested_by"
                                        :due_date="$request->due_date"
                                        :payment_type="$request->payment_type"
                                        :account_name="$request->account_name"
                                        :account_number="$request->account_number"
                                        :bank_name="$request->bank_name"
                                        :note="$request->note"
                                        :document_link="$request->document_link"
                                        />

                                    @empty
                                        <tr>
                                            <td colspan="10">
                                                <div id="lottie-container">
                                                    <div id="lottie-animation"></div>
                                                    <p>No data found.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                @endif
                                </tbody>
                            </table>
                            {{-- @include('requests.partials.view_request_modal')
                            @include('requests.partials.view_document_modal') --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
function payPendingAmount(element){
    $(`#${element}`).modal('show');
}
function viewRequest(requestId) {
    $.ajax({
        url: '{{ route("request.details", ":id") }}'.replace(':id', requestId), // Dynamic URL with the requestId
        type: 'GET',
        success: function(data) {
            console.log(data)
            $('#requestId').text(data.requestId);
            $('#category').text(data.category);
            $('#subcategory').text(data.subcategory);
            $('#supplier_name').text(data.supplier_name);
            $('#amount').text(data.amount);
            $('#dueAmount').text(data.due_amount);
            $('#totalPaid').text(data.total_paid);
            $('#status').text(data.status);
            $('#requested_date').text(data.requested_date);
            $('#requested_by').text(data.requested_by);
            $('#due_date').text(data.due_date);
            $('#payment_type').text(data.payment_type);
            $('#account_name').text(data.account_name);
            $('#account_number').text(data.account_number);
            $('#bank_name').text(data.bank_name);
            $('#note').text(data.note);
            $('#document_link').attr('href', data.document_link); // Set the link for the document

            // Show the modal
            $('#viewRequestModal').modal('show');
        },
        error: function() {
            Swal.fire({
                title: 'Error',
                text: 'Unable to fetch request details.',
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        }
    });
}
</script>

@endsection
