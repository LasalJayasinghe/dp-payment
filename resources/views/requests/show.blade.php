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
                                        <th>Category</th>
                                        <th>Supplier Name</th>
                                        <th>Amount</th>
                                        <th>Requested at</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Document</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($requests as $request)
                                        <tr>
                                            <td>{{ $request->id }}</td>
                                            <td>{{ $request->subcategory }}</td>
                                            <td>{{ $request->supplierRef?->supplier_name }}</td>
                                            <td>{{ number_format($request->amount, 2) }}</td>
                                            <td>{{ $request->created_at }}</td>
                                            <td>{{ $request->due_date }}</td>
                                            <td>
                                                <span class="badge badge-{{ $statusClasses[$request->status] ?? 'secondary' }}">
                                                    {{ $request->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <button onclick="viewDocument('{{ $request->id }}')" class="btn btn-info">View</button>
                                            </td>
                                            <td>
                                                <button onclick="viewRequest({{ $request->id }})" class="btn btn-info">View</button>
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

{{--@if(isset($requests) && $requests->isNotEmpty())--}}
{{--    <x-request-details-modal--}}
{{--        :category="$requests->first()->category"--}}
{{--        :subcategory="$requests->first()->subcategory"--}}
{{--        :supplier_name="$requests->first()->supplier_name"--}}
{{--        :amount="$requests->first()->amount"--}}
{{--        :dueAmount="$request->first()->due_amount"--}}
{{--        :status="$requests->first()->status"--}}
{{--        :requested_date="$requests->first()->requested_date"--}}
{{--        :requested_by="$requests->first()->requested_by"--}}
{{--        :due_date="$requests->first()->due_date"--}}
{{--        :payment_type="$requests->first()->payment_type"--}}
{{--        :account_name="$requests->first()->account_name"--}}
{{--        :account_number="$requests->first()->account_number"--}}
{{--        :bank_name="$requests->first()->bank_name"--}}
{{--        :note="$requests->first()->note"--}}
{{--        :document_link="$requests->first()->document_link"--}}
{{--        :requestId="$requests->first()->id"--}}
{{--        />--}}
{{--@endif--}}

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
    // Prepend /storage/ to the file path
    const publicPath = `/storage/${filePath}`;
    const link = document.createElement('a');
    link.href = publicPath;
    link.download = filePath.split('/').pop(); // Extract file name
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
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
@endpush
