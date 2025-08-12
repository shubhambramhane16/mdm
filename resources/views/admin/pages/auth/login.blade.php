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
            <div class="col-md-6 d-flex flex-column justify-content-center align-items-center login-section">
                <div class="login-container p-4 rounded">
                    <div class="text-center mb-4">
                        <img src="{{ asset('media/logos/Group20.png') }}" alt="Logo" class="img-fluid">
                    </div>

                    {{-- <h5 class="mb-3">Username / Email</h5> --}}
                    <form class="form" novalidate="novalidate" id="kt_login_signin_form" action="{{url('admin/auth/login')}}" method="post">
                         {{ csrf_field() }}

                         <div class="form-group">
                             @error('email')
                             <div class="alert alert-danger" role="alert">
                                 <strong>{{ $message }}</strong>
                             </div>
                             @enderror
                             @error('password')
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
                             @enderror
                         </div>
                         <div class="form-group col-md-12">
                             <label class="font-size-h6 font-weight-bolder text-dark">Email</label>
                             <input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg" type="text" name="email" autocomplete="off" isrequired="isrequired" />

                         </div>
                         <!--end::Form group-->
                         <!--begin::Form group-->
                         <div class="form-group col-md-12">
                             <div class="d-flex justify-content-between mt-n5">
                                 <label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>

                             </div>
                             <input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg" type="password" name="password" autocomplete="off" isrequired="isrequired" />

                         </div>
                         @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                         <!--end::Form group-->
                         <!--begin::Action-->
                         <div class="form-group col-md-12 center">
                             <button type="submit" id="kt_login_signin_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Sign In</button>
                         </div>
                         <!--end::Action-->
                     </form>
                </div>
            </div>
        </div>
    </div>
    <!--end::Main-->

@endsection

{{-- Styles Section --}}
@section('styles')
    <style>
        .welcome-section {
            background-color: #0e4c83 !important;
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
            color: #0e4c83;
        }

        .btn-danger {
            background-color: #0e4c83;
            border-color: #0e4c83;
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
            background-color: #0e4c83;
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
