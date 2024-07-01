@extends('layout.app')

@section('navbar')
    @include('components.navbar')
@endsection

@section('main')
    <section class="section-4 bg-2">
        <div class="container pt-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('jobs') }}"><i class="fa fa-arrow-left"
                                        aria-hidden="true"></i>
                                    &nbsp;Back to Jobs</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container job_details_area">
            <div class="row pb-5">
                <div class="col-md-8">
                    @include('frontend.message')
                    <div class="card shadow border-0">
                        <div class="job_details_header">
                            <div class="single_jobs white-bg d-flex justify-content-between">
                                <div class="jobs_left d-flex align-items-center">

                                    <div class="jobs_conetent">
                                        <a href="#">
                                            <h4>{{ $job->title }}</h4>
                                        </a>
                                        <div class="links_locat d-flex align-items-center">
                                            <div class="location">
                                                <p> <i class="fa fa-map-marker"></i> {{ $job->location }}</p>
                                            </div>
                                            <div class="location">
                                                <p> <i class="fa fa-clock-o"></i> {{ $job->jobType->name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="jobs_right">
                                    <div class="apply_now {{ $count == 1 ? 'saved-job' : '' }}">
                                        <a class="heart_mark" href="#" onClick="saveJob({{ $job->id }})"> <i
                                                class="fa fa-heart-o" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="descript_wrap white-bg">
                            @if (!empty($job->description))
                                <div class="single_wrap">
                                    <h4>Job description</h4>
                                    {!! nl2br($job->description) !!}
                                    {{-- this method use for text ចុះបន្ទាត់ --}}
                                </div>
                            @endif
                            @if (!empty($job->responsibility))
                                <div class="single_wrap">
                                    <h4>Responsibility</h4>
                                    {!! nl2br($job->responsibility) !!}
                                    {{-- this method use for text ចុះបន្ទាត់ --}}
                                </div>
                            @endif
                            @if (!empty($job->qualifications))
                                <div class="single_wrap">
                                    <h4>Qualifications</h4>
                                    {!! nl2br($job->qualifications) !!}
                                    {{-- this method use for text ចុះបន្ទាត់ --}}
                                </div>
                            @endif
                            @if (!empty($job->benefits))
                                <div class="single_wrap">
                                    <h4>Benefits</h4>
                                    {!! nl2br($job->benefits) !!}
                                    {{-- this method use for text ចុះបន្ទាត់ --}}
                                </div>
                            @endif
                            <div class="border-bottom"></div>
                            <div class="pt-3 text-end">
                                @if (Auth::check())
                                    <a href="#" onClick="saveJob({{ $job->id }})"
                                        class="btn btn-secondary">Save</a>
                                @else
                                    <a href="javascript:void(0);" class="btn btn-primary">Login to save</a>
                                @endif
                                @if (Auth::check())
                                    <a href="#" onClick="applyJob({{ $job->id }})"
                                        class="btn btn-primary">Apply</a>
                                @else
                                    <a href="javascript:void(0);" class="btn btn-primary">Login to apply</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (Auth::user())
                        @if (Auth::user()->id == $job->user_id)
                            <div class="card shadow border-0 mt-4">
                                <div class="job_details_header">
                                    <div class="single_jobs white-bg d-flex justify-content-between">
                                        <div class="jobs_left d-flex align-items-center">
                                            <div class="jobs_conetent">
                                                <h4>Applicants</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="descript_wrap white-bg">
                                    <table class="table " id="tblJob">
                                        <thead class="bg-light">
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Mobile</th>
                                                <th scope="col">Applied date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="border-0">
                                            @if ($applications->isNotEmpty())
                                                @foreach ($applications as $application)
                                                    <tr class="active">
                                                        <td>{{ $application->user->name }}</td>
                                                        <td>{{ $application->user->email }}</td>
                                                        <td>{{ $application->user->mobile }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($job->created_at)->format('d M Y') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" align="center">
                                                        <p>Applicants not found.</p>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="card shadow border-0">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Job Summery</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Published on:
                                        <span>{{ \Carbon\Carbon::parse($job->created_at)->format('d, M, Y') }}</span>
                                    </li>
                                    <li>Vacancy: <span> {{ $job->vacancy }}</span></li>
                                    @if (!is_null($job->salary))
                                        <li>Salary: <span>{{ $job->salary }}/span></li>
                                    @else
                                        <li>Salary: <span>None/y</span></li>
                                    @endif
                                    <li>Location: <span>{{ $job->location }}</span></li>
                                    <li>Job Nature: <span> {{ $job->jobType->name }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow border-0 my-4">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Company Details</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Name: &nbsp; &nbsp;<span>{{ $job->company_name }}</span></li>
                                    <li>Locaion: <span>{{ $job->company_location }}</span></li>
                                    <li>Webite: &nbsp;<span><a
                                                href="#">{{ Str::words($job->company_website, 5) }}</a></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection

@section('customJS')
    <script>
        function applyJob(id) {
            if (confirm("Are you sure you want to apply for this job?")) {
                $.ajax({
                    url: "{{ route('applyJob') }}",
                    type: 'post',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}' // Add CSRF token for Laravel
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            // Reload the page if application is successful
                            window.location.reload();
                        } else {
                            // Handle error messages if needed
                            alert(response.message);
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Handle Ajax errors
                        console.log(xhr.statusText);
                    }
                });
            }
        }

        function saveJob(id) {
            $.ajax({
                url: "{{ route('saveJob') }}",
                type: 'post',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}' // Add CSRF token for Laravel security
                },
                dataType: 'json',
                success: function(response) {
                    window.location.href = '{{ url()->current() }}';
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>
@endsection
