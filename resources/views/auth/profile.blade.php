
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Profile</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
@endsection

@section('content')

<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Profile Information</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <table class="table table-bordered">
  <tbody>
      <tr>
          <th style="width: 30%">Full Name</th>
          <td>{{$user->fname}}</td> 
      </tr>
      <tr>
          <th>Email</th>
          <td>{{$user->email}}</td> 
      </tr>
      <tr>
          <th>Signature</th>
          <td>
            <img src="{{ Storage::url($user->signature) }}" alt="Signature" class="img-fluid mb-4">
            <form method="POST" enctype="multipart/form-data" action="{{ route('auth.profile.signature') }}">
                @csrf
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="inputSignature" name="signature_file" accept="image/*" onchange="updateFileNameLabel()">
                        <label class="custom-file-label" for="inputSignature" id="document-label">Choose file</label>
                    </div>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </div>
            </form>
          </td>
      </tr>
      <tr>
          <th>Access Level</th>
          <td>
           
          </td>
      </tr>
      <tr>
          <th>Last Login</th>
          <td>{{$user->last_login}}</td> 
      </tr>
  </tbody>
</table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

                <!-- Password Change Section -->
                {{-- <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Change Password</h3>
                    </div>
                    <div class="card-body">
                      <form action="{{ route('auth.password') }}" method="POST">
                        @csrf
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                        </form>
                    </div>
                </div> --}}

            </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

@endsection
