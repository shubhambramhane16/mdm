@extends('admin.layout.default')

@section('module','active menu-item-open')
@section('content')
<div class="card card-custom">

    <div class="card-body">
        <div class="mb-7">
            <div class="row align-items-center">

                <form method="POST" action="" class="w-100">
                    {{ csrf_field() }}
                    <div class="col-lg-9 col-xl-12">
                        <div class="row align-items-center">

                            <div class="form-group col-md-12">
                                <label>Parent Module</label>
                                <select class="form-control" name="parent_id" id="parent_id">
                                    <option value="">Select Parent Module</option>
                                    @if($modules = App\Models\Module::where('status', 1)->get())
                                    @foreach($modules as $module )
                                    <option value="{{$module->id}}" {{runTimeSelection(old('parent_id'),$module->id)}}>{{ $module->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Module Name</label>
                                <div><input type="text" name="name" value="{{old('name')}}" isrequired="required" class="form-control" placeholder="Enter Module Name"></div>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Module Slug</label>
                                <div><input type="text" name="slug" value="{{old('slug')}}" isrequired="required" class="form-control" placeholder="Enter Module Slug"></div>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Module Icon</label>
                                <div><input type="text" name="icon" value="{{old('icon')}}" isrequired="required" class="form-control" placeholder="Enter Module Icon"></div>
                            </div>




                            <div class="form-group col-md-12">
                                <label>Module Description</label>
                                <div><textarea name="description" class="form-control" rows="20" cols="10" placeholder="Enter Module Description">{{old('description')}}</textarea></div>
                            </div>



                            <div class="form-group col-md-12">
                                <center><button class="btn btn-success">Submit</button></center>
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
<script>
    $(document).ready(function() {
        $('#parent_id').select2({
            placeholder: "Select Parent Module",
            allowClear: true
        });
    });
    // name to slug conversion
    $(document).on('keyup', 'input[name="name"]', function() {
        var name = $(this).val();
        var slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        $('input[name="slug"]').val(slug);
    });
</script>
@endsection
