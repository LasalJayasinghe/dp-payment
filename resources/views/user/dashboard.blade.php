@extends('layouts.app')

@section('title', 'Dashboard')

@section('content-header')
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
                    <th>ID</th>
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
                      </td>
                    </tr>
                  @endforeach
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
              <!-- Modal -->
              <div class="modal fade" id="viewRequestModal" tabindex="-1" aria-labelledby="viewRequestModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="viewRequestModalLabel">Request Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <!-- Request details will be loaded here -->
                        <div id="requestDetails"></div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
              </div>
              <!-- Modal for viewing documents -->
              <div class="modal fade" id="viewDocumentsModal" tabindex="-1" aria-labelledby="viewDocumentsModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="viewDocumentsModalLabel">Documents for Request</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <div class="modal-body">
                              <div id="documentsList">
                                  <!-- Documents will be populated here -->
                              </div>
                          </div>
                          <div class="modal-footer">
                              <!-- Button to open chat history modal -->
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
      </div>
    </div>
</section>
@endsection

@push('scripts')
    <script>
        // Custom JavaScript for the dashboard page
    </script>
@endpush
