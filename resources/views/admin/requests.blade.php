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
                                                    @if($request->status == "pending")
                                                    <button onclick="checkRequest(this)" class="px-3 py-2 text-sm bg-green-500 text-white rounded hover:bg-green-600">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    @endif

                                                    @if($request->status == "checked")
                                                    <button onclick="waitingForSignature(this)" class="px-3 py-2 text-sm bg-red-500 text-white rounded hover:bg-red-600">
                                                        <i class="fa fa-check"></i>
                                                    </button>

                                                    @endif

                                                    @if($request->status == "waiting_for_signature")
                                                    <button onclick="approveRequest(this)" class="px-3 py-2 text-sm bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                        <i class="fa fa-check-double"></i>
                                                    </button>
                                                    @endif
                                                    
                                                    @if($request->status == "pending" || $request->status == "checked" || $request->status == "waiting_for_signature")
                                                    <button onclick="rejectRequest(this)" class="px-3 py-2 text-sm bg-red-500 text-white rounded hover:bg-red-600">
                                                        <i class="fa fa-ban"></i>
                                                    </button>
                                                    @endif                                            
                                                    
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

<div id="rejectModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <!-- Modal content -->
        <input type="hidden" id="rejectRequestId">
        <textarea id="rejectMessage" class="w-full border border-gray-300 rounded p-2" placeholder="Add a reject message"></textarea>
        <button onclick="submitRejection()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Submit
        </button>
        <button onclick="hideModal()" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Close
        </button>
    </div>
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
                text: 'Request status updated successfully.',
                icon: 'success',
                confirmButtonText: 'Ok'
            }).then(() => {
                window.location.reload(); // Refresh the page
            });

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
function waitingForSignature(button) {
    const requestId = getRequestId(button);
    updateRequestStatus(requestId, 'waiting_for_signature');
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
    document.getElementById('rejectModal').classList.remove('hidden');

}

function hideModal() {
    document.getElementById('rejectModal').classList.add('hidden');
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
    document.getElementById('rejectModal').classList.add('hidden');
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
            signatured_by: status === 'waiting_for_signature' ? '{{ Auth::id() }}' : null,
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
            }).then(() => {
                window.location.reload(); // Refresh the page
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


function viewChat(requestId) {
    // Fetch chat status using AJAX
    $.ajax({
        url: '{{ route("requests.chatStatus", ":id") }}'.replace(':id', requestId), // Replace with your route to get chat status
        type: 'GET',
        success: function (data) {
            if (data.chat === 0) {
                // Chat is disabled, show confirmation to enable chat
                Swal.fire({
                    title: 'Chat Disabled',
                    text: 'Chat is currently disabled. Do you want to enable it?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Enable',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Enable chat
                        enableChat(requestId);
                    }
                });
            } else if (data.chat === 1) {
                // Chat is enabled, show chat popup
                showChatPopup(requestId);
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Unable to fetch chat status.',
                icon: 'error',
                confirmButtonText: 'Ok',
            });
        },
    });
}

function enableChat(requestId) {
    $.ajax({
        url: '{{ route("requests.enableChat", ":id") }}'.replace(':id', requestId), // Replace with your route to enable chat
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}', // Add CSRF token for security
        },
        success: function (data) {
            if (data.success) {
                Swal.fire({
                    title: 'Chat Enabled',
                    text: 'Chat has been enabled successfully.',
                    icon: 'success',
                    confirmButtonText: 'Ok',
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Unable to enable chat.',
                    icon: 'error',
                    confirmButtonText: 'Ok',
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'An error occurred while enabling chat.',
                icon: 'error',
                confirmButtonText: 'Ok',
            });
        },
    });
}

function showChatPopup(requestId) {
    // Replace with your chat UI
    const chatPopupHtml = `
        <div id="chatPopup" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-96 p-4">
                <h3 class="text-lg font-semibold mb-4">Chat</h3>
                <div id="chatMessages" class="border rounded h-64 overflow-y-auto p-2 mb-4">
                    <!-- Chat messages will appear here -->
                </div>
                <textarea id="chatInput" class="w-full border rounded p-2 mb-2" placeholder="Type your message..."></textarea>
                <button onclick="sendChatMessage(${requestId})" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Send</button>
                <button onclick="closeChatPopup()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Close</button>
            </div>
        </div>
    `;
    $('body').append(chatPopupHtml);

    fetch(`/chat/${requestId}`)
        .then(response => response.json())
        .then(data => {
            const chatMessagesDiv = $('#chatMessages');
            chatMessagesDiv.empty(); // Clear loading message

            if (data.success && data.messages.length > 0) {
                data.messages.forEach(message => {
                    chatMessagesDiv.append(`
                        <div class="p-2 bg-gray-100 rounded mb-2">
                            <strong>${message.sender.name}</strong>: ${message.message}
                        </div>
                    `);
                });
            } else {
                chatMessagesDiv.append('<p class="text-gray-500">No messages found.</p>');
            }
        })
        .catch(error => {
            console.error('Error fetching messages:', error);
            $('#chatMessages').html('<p class="text-red-500">Failed to load messages.</p>');
        });
}

function closeChatPopup() {
    $('#chatPopup').remove();
}

function sendChatMessage(requestId) {
    const message = $('#chatInput').val();
    if (!message) {
        Swal.fire({
            title: 'Error',
            text: 'Message cannot be empty.',
            icon: 'error',
            confirmButtonText: 'Ok',
        });
        return;
    }

    // Send chat message via AJAX
    $.ajax({
        url: '{{ route("requests.sendChatMessage", ":id") }}'.replace(':id', requestId), // Replace with your route to send a chat message
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            message: message,
        },
        success: function (data) {
            if (data.success) {
                // Append the message to the chat window
                $('#chatMessages').append(`<div class="mb-2"><strong>${data.userName}:</strong> ${message}</div>`);
                $('#chatInput').val(''); // Clear the input field
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Unable to send message.',
                    icon: 'error',
                    confirmButtonText: 'Ok',
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'An error occurred while sending the message.',
                icon: 'error',
                confirmButtonText: 'Ok',
            });
        },
    });
}

</script>
@endpush