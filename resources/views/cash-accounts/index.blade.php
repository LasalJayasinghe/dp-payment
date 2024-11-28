@extends('layouts.app')
@section('title', 'Cash Accounts')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">All Cash Accounts</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">All Accounts</a></li>
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
                        <div class="d-flex flex-row justify-content-end">
                            <div class="pb-4 pt-4 pr-2">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#feed-account">Create Account</button>
                                <div class="modal fade" id="feed-account" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="feed-account" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Create Cash Account</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="cash-account-create-form" action="{{route('create-cash-account')}}" method="post">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-sm-12 col-lg-6 col-xl-6">
                                                            <div class="form-group">
                                                                <label for="account-name">Account Name</label>
                                                                <input type="text" class="form-control" id="account-name" name="account_name">
                                                                {{--                                                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>--}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-lg-6 col-xl-6">
                                                            <div class="form-group">
                                                                <label for="account-number">Account Number</label>
                                                                <input type="text" class="form-control" id="account-number" name="account_number" required>
                                                                {{--                                                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>--}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-lg-6 col-xl-6">
                                                            <div class="form-group">
                                                                <label for="amount">Amount</label>
                                                                <input type="number" class="form-control" id="amount" name="amount" value="0" required>
                                                                {{--                                                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>--}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" onclick="document.getElementById('cash-account-create-form').submit()">Create</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pr-4 pt-4 pb-4">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#funds-transfer">Funds Transfer</button>
                                <div class="modal fade" id="funds-transfer" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="funds-transfer" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Funds Transfer</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="funds-transfer-form" action="{{route('cash-account-funds-transfer')}}" method="post">
                                                    @csrf
                                                    @method('patch')
                                                    <div class="row">
                                                        <div class="col-sm-12 col-lg-6 col-xl-6">
                                                            <div class="form-group">
                                                                <label for="account-name">Account</label>
                                                                <select class="form-control" id="account" name="account">
                                                                        <option value="">Choose Option</option>
                                                                        @foreach($cash_accounts as $key => $account)
                                                                            <option value="{{$account->id}}">{{$account->name}} - {{number_format($account->amount, 2)}}</option>
                                                                        @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-lg-6 col-xl-6">
                                                            <div class="form-group">
                                                                <label for="account-number">Transfer Amount</label>
                                                                <input type="number" class="form-control" id="amount" name="amount" value="0" required>
                                                                {{--                                                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>--}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-lg-6 col-xl-6">
                                                            <div class="form-group">
                                                                <label for="remark">Remark</label>
                                                                <input type="text" class="form-control" id="remark" name="remark" required>
                                                                {{--                                                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>--}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" onclick="document.getElementById('funds-transfer-form').submit()">Transfer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Account Name</th>
                                    <th>Account Number</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($cash_accounts as $account)
                                    <tr>
                                        <td>{{ $account->name }}</td>
                                        <td>{{ $account->account_number }}</td>
                                        <td>{{ number_format($account->amount, 2) }}</td>
                                        <td>{{ $account->status }}</td>
                                        <td>
                                            <button onclick="removeAccount('{{$account->id}}')" class="btn btn-sm btn-danger">Delete</button>
                                            @if($account->status == \App\Models\CashAccount::ACTIVE)
                                                <button onclick="changeStatus('{{$account->id}}', '{{$account->status}}')" class="btn btn-sm btn-warning">Inactive</button>
                                            @else
                                                <button onclick="changeStatus('{{$account->id}}', '{{$account->status}}')" class="btn btn-sm btn-success">Active</button>
                                            @endif
                                            <a href="{{route('cash-account-detail', ['id' => $account->id])}}" class="btn btn-sm btn-secondary">View</a>
                                        </td>
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
    </section>

    <script>
        function removeAccount(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to remove this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const deleteUrl = `{{ route('cash-account-remove', ['id' => ':id']) }}`.replace(':id', id);
                    fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data)
                            if (data.success) {
                                Swal.fire(
                                    'Deleted!',
                                    `${data.message}`,
                                    'success'
                                ).then(() => location.reload());
                            } else {
                                Swal.fire(
                                    'Error!',
                                    data.message || 'An error occurred.',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            Swal.fire(
                                'Error!',
                                'An error occurred.',
                                'error'
                            );
                        });
                }
            });
        }

        function changeStatus(id, status) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to change this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let deleteUrl = `{{ route('cash-account-status-change', ['id' => ':id', 'status' => ':status']) }}`.replace(':id', id);
                    deleteUrl = deleteUrl.replace(':status', status);
                    fetch(deleteUrl, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data)
                            if (data.success) {
                                Swal.fire(
                                    'Deleted!',
                                    `${data.message}`,
                                    'success'
                                ).then(() => location.reload());
                            } else {
                                Swal.fire(
                                    'Error!',
                                    data.message || 'An error occurred.',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            Swal.fire(
                                'Error!',
                                'An error occurred.',
                                'error'
                            );
                        });
                }
            });
        }

    </script>
@endsection
