@extends('admin.layout.default')

@section('module','active menu-item-open')
@section('content')
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-3 pb-0">
        <div class="card-title">
            <h3 class="card-label">Module List</h3>
        </div>
        <div class="card-toolbar">
            <a href="{{url('/admin/module/add')}}" class="btn btn-primary font-weight-bolder">
                <i class="la la-plus"></i>Add Module</a>
        </div>
        <form action="" method="get" class="w-100">
            <div class="row col-lg-12 pl-0 pr-0">
                <div class="col-sm-3">
                    <div class="dataTables_length">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>InActive</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="dataTables_length">
                        <label>&#160; </label>
                        <button type="submit" class="btn btn-success mt-7" data-toggle="tooltip" title="Apply Filter">Filter</button>
                        <a href="{{url('/admin/module/list')}}" class="btn btn-default mt-7" data-toggle="tooltip" title="Reset Filter">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover" id="myTable">
            <thead>
                <tr>
                    <th class="custom_sno">SNo.</th>
                    <th>Module Name</th>
                    <th>Module Slug</th>
                    <th class="custom_status">Status</th>
                    <th class="custom_action">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($details) > 0)
                    @foreach($details as $key => $value)
                    <tr id="row-{{$value->id}}" data-id="{{$value->id}}" data-sort="{{$loop->iteration}}">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$value->name}}</td>
                        <td>{{$value->slug}}</td>
                        <td>
                            <a href="javascript:void(0)" data-url="{{url('admin/module/update-status/'.$value->id.'/'.$value->status)}}" onclick="changeStatus(this)">
                                <span class="label label-lg font-weight-bold label-light-{{($value->status == 1) ? 'success' : 'danger'}} label-inline">{{($value->status == 1) ? 'Active' : 'InActive'}}</span>
                            </a>
                        </td>
                        <td>
                            <a href="{{url('/admin/module/edit/'.$value->id)}}" class="btn btn-sm btn-clean btn-icon" title="Edit details" data-toggle="tooltip">
                                <i class="la la-edit"></i>
                            </a>
                            <a href="{{url('/admin/module/delete/'.$value->id)}}" class="btn btn-sm btn-clean btn-icon" title="Delete details" data-toggle="tooltip" onclick="return confirm('Are you sure you want to delete this module?');">
                                <i class="la la-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">No modules found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
    div.dataTables_wrapper div.dataTables_length select {
        width: 56px !important;
        display: inline-block;
    }
</style>
@endsection

@section('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
    $('#myTable').DataTable();

    $('.dataTables_filter label input[type=search]').addClass('form-control form-control-sm');
    $('.dataTables_length select').addClass('custom-select custom-select-sm form-control form-control-sm');

    // Drag & Drop sorting
    var fixHelper = function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };

    $("#myTable tbody").sortable({
        helper: fixHelper,
        update: function(event, ui) {
            var order = [];
            $('#myTable tbody tr').each(function(index, elem){
                order.push({
                    id: $(elem).data('id'),
                    position: index + 1
                });
            });
            $.ajax({
                url: "{{ url('admin/module/sort-order') }}",
                method: "POST",
                data: {
                    sort_order: order,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response){
                    // Optionally show a success message
                }
            });
        }
    }).disableSelection();
});

// Change status function with AJAX
function changeStatus(elem) {
    if(confirm("Do you want to change status?")) {
        var url = $(elem).data('url');
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                location.reload();
            }
        });
    }
}
</script>
@endsection
