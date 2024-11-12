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
                                            <td>{{ $request->supplier_name }}</td>
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
@endsection
