@extends('layouts.app')
@section('title', 'Cash Accounts Detail')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Cash Accounts Detail</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('cash-accounts')}}">All Accounts</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Detail</a></li>
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
                        <div class="card-body">
                            <div class="col">
                                <div class="col-sm-12 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <label class="form-label">Name:</label>
                                        <span>{{$cash_account->name}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <label class="form-label">Account Number:</label>
                                        <span>{{$cash_account->account_number}}</span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <label class="form-label">Total Amount:</label>
                                        <span>{{number_format($cash_account->amount, 2)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Amount</th>
                                        <th>Remark</th>
                                        <th>Status</th>
                                        <th>Actions By</th>
                                        <th>Recorded At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($cash_account->detailsRef as $log)
                                        <tr>
                                            <td>{{ number_format($log->amount, 2) }}</td>
                                            <td>{{ $log->remark }}</td>
                                            <td>{{ $log->status }}</td>
                                            <td>{{ $log->createdByRef->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($log->created_at)->timezone('Asia/Colombo')->format('Y-m-d H:i:s')}}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No categories available.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
