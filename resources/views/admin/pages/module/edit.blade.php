@extends('admin.layout.default')

@section('module', 'active menu-item-open')
@section('content')
    <div class="card card-custom">

        <div class="card-body">
            <div class="mb-7">
                <div class="row align-items-center">

                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Module Details
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form method="POST" action="" class="w-100">
                                        {{ csrf_field() }}
                                        <div class="col-lg-9 col-xl-12">
                                            <div class="row align-items-center">

                                                <div class="form-group col-md-6">
                                                    <label>Parent Module</label>
                                                    <select class="form-control" name="parent_id" id="parent_id">
                                                        <option value="">Select Parent Module</option>
                                                        @php
                                                            $modules = \App\Models\Module::where('status', 1)->get();
                                                        @endphp
                                                        @foreach ($modules as $module)
                                                            <option value="{{ $module->id }}"
                                                                {{ old('parent_id', $details->parent_id ?? '') == $module->id ? 'selected' : '' }}>
                                                                {{ $module->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-6 d-flex justify-content-end align-items-start">
                                                    <a href="javascript:void(0)"
                                                        onClick="publishModule({{ $details->id }})" class="btn btn-info"
                                                        data-toggle="tooltip"
                                                        title="Publish Module"><i class="la la-check"></i> Publish</a>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>Module Name</label>
                                                    <div>
                                                        <input type="text" name="name"
                                                            value="{{ old('name', $details->name ?? '') }}"
                                                            isrequired="required" class="form-control"
                                                            placeholder="Enter Module Name">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>Module Slug</label>
                                                    <div>
                                                        <input type="text" name="slug"
                                                            value="{{ old('slug', $details->slug ?? '') }}"
                                                            isrequired="required" class="form-control"
                                                            placeholder="Enter Module Slug">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>Module Icon</label>
                                                    <div>
                                                        <input type="text" name="icon"
                                                            value="{{ old('icon', $details->icon ?? '') }}"
                                                            isrequired="required" class="form-control"
                                                            placeholder="Enter Module Icon">
                                                    </div>
                                                </div>

                                                {{-- is left menu --}}
                                                <div class="form-group col-md-3">
                                                    <div class="form-check form-switch mt-2">
                                                        <input type="checkbox" name="is_left_menu" class="form-check-input" onchange="leftMenu(this)"
                                                            id="is_left_menu"
                                                            value="1"
                                                            {{ old('is_left_menu', $details->is_left_menu ?? 0) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_left_menu">Enable Left Menu</label>
                                                    </div>
                                                </div>

                                                {{-- is accordion --}}
                                                <div class="form-group col-md-3">
                                                    <div class="form-check form-switch mt-2">
                                                        <input type="checkbox" name="is_accordion" class="form-check-input" id="is_accordion" onchange="accordion(this)"
                                                            value="1"
                                                            {{ old('is_accordion', $details->is_accordion ?? 0) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_accordion">Enable Accordion</label>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label>Module Description</label>
                                                    <div>
                                                        <textarea name="description" class="form-control" rows="5" placeholder="Enter Module Description">{{ old('description', $details->description ?? '') }}</textarea>
                                                    </div>
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
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Module Fields
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form method="POST" action="" class="w-100">
                                        {{ csrf_field() }}
                                        <div class="col-lg-9 col-xl-12">
                                            <div class="row align-items-center">

                                                <div class="form-group col-md-12">
                                                    <label>Module Description</label>
                                                    <div>
                                                        <textarea name="description" class="form-control" rows="5" placeholder="Enter Module Description">{{ old('description', $details->description ?? '') }}</textarea>
                                                    </div>
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
    <script>
        function leftMenu(checkbox) {
            // call the ajax function to update the left menu status
            var isChecked = checkbox.checked ? 1 : 0;
            var moduleId = {{ request('id') }};
            var url = "{{ url('admin/module/left-menu') }}/" + moduleId;
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_left_menu: isChecked
                },
                success: function(response) {
                    if (response.success) {
                        alert('Left menu status updated successfully!');
                        // Optionally, you can reload the page or update the UI accordingly
                        location.reload();
                    } else {
                        alert('Failed to update left menu status.');
                        // Revert the checkbox state if the update fails
                        checkbox.checked = !checkbox.checked;
                    }
                },
                error: function() {
                    alert('An error occurred while updating the left menu status.');
                    // Revert the checkbox state if the update fails
                    checkbox.checked = !checkbox.checked;
                }
            });
        }
    </script>
    <script>
        function accordion(checkbox) {
            // call the ajax function to update the accordion status
            var isChecked = checkbox.checked ? 1 : 0;
            var moduleId = {{ request('id') }};
            var url = "{{ url('admin/module/accordion') }}/" + moduleId;
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_accordion: isChecked
                },
                success: function(response) {
                    if (response.success) {
                        alert('Accordion status updated successfully!');
                        // Optionally, you can reload the page or update the UI accordingly
                        location.reload();
                    } else {
                        alert('Failed to update accordion status.');
                        // Revert the checkbox state if the update fails
                        checkbox.checked = !checkbox.checked;
                    }
                },
                error: function() {
                    alert('An error occurred while updating the accordion status.');
                    // Revert the checkbox state if the update fails
                    checkbox.checked = !checkbox.checked;
                }
            });
        }
    </script>
    <script>
        function publishModule(moduleId) {
            if (!confirm('Are you sure you want to publish this module?')) {
                return;
            }
            var moduleId = {{ request('id') }};
            var url = "{{ url('admin/module/publish') }}/" + moduleId;
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Module published successfully!');
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
