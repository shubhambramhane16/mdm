@extends('admin.pages.auth.layout')

@section('content')
    <!--begin::Main-->
    <div class=" vh-100">
        <div class="row h-100 m-0">
            <!-- Left Side - Welcome Section -->
            <div class="col-md-6 welcome-section text-center text-white d-flex flex-column justify-content-center">
                <h1>Welcome to {{ config('app.name') }}</h1>
                {{-- <h1 class="mb-3">Avana One</h1> --}}
                {{-- <p class="mb-3">Welcome to Avana One!</p> --}}
                <p class="profile-para">

                    An Exclusive and Automated One Stop Portal For All Your Sales and Order Management!.</p>
            </div>

            <!-- Right Side - Login Section -->

            <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
             <!--begin::Content body-->
             <div class="d-flex flex-column-fluid flex-center">
                 <!--begin::Signin-->
                 <div class="login-form login-signin">
                     <!--begin::Form-->
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="pb-13 pt-lg-0 pt-5">
                            <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">{{ config('app.name') }}</h3>
                            <span class="text-muted font-size-h4">The best in class give their entire team <br>visibility through our business intelligence.</span>
                        </div>

                        <div class="form-group">
                            @error('email')
                            <div class="alert alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror

                            @error('error')
                            <div class="alert alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                            @if(request('error'))
                            <div class="alert alert-danger" role="alert">
                                <strong>{{ request('error') }}</strong>
                            </div>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="font-size-h6 font-weight-bolder text-dark">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control form-control-solid h-auto py-6 px-6 rounded-lg @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="font-size-h6 font-weight-bolder text-dark">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control form-control-solid h-auto py-6 px-6 rounded-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password-confirm" class="font-size-h6 font-weight-bolder text-dark">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control form-control-solid h-auto py-6 px-6 rounded-lg" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="pb-lg-0 pb-5">
                            <button type="submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </form>
                     <!--end::Form-->
                 </div>
                 <!--end::Signin-->
                 <!--begin::Signup-->

                 <!--end::Signup-->
                 <!--begin::Forgot-->

                 <!--end::Forgot-->
             </div>
             <!--end::Content body-->
             <!--begin::Content footer-->

             <!--end::Content footer-->
         </div>
    </div>
</div>
<!--end::Main-->
@endsection

{{-- Styles Section --}}
@section('styles')
<style>

    :root {
        --project-theme-color: {{env('project_theme_color', '#0e4c83')}};
    }
    .welcome-section {
        background-color: var(--project-theme-color) !important;
        /* Red background */
        padding: 50px;
        /* background-image: url({{ asset('/media/custom/login-bg.png') }}); */
        /* Use a background image for effect */
        background-size: cover;
        background-repeat: no-repeat;
    }

    .welcome-section h1 {
        font-size: 48px;
        font-weight: bold;
    }

    .welcome-section p {
        font-size: 18px;
        margin-top: 20px;
        color: #fff;
    }

    .profile-para {
        font-size: 14px !important;
    }

    /* Login Section */
    .login-section {
        background-color: #f9f9f9;
        /* Light gray background */
    }

    .login-container {
        border-radius: 10px;
        padding: 30px;
        width: 100%;
    }

    .login-container img {
        max-width: 150px;
    }

    .input-group-text {
        background-color: transparent;
        border: none;
    }

    .input-group-text i {
        font-size: 24px;
        color: var(--project-theme-color);
    }

    .btn-danger {
        background-color: var(--project-theme-color);
        border-color: var(--project-theme-color);
    }

    .btn-danger i {
        margin-left: 10px;
    }

    .submit-btn {
        width: 100%;
        display: flex;
        justify-content: center;
        margin: auto;
        align-items: center;
        text-align: center;
        margin-top: 4rem !important;

    }

    .submit-btn button {
        padding: 10px 40px;
        background-color: var(--project-theme-color);
        color: #fff !important;

    }

    .submit-btn button a {
        color: #fff !important;

    }
</style>
@endsection


{{-- Scripts Section --}}
@section('scripts')
{{-- vendors --}}

<script src="{{ url('/') }}/js/custom.js" type="text/javascript"></script>
{{-- page scripts --}}
@endsection
