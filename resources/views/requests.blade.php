@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="m-0 text-dark">Requests</h1>
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
            {{-- @forelse ($requests as $request)
                <tr data-request-id="{{ $request->id }}">
                    <td>{{ $request->id }}</td>
                    <td>{{ $request->subcategory }}</td>
                    <td>{{ $request->supplier_name }}</td>
                    <td>{{ number_format($request->amount, 2) }}</td>
                    <td>{{ $request->created_at }}</td>
                    <td>{{ $request->due_date }}</td>
                    <td>
                        <span class="badge badge-{{ $request->status }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td><button onclick="viewDocument({{ $request->id }})" class="btn btn-info">View</button></td>
                    <td><button onclick="viewRequest({{ $request->id }})" class="btn btn-info">View</button></td>
                    <td>
                        @if (!in_array('approve_users', $requestAccess) || in_array('both', $requestAccess))
                            <button onclick="checkRequest(this)" class="btn btn-info"><i class="fa fa-check"></i></button>
                        @endif
                        @if (in_array('approve_users', $requestAccess))
                            <button onclick="rejectRequest(this)" class="btn btn-danger"><i class="fa fa-ban"></i></button>
                            <button onclick="approveRequest(this)" class="btn btn-warning"><i class="fa fa-check-double"></i></button>
                        @endif
                        <button onclick="viewChat({{ $request->id }})" class="btn btn-info"><i class="fas fa-comments"></i></button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No requests found.</td>
                </tr>
            @endforelse --}}
        </tbody>
    </table>
</div>
@endsection
