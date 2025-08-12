@extends('admin.layout.default')

@section('user','active menu-item-open')
@section('content')
<div class="card card-custom">

    <div class="card-body">
        <div class="mb-7">
            <div class="row align-items-center">

                <form method="POST" action="" class="w-100">
                    {{ csrf_field() }}
                    <div class="col-lg-9 col-xl-12">
                        <div class="row align-items-center">
                            <div class="form-group col-md-6">
                                <label>Name</label>
                                <div><input type="text" name="name" value="{{$details->name}}" isrequired="required" class="form-control" placeholder="Enter User Name"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email/User Name </label>
                                <div><input type="email" name="email" value="{{$details->email }}" isrequired="required" class="form-control" placeholder="Enter Email"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Mobile No.</label>
                                <div><input type="text" name="mobile" value="{{$details->mobile}}" isrequired="required" class="form-control number" placeholder="Enter Mobile Number"></div>
                            </div>
                            <!-- <div class="form-group col-md-6">
                                <label>Password </label>
                                <div><input type="password" name="password" value="{{$details->password}}" isrequired="required" class="form-control" placeholder="Enter Password"></div>
                            </div> -->
                            <div class="form-group col-md-6">
                                <label>Password </label>
                                <div>
                                    <button type="button" class="btn btn-primary " data-toggle="modal"
                                        data-target="#password-change">Change Password
                                    </button>
                                </div>

                            </div>
                            <div class="form-group col-md-6">
                                <label>User Role</label>

                                <select class="form-control" name="role_id" id="role_id" isrequired="required">
                                    <option value="">Select Role</option>
                                    @if($roles = getSystemRoles())
                                    @foreach($roles as $role )
                                    <option value="{{$role->id}}" {{runTimeSelection($details->role_id,$role->id)}}>{{ $role->role }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <center><button class="btn btn-success">Update</button></center>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Styles Section --}}
@section('styles')

@endsection

{{-- Scripts Section --}}
@section('scripts')
@endsection
