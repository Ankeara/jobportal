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
                            <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
                            <li class="breadcrumb-item active">Edit users</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('admin.sidebar')
                </div>
                <div class="col-lg-9">
                    @include('frontend.message')
                    <div class="card border-0 shadow mb-4">
                        <form action="" method="POST" id="userFormAdmin" name="userFormAdmin">
                            @csrf
                            <div class="card-body  p-4">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="fs-4 mb-1">User / Edit</h3>
                                    </div>
                                    <div style="margin-top: -10px;">
                                        <a href="{{ route('admin.users') }}" class="btn btn-primary">Go back</a>
                                    </div>

                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Name*</label>
                                    <input type="text" name="name" id="name" placeholder="Enter Name"
                                        class="form-control" value="{{ old('name', $users->name) }}">
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Email*</label>
                                    <input type="text" placeholder="Enter Email" name="email" id="email"
                                        class="form-control" value="{{ old('email', $users->email) }}">
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Designation*</label>
                                    <input type="text" placeholder="Designation" name="description" id="description"
                                        class="form-control" value="{{ old('description', $users->description) }}">
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Mobile*</label>
                                    <input type="text" placeholder="Mobile" class="form-control" name="mobile"
                                        id="mobile" value="{{ old('mobile', $users->mobile) }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="card-footer  p-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

@section('customJS')
    <script>
        // update profile
        $("#userFormAdmin").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('admin.users.update', $users->id) }}",
                type: 'put',
                dataType: 'json',
                data: $("#userFormAdmin").serializeArray(),
                success: function(response) {
                    if (response.status == true) {
                        $('#name').removeClass("is-invalid").siblings('p').removeClass(
                                "invalid-feedback")
                            .html('')
                        $('#email').removeClass("is-invalid").siblings('p').removeClass(
                                "invalid-feedback")
                            .html('')
                        window.location.href = "{{ url()->current() }}";
                    } else {
                        var errors = response.errors;
                        if (errors.name) {
                            $('#name').addClass("is-invalid").siblings('p').addClass("invalid-feedback")
                                .html(errors.name)
                        } else {
                            $('#name').removeClass("is-invalid").siblings('p').removeClass(
                                    "invalid-feedback")
                                .html('')
                        }

                        if (errors.email) {
                            $('#email').addClass("is-invalid").siblings('p').addClass(
                                    "invalid-feedback")
                                .html(errors.email)
                        } else {
                            $('#email').removeClass("is-invalid").siblings('p').removeClass(
                                    "invalid-feedback")
                                .html('')
                        }

                    }
                }
            })
        })
    </script>
@endsection
