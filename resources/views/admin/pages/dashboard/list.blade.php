@extends('admin.layout.default')

@section('dashboard', 'active menu-item-open')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-3 pb-0">
            <div class="card-title">
                <h3 class="card-label">Dashboard</h3>
            </div>
            <form action="" method="get" class="w-100" style="position: absolute; right: 0; top: 10px;">
                <div class="row pl-0 pr-0">
                    <div class="col-lg-12 text-right">
                        <div class="dataTables_length">
                            <input type="text" name="fromtodate" id="fromtodate" placeholder="From Date"
                                autocomplete="off" value="" style="opacity:0; width:0;position:absolute;right:20%">
                            <button type="button" class="btn" onclick="$('#fromtodate').click(),setSubmitAtt()">
                                <i class="icon-2x text-dark-50 ki ki-calendar"></i>
                            </button>
                            <button type="submit" class="btn btn-success btn-sm d-none" id="Filter_ME"
                                data-toggle="tooltip" title="Apply Filter">Filter</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="dash-card-start">
                <div class="card">
                    <div class="dash-cards">
                        <div class="dash-card-img">
                            <img class="pt-10 w-70" alt="{{ config('app.name') }}"
                                src="{{ asset('media/dashboard-img/dash-card_6.png') }}" />
                        </div>
                        <div class="dash-card-cont">
                            <p>Total Expense</p>
                            <h5><strong>@php
                                $totalExpense = 0;
                            @endphp</strong></h5>
                        </div>
                    </div>
                    <div class="dash-view-btn">
                        <a href="/admin/expense/list" style="color : black">
                            <p>View All</p>
                        </a>
                        <span>
                            <img class="pt-10 w-70" alt="{{ config('app.name') }}"
                                src="{{ asset('media/dashboard-img/right-arrow.png') }}" />
                        </span>
                    </div>
                </div>
                <div class="card">
                    <div class="dash-cards">
                        <div class="dash-card-img">
                            <img class="pt-10 w-70" alt="{{ config('app.name') }}"
                                src="{{ asset('media/dashboard-img/dash-card_5.png') }}" />
                        </div>
                        <div class="dash-card-cont">
                            <p>Total Income</p>
                            <h5><strong>@php
                                $totalIncome = 0;
                            @endphp</strong></h5>
                        </div>
                    </div>
                    <div class="dash-view-btn">
                        <a href="/admin/income/list" style="color : black">
                            <p>View All</p>
                        </a>
                        <span>
                            <img class="pt-10 w-70" alt="{{ config('app.name') }}"
                                src="{{ asset('media/dashboard-img/right-arrow.png') }}" />
                        </span>
                    </div>
                </div>
                <div class="card">
                    <div class="dash-cards">
                        <div class="dash-card-img">
                            <img class="pt-10 w-70" alt="{{ config('app.name') }}"
                                src="{{ asset('media/dashboard-img/dash-card_8.png') }}" />
                        </div>
                        <div class="dash-card-cont">
                            <p>Total Client</p>
                            <h5><strong>@php
                                $totalClients = 0;
                            @endphp</strong></h5>
                        </div>
                    </div>
                    <div class="dash-view-btn">
                        <a href="/admin/client/list" style="color : black">
                            <p>View All</p>
                        </a>
                        <span>
                            <img class="pt-10 w-70" alt="{{ config('app.name') }}"
                                src="{{ asset('media/dashboard-img/right-arrow.png') }}" />
                        </span>
                    </div>
                </div>
                <div class="card">
                    <div class="dash-cards">
                        <div class="dash-card-img">
                            <img class="pt-10 w-70" alt="{{ config('app.name') }}"
                                src="{{ asset('media/dashboard-img/dash-card_7.png') }}" />
                        </div>
                        <div class="dash-card-cont">
                            <p>Total Invoices</p>
                            <h5><strong>@php
                                $totalInvoices = 0;
                            @endphp</strong></h5>
                        </div>
                    </div>
                    <div class="dash-view-btn">
                        <a href="/admin/invoice/list" style="color : black">
                            <p>View All</p>
                        </a>
                        <span>
                            <img class="pt-10 w-70" alt="{{ config('app.name') }}"
                                src="{{ asset('media/dashboard-img/right-arrow.png') }}" />
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .dash-cards {
            display: flex;
            padding: 1rem 2rem;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            gap: 1rem;
            background: #cbdffe;
            align-items: center;
        }
        .dash-card-start {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        .card {
            box-shadow: 0px 1px 5px #00000029;
            border: 0.5px solid #E9E9E9;
        }
        .card-title {
            margin-bottom: 1rem;
            padding-top: 2rem;
        }
        .dash-card-start .card {
            width: 25%;
            box-shadow: 0px 1px 4px #00000029;
            border-radius: 8px !important;
        }
        .dash-view-btn {
            display: flex;
            gap: 9px;
            justify-content: center;
            padding: 1rem;
        }
        .dash-view-btn p {
            margin-bottom: 0;
        }
        .dash-card-img img {
            background: #FFFFFF 0% 0% no-repeat padding-box;
            box-shadow: inset 0px 2px 4px #00000029;
            border: 1px solid #a4a7dc;
            border-radius: 50%;
            opacity: 1;
            padding: 6px;
            height: 60px;
            width: 60px !important;
        }
        .dash-card-cont p {
            margin-bottom: 0;
        }
    </style>
@endsection

@section('scripts')
@endsection
