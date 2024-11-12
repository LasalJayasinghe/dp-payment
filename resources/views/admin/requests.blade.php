@extends('layouts.app')

@section('title', $heading)

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ $heading }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">{{ $heading }}</li>
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
                                        <th>Subcategory</th>
                                        <th>Supplier Name</th>
                                        <th>Amount</th>
                                        <th>Requested at</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Document</th>
                                        <th>View</th>
                                        <th>Update Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($requests as $request)
                                        <tr data-request-id="{{ $request->id }}">
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $request->id }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $request->subcategory }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $request->supplier_name }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($request->amount, 2) }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $request->created_at }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $request->due_date }}</td>
                                            <td class="px-4 py-2">
                                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $statusClasses[$request->status] ?? 'bg-gray-400 text-gray-800' }}">
                                                    {{ $request->status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2">
                                                <button onclick="viewDocument('{{ $request->id }}')" class="px-3 py-1 text-sm font-medium bg-blue-500 text-white rounded hover:bg-blue-600">
                                                    View
                                                </button>
                                                <td class="px-4 py-2">
                                                    <button onclick="viewRequest({{ $request->id }})" class="px-3 py-1 text-sm font-medium bg-blue-500 text-white rounded hover:bg-blue-600">
                                                        View
                                                    </button>
                                                </td>
                                                
                                            <td class="px-4 py-2">
                                                <div class="flex items-center space-x-3">
                                                    <button onclick="checkRequest(this)" class="px-3 py-2 text-sm bg-green-500 text-white rounded hover:bg-green-600">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <button onclick="rejectRequest(this)" class="px-3 py-2 text-sm bg-red-500 text-white rounded hover:bg-red-600">
                                                        <i class="fa fa-ban"></i>
                                                    </button>
                                                    <button onclick="approveRequest(this)" class="px-3 py-2 text-sm bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                        <i class="fa fa-check-double"></i>
                                                    </button>
                                                    
                                                    <button onclick="viewChat({{ $request->id }})" class="px-3 py-2 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">
                                                        <i class="fas fa-comments"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            
                                            
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

<div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
    <!-- Modal content -->
    <input type="hidden" id="rejectRequestId">
    <textarea id="rejectMessage"></textarea>
    <button onclick="submitRejection()">Submit</button>
</div>


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

@endsection

@push('scripts')
<script>

function viewRequest(requestId) {
    $.ajax({
        url: '{{ route("request.details", ":id") }}'.replace(':id', requestId), // Dynamic URL with the requestId
        type: 'GET',
        success: function(data) {
            $('#requestId').text(data.requestId);
            $('#category').text(data.category);
            $('#subcategory').text(data.subcategory);
            $('#supplier_name').text(data.supplier_name);
            $('#amount').text(data.amount);
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


$('#updateRequestBtn').click(function() {
    const categorySelect = $('#category_select'); // Initialize category select dropdown
    const updatedCategory = categorySelect.val(); // Get the selected category value

    $.ajax({
        url: '{{ route("request.update", ":id") }}'.replace(':id', $('#requestId').text()), // Dynamic URL with request ID
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            category: updatedCategory, // Send the updated category value
        },
        success: function(response) {
            Swal.fire({
                title: 'Success',
                text: 'Request updated successfully.',
                icon: 'success',
                confirmButtonText: 'Ok'
            });
            $('#viewRequestModal').modal('hide');
        },
        error: function() {
            Swal.fire({
                title: 'Error',
                text: 'Unable to update request.',
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        }
    });
});

function checkRequest(button) {
    const requestId = getRequestId(button);
    updateRequestStatus(requestId, 'checked');
}

// Function to handle "Approve" action
function approveRequest(button) {
    const requestId = getRequestId(button);
    updateRequestStatus(requestId, 'approved');
}

// Function to handle "Reject" action (shows modal)
function rejectRequest(button) {
    const requestId = getRequestId(button);
    document.getElementById('rejectRequestId').value = requestId; // Set request ID in the modal
    $('#rejectModal').modal('show'); // Show the reject modal
}

// Function to get request ID (adapt as needed based on your data structure)
function getRequestId(button) {
    return button.closest('[data-request-id]').getAttribute('data-request-id');
}

// Function to submit rejection message from modal
function submitRejection() {
    const requestId = document.getElementById('rejectRequestId').value;
    const rejectMessage = document.getElementById('rejectMessage').value;
    updateRequestStatus(requestId, 'rejected', rejectMessage);
    $('#rejectModal').modal('hide'); // Hide the modal after submission
}

// Function to update the request status via AJAX with SweetAlert notifications
function updateRequestStatus(requestId, status, rejectMessage = '') {
    fetch(`{{ route('requests.updateStatus') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            request_id: requestId,
            status: status,
            checked_by: status === 'checked' ? '{{ Auth::id() }}' : null,
            approved_by: status === 'approved' ? '{{ Auth::id() }}' : null,
            rejected_by: status === 'rejected' ? '{{ Auth::id() }}' : null,
            reject_message: rejectMessage
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Success',
                text: 'Request status updated successfully.',
                icon: 'success',
                confirmButtonText: 'Ok'
            });
        } else {
            Swal.fire({
                title: 'Error',
                text: 'Failed to update request status.',
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error',
            text: 'An error occurred while updating request status.',
            icon: 'error',
            confirmButtonText: 'Ok'
        });
        console.error('Error:', error);
    });
}

</script>
@endpush