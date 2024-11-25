@extends('layouts.app')

@section('title', 'Dashboard')

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Request ID</th>
                    <th>Subcategory</th>
                    <th>Supplier Name</th>
                    <th>Amount</th>
                    <th>Requested at</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Document</th>
                    <th>View Request</th>
                  </tr>
                </thead>
                <tbody>
                @if($requests->isNotEmpty())
                  @foreach($requests as $row)
                    <tr>
                      <td>{{ $row->id }}</td>
                      <td>{{ $row->subcategory }}</td>
                      <td>{{ $row->supplier_name }}</td>
                      <td>{{ number_format($row->amount, 2) }}</td>
                      <td>{{ $row->created_at }}</td>
                      <td>{{ $row->due_date }}</td>
                      <td>
                          @php
                          $status = $row->status;
                          $class = '';
                          switch ($status) {
                              case 'pending':
                                  $class = 'badge badge-secondary';
                                  break;
                              case 'checked':
                                  $class = 'badge badge-info';
                                  break;
                              case 'waiting_for_signature':
                                  $class = 'badge badge-warning';
                                  break;
                              case 'approved':
                                  $class = 'badge badge-success';
                                  break;
                              case 'rejected':
                                  $class = 'badge badge-danger';
                                  break;
                              default:
                                  $class = '';
                                  break;
                          }
                          @endphp
                          <span class="{{ $class }}">{{ $status }}</span>
                      </td>
                      <td><button onclick="viewDocument('{{ $row->id }}')" class="btn btn-info">View</button></td>
                      <td>
                        <button onclick="viewRequest({{ $row->id }})" class="btn btn-info">View</button>
                        <button onclick="viewChat({{ $row->id }})" class="btn btn-info">
                            <i class="fas fa-comments"></i> <!-- Font Awesome icon -->
                        </button>
                        <a href="{{ route('payment-request.pdf', ['requestId' => $row->id]) }}" target="_blank">
                            <button class="btn btn-info">
                                <i class="fas fa-download"></i> <!-- Download Icon -->
                            </button>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                  <x-request-details-modal :id="$row" />

                @else
                  <tr>
                      <td colspan="10">
                          <div id="lottie-container">
                              <div id="lottie-animation"></div>
                              <p>There are no pending requests at this time.</p>
                          </div>
                      </td>
                  </tr>
                @endif
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
      </div>
    </div>
</section>

<div id="documentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50">
  <div class="bg-white rounded-lg shadow-lg w-3/4 max-w-lg">
      <div class="p-4 border-b flex justify-between items-center">
          <h3 class="text-lg font-semibold">Files</h3>
          <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
      </div>
      <div class="p-4">
          <ul id="fileList" class="space-y-2"></ul>
      </div>
      <div class="p-4 border-t text-right">
          <button onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
              Close
          </button>
      </div>
  </div>
</div>
@endsection


@push('scripts')
<script>

function viewDocument(requestId) {
    // Fetch files using AJAX
    fetch(`/files/${requestId}`)
        .then(response => response.json())
        .then(files => {
            const fileList = document.getElementById('fileList');
            fileList.innerHTML = ''; // Clear previous data

            if (files.length === 0) {
                // If no files, show a "No documents" message
                fileList.innerHTML = `<li class="text-center text-gray-500">No documents available</li>`;
            } else {
                files.forEach((file, index) => {
                    const listItem = document.createElement('li');
                    listItem.classList.add('flex', 'justify-between', 'items-center', 'border-b', 'pb-2');
                    listItem.innerHTML = `
                        <span>Document ${index + 1}</span>
                        <button onclick="downloadFile('${file.file_path}')" class="text-blue-500 hover:underline">Download</button>
                    `;
                    fileList.appendChild(listItem);
                });
            }

            // Show the modal
            document.getElementById('documentModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error fetching files:', error);
            alert('Failed to fetch files.');
        });
}

function closeModal() {
    document.getElementById('documentModal').classList.add('hidden');
}

function downloadFile(filePath) {
    const publicPath = `/storage/${filePath}`;
    const link = document.createElement('a');
    link.href = publicPath;
    link.download = filePath.split('/').pop();
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}


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
                    text: 'Chat is currently disabled. ',
                    icon: 'warning',
                    confirmButtonText: 'Close',
                })
            } else if (data.chat === 1) {
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
