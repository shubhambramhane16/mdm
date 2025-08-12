@extends('admin.layout.default')

@section('budget-management','active menu-item-open')
@section('content')
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-3 pb-0">
        <div class="card-title">
            <h3 class="card-label">Budget Management</h3>
        </div>
    </div>

    <div class="card-body">
        {{-- card data --}}
    </div>

</div>

@endsection

{{-- Styles Section --}}
@section('styles')
@endsection


{{-- Scripts Section --}}
@section('scripts')
<script src="{{url('/')}}/public/js/apexcharts.js?v=7.2.9"></script>

@endsection
