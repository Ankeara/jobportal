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
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Job applied</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('components.sidebar')
                </div>
                <div class="col-lg-9">
                    @include('frontend.message')
                    <div class="card border-0 shadow mb-4 p-3">
                        <div class="card-body card-form">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fs-4 mb-1">Jobs applied</h3>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table " id="tblJob">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">Title</th>
                                            <th scope="col">Job Created</th>
                                            <th scope="col">Applicants</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        @if ($jobApplications->isNotEmpty())
                                            @foreach ($jobApplications as $jobApplication)
                                                <tr class="active">
                                                    <td>
                                                        <div class="job-name fw-500">{{ $jobApplication->job->title }}</div>
                                                        <div class="info1">{{ $jobApplication->job->jobType->name }} .
                                                            {{ $jobApplication->job->location }}
                                                        </div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($jobApplication->created_at)->format('d M Y') }}
                                                    </td>
                                                    <td>{{ $jobApplication->job->applications->count() }} Applications</td>
                                                    <td>
                                                        @if ($jobApplication->job->status == 1)
                                                            <div class="job-status text-capitalize">Active</div>
                                                        @else
                                                            <div class="job-status text-capitalize">Block</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="action-dots float-end">
                                                            <a href="#" class="" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('jobDetail', $jobApplication->job_id) }} }}">
                                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                                        View</a></li>
                                                                <li><a class="dropdown-item"
                                                                        onclick="removeAppli('{{ $jobApplication->id }}')"
                                                                        href="#"><i class="fa fa-trash"
                                                                            aria-hidden="true"></i>
                                                                        Remove</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td align="center" colspan="5">
                                                    <p>Job application not found.</p>
                                                </td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                                <div>
                                    {{ $jobApplications->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Delete jobappli Modal -->
    <div class="modal fade" id="removeJobAppModel" tabindex="-1" aria-labelledby="removeJobAppModelLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeJobAppModelLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this jobappli?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmRemoveJob">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customJS')
    <script>
        function removeAppli(id) {
            $('#removeJobAppModel').modal('show');

            // When user confirms deletion
            $('#confirmRemoveJob').click(function() {
                $.ajax({
                    url: "{{ route('account.removeJob') }}",
                    type: 'delete',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}' // Add CSRF token for Laravel POST requests
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Redirect to users page or update UI as needed
                        window.location.href = "{{ route('account.myJobApplication') }}";
                    }
                });
            });
        }
    </script>
@endsection
