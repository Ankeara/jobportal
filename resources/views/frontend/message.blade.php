@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>Error!</strong> {{ Session::get('error') }}
    </div>
@endif

@if (Session::has('success'))
    <div class="alert alert-primary alert-dismissible fade show">
        <strong>Success!</strong> {{ Session::get('success') }}
    </div>
@endif

@if (Session::has('info'))
    <div class="alert alert-info alert-dismissible fade show">
        <strong>Information</strong> {{ Session::get('info') }}
    </div>
@endif
