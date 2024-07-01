@extends('layout.app')

@section('navbar')
    @include('components.navbar')
@endsection

@section('main')
    <section class="section-5">
        <div class="container my-5">
            <div class="py-lg-2">&nbsp;</div>
            @include('frontend.message')
            <div class="row d-flex justify-content-center">
                <div class="col-md-5">
                    <div class="card shadow border-0 p-5">
                        <h1 class="h3">Forget password</h1>
                        <form action="{{ route('account.processForgetPassword') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="mb-2">Email*</label>
                                <input type="text" value="{{ old('email') }}" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="youremail@gmail.com">
                                @error('email')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="justify-content-between d-flex">
                                <button class="btn btn-primary mt-2">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="mt-4 text-center">
                        <p>Do not have an account? <a href="{{ route('account.login') }}">Back to login</a></p>
                    </div>
                </div>
            </div>
            <div class="py-lg-5">&nbsp;</div>
        </div>
    </section>
@endsection
