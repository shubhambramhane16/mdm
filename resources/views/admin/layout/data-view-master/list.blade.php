@extends('admin.layout.default')

@section($json['slug'], 'active menu-item-open')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-3 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{ $json['module'] }} List
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                @if (isset($json['sub_url']) && $json['sub_url'])
                    <a href="{{ $json['sub_url'] . '/add/' . request('id') }}" class="btn btn-primary font-weight-bolder">
                        <i class="la la-plus"></i>Add {{ $json['module'] }}
                    </a>
                @else
                    <a href="{{ url('/admin/' . $json['slug'] . '/add') }}" class="btn btn-primary font-weight-bolder">
                        <i class="la la-plus"></i>Add {{ $json['module'] }}
                    </a>
                @endif
                <!--end::Button-->
            </div>
            <form action="" method="get" class="w-100">
                <div class="row col-lg-12 pl-0 pr-0">
                    @if ($json['module'] == 'expense')

                        <div class="col-sm-3">
                            <div class="dataTables_length">
                                <label>Category</label>
                                <select name="category" value="" class="form-control" id="category_id">
                                    <option value="">All Category</option>
                                    @php
                                        $categories = \App\Models\Category::where('parent_id', 0)->get();
                                    @endphp
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @if (request('category') == $category->id) {{ runTimeSelection($category->id, request('category')) }} @endif>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- subcategory --}}
                        <div class="col-sm-3">
                            <div class="dataTables_length">
                                <label>Sub Category</label>
                                <select name="subcategory" value="" class="form-control" id="subcategory_id">
                                    <option value="">All Sub Category</option>
                                    @php
                                        $subcategories = \App\Models\Category::where('parent_id', '!=', 0)->get();
                                    @endphp
                                    @foreach ($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}"
                                            @if (request('subcategory') == $subcategory->id) {{ runTimeSelection($subcategory->id, request('subcategory')) }} @endif>
                                            {{ $subcategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                    @endif
                    @if ($json['module'] == 'invoice')
                        <div class="col-sm-3">
                            <div class="dataTables_length">
                                <label>Status</label>
                                <select name="status" value="" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="1"
                                        @if (request('status') == '1') {{ runTimeSelection('1', request('status')) }} @endif>
                                        Pending</option>
                                    <option value="2"
                                        @if (request('status') == '2') {{ runTimeSelection('2', request('status')) }} @endif>
                                        Approved</option>
                                    <option value="3"
                                        @if (request('status') == '3') {{ runTimeSelection('3', request('status')) }} @endif>
                                        Rejected</option>
                                </select>
                            </div>
                        </div>

                        {{-- daterange --}}
                        <div class="col-sm-3">
                            <div class="dataTables_length">
                                <label>Date Range</label>
                                <input style="width:100%;" type="text" name="fromtodate" id="fromtodate"
                                    class="form-control input-sm" placeholder="Date Range" autocomplete="off"
                                    value="{{ request('fromtodate') }}" readonly>
                            </div>
                        </div>
                    @else
                        <div class="col-sm-3">
                            <div class="dataTables_length">
                                <label>Status</label>
                                <select name="status" value="" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="0"
                                        @if (request('status') == '0') {{ runTimeSelection(0, request('status')) }} @endif>
                                        InActive</option>
                                    <option value="1"
                                        @if (request('status') == '1') {{ runTimeSelection(1, request('status')) }} @endif>
                                        Active</option>
                                </select>
                            </div>
                        </div>
                    @endif

                    <div class="col-sm-2">
                        <div class="dataTables_length">
                            <label cla>&#160; </label>
                            <button type="submit" class="btn btn-success mt-7" data-toggle="tooltip"
                                title="Apply Filter">Filter</button>
                            @if (isset($json['sub_url']) && $json['sub_url'])
                                <a href="{{ $json['sub_url'] . '/' . request('id') }}" class="btn btn-default mt-7"
                                    data-toggle="tooltip" title="Reset Filter">Reset</a>
                            @else
                                <a href="{{ url('/admin/' . $json['slug'] . '/list') }}" class="btn btn-default mt-7"
                                    data-toggle="tooltip" title="Reset Filter">Reset</a>
                            @endif

                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-bordered table-hover" id="myTable">
                <thead>
                    <tr>
                        <th class="custom_sno">SNo.</th>
                        @php
                            $moduleName = $json['module'] ?? 'default';
                        @endphp
                        @foreach ($json['data'] as $field)
                            {{-- <th>{{ $field['label'] }}</th> --}}
                            <th>
                                @php
                                    $translationKey = $moduleName . '/view_list.fields.' . $field['label'];
                                @endphp
                                @if (Lang::has($translationKey))
                                    <label>{{ __($translationKey) }}</label>
                                @else
                                    <label>{{ $field['label'] }}</label>
                                @endif
                        @endforeach
                        </th>
                        <th class="custom_status">Status</th>
                        <th class="custom_action">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($details) > 0)
                        @foreach ($details as $index => $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                @foreach ($json['data'] as $field)
                                    @if (isset($field['relation']) && $field['relation'] != '')
                                        @php
                                            $relationData = $value->{$field['relation']}()->first();
                                        @endphp
                                        <td>
                                            @if ($relationData)
                                                {{ $relationData->{$field['config']['name']} ?? 'N/A' }}
                                            @else
                                                N/Afds
                                            @endif
                                        </td>
                                    @elseif(isset($field['attachment']))
                                        <td>
                                            @if (isset($field['action']['type']) && $field['action']['type'] === 'download' && isset($field['action']['url']))
                                                <a href="{{ url($field['action']['url'], $value->id) }}"
                                                    class="btn btn-sm btn-clean btn-icon" title="Download"
                                                    data-toggle="tooltip" target="_blank">
                                                    <i class="la la-download"></i>
                                                </a>
                                            @elseif (!empty($value[$field['fieldKey']]))
                                                <a href="{{ url($value[$field['fieldKey']]) }}" target="_blank">
                                                    <i class="la la-file"></i>
                                                </a>
                                                <span class="file-name">{{ basename($value[$field['fieldKey']]) }}</span>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    @elseif(isset($field['type']) && $field['type'] == 'date')
                                        <td>
                                            @if (isset($value[$field['fieldKey']]) && $value[$field['fieldKey']] != '')
                                                {{ date('d-m-Y', strtotime($value[$field['fieldKey']])) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>


                                    @else
                                        <td>{{ $value[$field['fieldKey']] }}</td>
                                    @endif
                                @endforeach
                                <td>
                                    @if (isset($json['module']) && $json['module'] == 'invoice')
                                        <a href="javascript:void(0)" onclick="openStatusModal({{ $value->id }});">
                                            <span class="label label-lg font-weight-bold label-light-{{ $value->status == 2 ? 'success' : ($value->status == 3 ? 'danger' : 'warning') }} label-inline">
                                                @if ($value->status == 1)
                                                    Pending
                                                @elseif ($value->status == 2)
                                                    Approved
                                                @elseif ($value->status == 3)
                                                    Rejected
                                                @endif
                                            </span>
                                        </a>

                                        <div class="modal fade" id="statusModal-{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel-{{ $value->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form id="statusForm-{{ $value->id }}">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="statusModalLabel-{{ $value->id }}">Update Status & Comment</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select name="status" class="form-control" id="statusSelect-{{ $value->id }}">
                                                                    <option value="1" @selected($value->status == 1)>Pending</option>
                                                                    <option value="2" @selected($value->status == 2)>Approved</option>
                                                                    <option value="3" @selected($value->status == 3)>Rejected</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-primary" onclick="submitStatus({{ $value->id }})">Update Status</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <script>
                                            function openStatusModal(id) {
                                                $('#statusModal-' + id).modal('show');
                                            }

                                            function submitStatus(id) {
                                                var status = $('#statusSelect-' + id).val();
                                                $.ajax({
                                                    url: "{{ url('admin/invoice/update-status') }}" + '/' + id,
                                                    type: "POST",
                                                    data: {
                                                        _token: '{{ csrf_token() }}',
                                                        id: id,
                                                        status: status
                                                    },
                                                    success: function() {
                                                        $('#statusModal-' + id).modal('hide');
                                                        location.reload();
                                                    },
                                                    error: function() {
                                                        alert('Error updating status');
                                                        $('#statusModal-' + id).modal('hide');
                                                        // location.reload();
                                                    }
                                                });
                                            }
                                        </script>

                                    @else
                                    <a href="javascript:void(0)"
                                        data-url="{{ url('/admin/' . $json['slug'] . '/update-status/' . $value->id . '/' . $value->status) }}"
                                        onclick="changeStatus(this)"> <span
                                            class="label label-lg font-weight-bold label-light-{{ $value->status == 1 ? 'success' : 'danger' }} label-inline">
                                            @if ($json['slug'] == 'expense')
                                                {{ $value->status == 1 ? 'Approve' : 'InApprove' }}
                                            @else
                                                {{ $value->status == 1 ? 'Active' : 'InActive' }}
                                            @endif

                                        </span></a>
                                    @endif
                                </td>
                                <td>
                                    @if (isset($json['sub_url']) && $json['sub_url'])
                                        <a href="{{ $json['sub_url'] . '/edit/' . $value->id }}"
                                            class="btn btn-sm btn-clean btn-icon" title="Edit details"
                                            data-toggle="tooltip">
                                            <i class="la la-edit"></i>
                                        </a>
                                        <a href="{{ $json['sub_url'] . '/delete/' . $value->id }}"
                                            class="btn btn-sm btn-clean btn-icon" title="Delete details"
                                            data-toggle="tooltip"
                                            onclick="return confirm('Are you sure you want to delete this item?');">
                                            <i class="la la-trash"></i>
                                        </a>
                                    @else
                                        <a href="{{ url('/admin/' . $json['slug'] . '/edit/' . $value->id) }}"
                                            class="btn btn-sm btn-clean btn-icon" title="Edit details"
                                            data-toggle="tooltip">
                                            <i class="la la-edit"></i>
                                        </a>
                                        {{-- <a href="{{ url('/admin/' . $json['module'] . '/delete/' . $value->id) }}"
                                            class="btn btn-sm btn-clean btn-icon" title="Delete details"
                                            data-toggle="tooltip"
                                            onclick="return confirm('Are you sure you want to delete this item?');">
                                            <i class="la la-trash"></i>
                                        </a> --}}
                                    @endif
                                    @if ($json['module'] == 'client')
                                        <a href="{{ url('/admin/' . $json['module'] . '/client-units/list/' . $value->id) }}"
                                            class="btn btn-sm btn-clean btn-icon" title="View Units"
                                            data-toggle="tooltip">
                                            <i class="la la-eye"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
            <!--end: Datatable-->
            <div class="d-flex justify-content-end mt-12">
                {{ $details->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </div>


    <script>
        function changeStatus() {
            confirm("Do you want to change status?");
        }
    </script>
@endsection

{{-- Styles Section --}}
@section('styles')
    <!-- <link href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" /> -->
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection


{{-- Scripts Section --}}
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
            $('.dataTables_filter label input[type=search]').addClass('form-control form-control-sm');
            $('#myTable_length').remove();
            $('#myTable_info').remove();
            $('#myTable_paginate').remove();
        });
    </script>
    {{-- vendors --}}
    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js" type="text/javascript"></script>


    <script>
        // get the subcategory based on parent category
        $(document).on('change', 'select[id="category_id"]', function() {
            var parentId = $(this).val();
            var subCategorySelect = $('select[id="subcategory_id"]');
            subCategorySelect.empty();
            subCategorySelect.append('<option value="">Select Sub Category</option>');
            if (parentId) {
                $.ajax({
                    url: '{{ url('/admin/subcategory-list') }}',
                    type: 'GET',
                    data: {
                        category_id: parentId
                    },
                    success: function(data) {
                        subCategorySelect.empty();
                        if (data && Array.isArray(data) && data.length > 0) {
                            $.each(data, function(index, subCategory) {
                                subCategorySelect.append(
                                    '<option value="' + subCategory.id + '">' + subCategory
                                    .name + '</option>'
                                );
                            });
                        } else {
                            subCategorySelect.append(
                                '<option value="">No Sub Categories Found</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        subCategorySelect.empty();
                        subCategorySelect.append(
                            '<option value="">Error fetching subcategories</option>');
                    }
                });
            } else {
                subCategorySelect.append('<option value="">Select Sub Category</option>');
            }
        });
    </script>
@endsection
