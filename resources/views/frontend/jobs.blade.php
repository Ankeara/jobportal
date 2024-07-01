@extends('layout.app')

@section('navbar')
    @include('components.navbar')
@endsection

@section('main')
    <section class="section-3 py-5 bg-2 ">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-10 ">
                    <h2>Find Jobs</h2>
                </div>
                <div class="col-6 col-md-2">
                    <div class="align-end">
                        <select name="sort" id="sort" class="form-control">
                            <option value="1" {{ Request::get('sort') == 1 ? 'selected' : '' }}>Latest</option>
                            <option value="0" {{ Request::get('sort') == 0 ? 'selected' : '' }}>Oldest</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row pt-5">
                <div class="col-md-4 col-lg-3 sidebar mb-4">
                    <form action="" id="searchForm" name="searchForm">
                        <div class="card border-0 shadow p-4">
                            <div class="mb-4">
                                <h2>Keywords</h2>
                                <input type="text" value="{{ Request::get('keywords') }}" placeholder="Keywords"
                                    name="keywords" id="keywords" class="form-control">
                            </div>

                            <div class="mb-4">
                                <h2>Location</h2>
                                <input type="text" value="{{ Request::get('location') }}" placeholder="Location"
                                    name="location" id="location" class="form-control">
                            </div>

                            <div class="mb-4">
                                <h2>Category</h2>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select a Category</option>
                                    @if ($categories)
                                        @foreach ($categories as $cate)
                                            <option {{ Request::get('category') }} value="{{ $cate->id }}">
                                                {{ $cate->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="mb-4">
                                <h2>Job Type</h2>
                                @if ($jobTypes->isNotEmpty())
                                    @foreach ($jobTypes as $type)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input "
                                                {{ in_array($type->id, $jobTypeArr) ? 'selected' : '' }} name="job_type"
                                                type="checkbox" value="{{ $type->id }}"
                                                id="job-type-{{ $type->id }}">
                                            <label class="form-check-label "
                                                for="job-type-{{ $type->id }}">{{ $type->name }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="mb-4">
                                <h2>Experience</h2>
                                <select name="experience" id="experience" class="form-control">
                                    <option value="">Select experience</option>
                                    <option value="1" {{ Request::get('experience') == 1 ? 'selected' : '' }}>1 Year
                                    </option>
                                    <option value="2" {{ Request::get('experience') == 2 ? 'selected' : '' }}>2 Years
                                    </option>
                                    <option value="3" {{ Request::get('experience') == 3 ? 'selected' : '' }}>3 Years
                                    </option>
                                    <option value="4" {{ Request::get('experience') == 4 ? 'selected' : '' }}>4 Years
                                    </option>
                                    <option value="5" {{ Request::get('experience') == 5 ? 'selected' : '' }}>5 Years
                                    </option>
                                    <option value="6" {{ Request::get('experience') == 6 ? 'selected' : '' }}>6 Years
                                    </option>
                                    <option value="7" {{ Request::get('experience') == 7 ? 'selected' : '' }}>7 Years
                                    </option>
                                    <option value="8" {{ Request::get('experience') == 8 ? 'selected' : '' }}>8 Years
                                    </option>
                                    <option value="9" {{ Request::get('experience') == 9 ? 'selected' : '' }}>9 Years
                                    </option>
                                    <option value="10" {{ Request::get('experience') == 10 ? 'selected' : '' }}>10
                                        Years</option>
                                    <option value="10_plus"
                                        {{ Request::get('experience') == '10_plus' ? 'selected' : '' }}>10+
                                        Years</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg">Search</button>

                        </div>
                    </form>
                </div>

                <div class="col-md-8 col-lg-9 ">
                    <div class="job_listing_area">
                        <div class="job_lists">
                            <div class="row justify-content-center">
                                @if ($job->isNotEmpty())
                                    @foreach ($job as $item)
                                        <div class="col-md-4">
                                            <div class="card border-0 p-3 shadow mb-4">
                                                <div class="card-body">
                                                    <h3 class="border-0 fs-5 pb-2 mb-0">{{ $item->title }}</h3>
                                                    <p>{{ Str::words($item->description, 5) }}</p>
                                                    <div class="bg-light p-3 border">
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                            <span class="ps-1">{{ $item->location }}</span>
                                                        </p>
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                            <span class="ps-1">{{ $item->jobType->name }}</span>
                                                        </p>
                                                        @if (!is_null($item->salary))
                                                            <p class="mb-0">
                                                                <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                                <span class="ps-1">{{ $item->salary }}</span>
                                                            </p>
                                                        @endif
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa-solid fa-code"></i></span>
                                                            <span class="ps-1">{{ $item->keywords }}</span>
                                                        </p>
                                                    </div>

                                                    <div class="d-grid mt-3">
                                                        <a href="{{ route('jobDetail', $item->id) }}"
                                                            class="btn btn-primary btn-lg">Details</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-mb-5 text-center">
                                        <img src="{{ url('assets/images/undraw_Taken_re_yn20.png') }}"
                                            style="width: 250px;" alt="">
                                        <h4 class=" mt-5">Job not found.</h4>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    {{ $job->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJS')
    <script>
        $('#searchForm').submit(function(e) {
            e.preventDefault();

            var url = "{{ route('jobs') }}?";

            var keywords = $("#keywords").val();
            var location = $("#location").val();
            var category = $("#category").val();
            var jobType = $("input:checkbox[name='job_type']:checked").map(function() {
                return $(this).val();
            }).get();
            var experience = $("#experience").val();
            var sort = $("#sort").val();

            if (keywords != '') {
                url += '&keywords=' + keywords;
            }

            if (location != '') {
                url += '&location=' + location;
            }

            if (category != '') {
                url += '&category=' + category;
            }

            if (jobType.length > 0) {
                url += '&jobType=' + jobType;
            }

            if (experience != '') {
                url += '&experience=' + experience;
            }

            url += '&sort=' + sort;

            window.location.href = url;
        });
    </script>
@endsection
