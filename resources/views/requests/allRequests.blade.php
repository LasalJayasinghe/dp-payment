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
                                        <th>Due Amount</th>
                                        <th>Paid Amount</th>
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
                                            <td>{{ $request->supplierRef?->supplier_name }}</td>
                                            <td>{{ number_format($request->amount, 2) }}</td>
                                            <td>{{ number_format($request->due_amount, 2) }}</td>
                                            <td>{{ number_format($request->paid_amount, 2) }}</td>
                                            <td>{{ $request->created_at }}</td>
                                            <td>{{ $request->due_date }}</td>
                                            <td>
                                                <span class="badge badge-{{ $statusClasses[$request->status] ?? 'secondary' }}">
                                                    {{ $request->status }}
                                                </span>
                                            </td>
                                            <td>{{ $request->checkedRef?->name }}</td>
                                            <td>{{ $request->approvedRef?->name }}</td>
                                            <td>
                                                <div class="flex flex-row space-x-3">
                                                    <button onclick="viewRequest({{ $request->id }})" class="btn btn-info">View</button>
                                                   @if(!$request->requestRef?->is_payment_settled && $request->is_latest && in_array($request->status, [ \App\Models\SubRequest::STATUS_APPROVED, \App\Models\SubRequest::STATUS_REJECTED], TRUE))
                                                        <a href="{{route('request.settle.update', ['id' => $request->id])}}" class="btn btn-primary">Pay Pending Balance</a>
                                                   @endif
                                                </div>
                                            </td>
                                            <x-request-details-modal :id="$request" />

                                        </tr>
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

document.addEventListener('DOMContentLoaded', function() {
        var requestId = document.getElementById('requestId').innerText;

        var pdfLink = document.getElementById('generatePdfBtn');
        pdfLink.href = "{{ route('payment-request.pdf', ['requestId' => ':id']) }}".replace(':id',requestId);
    });
    
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
