@extends('layout.app')

@section('navbar')
    @include('components.navbar')
@endsection

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Job</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('admin.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="card border-0 shadow mb-4 p-3">
                        @include('frontend.message')
                        <div class="card-body card-form">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fs-4 mb-1">Jobs</h3>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table " id="tblJob">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Mobile</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        @if ($job->isNotEmpty())
                                            @foreach ($job as $jb)
                                                <tr class="active">
                                                    <td>{{ $jb->id }}</td>
                                                    <td>
                                                        <p>{{ $jb->title }}</p>
                                                        <p>Applicants: {{ $jb->applications->count() }}</p>
                                                    </td>
                                                    <td>{{ $jb->user->name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($jb->created_at)->format('d M Y') }}</td>
                                                    <td>
                                                        <div class="action-dots">
                                                            <a href="#" class="" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('admin.jobs.edit', $jb->id) }}"><i
                                                                            class="fa fa-edit" aria-hidden="true"></i>
                                                                        Edit</a></li>
                                                                <li><a class="dropdown-item"
                                                                        onclick="deleteJob({{ $jb->id }})"
                                                                        href="#"><i class="fa fa-trash"
                                                                            aria-hidden="true"></i>
                                                                        Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>
                                <div>
                                    {{ $job->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteJobModal" tabindex="-1" aria-labelledby="deleteJobModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteJobModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this User?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteJobBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customJS')
    <script>
        function deleteJob(id) {
            $('#deleteJobModal').modal('show');

            // When user confirms deletion
            $('#confirmDeleteJobBtn').click(function() {
                $.ajax({
                    url: "{{ route('admin.jobs.delete') }}",
                    type: 'delete',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}' // Add CSRF token for Laravel POST requests
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Redirect to job page or update UI as needed
                        window.location.href = "{{ route('admin.job') }}";
                    }
                });
            });
        }
    </script>
@endsection
