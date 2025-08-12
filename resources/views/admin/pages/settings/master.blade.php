@extends('admin.layout.default')

@section('settings', 'active menu-item-open')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-3 pb-0">
            <div class="card-title">
                <h3 class="card-label">Master</h3>
            </div>
        </div>
        @php
            use Illuminate\Support\Str;
            $menu = modulesListNew();
            $menu = collect($menu)
                ->map(function ($item) {
                    return [
                        'label' => $item['module_name'],
                        'icon' => $item['icon'] ?? 'dashboard',
                        'route' => $item['route'] ?? '#',
                        'sortOrder' => $item['sortOrder'] ?? 0,
                        'id' => $item['id'] ?? '',
                        'is_left_menu' => $item['is_left_menu'] ?? false,
                    ];
                })
                ->filter(function ($item) {
                    return isset($item['is_left_menu']) && $item['is_left_menu'] === false;
                })
                ->sortBy('sortOrder')
                ->values();
        @endphp
        <div class="card-body">
            <div class="dash-card-start">
                @foreach($menu as $item)
                    <div class="card">
                        <a href="{{ url('admin/' . $item['route']) }}"
                            style="display: block; height: 100%; color: inherit; text-decoration: none;"
                            aria-label="View {{ $item['label'] }}">
                            <div class="dash-cards">
                                <div class="dash-card-img">
                                    <div
                                        style="background: #fff; box-shadow: inset 0px 2px 4px #00000029; border: 1px solid #a4a7dc; border-radius: 50%; height: 60px; width: 60px; display: flex; align-items: center; justify-content: center;">
                                        <span style="font-weight: bold; font-size: 2rem; color: #0e4c83;">
                                            {{ strtoupper(collect(explode('-', Str::slug($item['label'])))->map(fn($w) => Str::substr($w, 0, 1))->join('')) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="dash-card-cont">
                                    <p>{{ $item['label'] }}</p>
                                </div>
                            </div>
                            <div class="dash-view-btn">
                                <p>View All</p>
                                <span>
                                    <img class="pt-10 w-70" alt="{{ config('app.name') }}"
                                        src="{{ asset('media/dashboard-img/right-arrow.png') }}" />
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        body {
            background: linear-gradient(135deg, #e0e7ff 0%, #f3f8ff 100%);
        }

        .dash-cards {
            display: flex;
            padding: 1.5rem 2.5rem;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            gap: 1.2rem;
            background: linear-gradient(90deg, #cbdffe 60%, #e3eaff 100%);
            align-items: center;
            box-shadow: 0 4px 24px #3a57e81a;
            position: relative;
            overflow: hidden;
        }

        .dash-cards::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 60px;
            height: 60px;
            background: radial-gradient(circle, #3a57e8 20%, transparent 70%);
            opacity: 0.15;
            z-index: 0;
        }

        .dash-card-img {
            z-index: 1;
        }

        .dash-card-img > div {
            background: linear-gradient(135deg, #fff 60%, #e3eaff 100%);
            box-shadow: 0px 4px 12px #3a57e81a, inset 0px 2px 4px #00000029;
            border: 2px solid #a4a7dc;
            border-radius: 50%;
            height: 70px;
            width: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }

        .dash-card-img > div:hover {
            transform: scale(1.08) rotate(-5deg);
            box-shadow: 0px 8px 24px #3a57e81a;
        }

        .dash-card-img span {
            font-weight: bold;
            font-size: 2.2rem;
            color: #0e4c83;
            text-shadow: 0 2px 8px #3a57e84d;
            letter-spacing: 2px;
        }

        .dash-card-cont p {
            margin-bottom: 0;
            font-size: 1.15rem;
            font-weight: 600;
            color: #2d3a5a;
            text-shadow: 0 1px 2px #e3eaff;
        }

        .dash-card-start {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            margin-bottom: 3rem;
            justify-content: flex-start;
        }

        .card {
            box-shadow: 0px 2px 12px #3a57e81a;
            border: 1px solid #E9E9E9;
            background: #fff;
            transition: box-shadow 0.2s, transform 0.2s;
            position: relative;
        }

        .dash-card-start .card {
            width: 23%;
            min-width: 260px;
            border-radius: 16px !important;
            overflow: hidden;
        }

        .dash-card-start .card:hover {
            box-shadow: 0px 8px 32px #3a57e84d;
            transform: translateY(-4px) scale(1.03);
            border-color: #0e4c83;
        }

        .dash-view-btn {
            display: flex;
            gap: 12px;
            justify-content: center;
            align-items: center;
            padding: 1.2rem;
            background: linear-gradient(90deg, #e3eaff 0%, #cbdffe 100%);
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
            box-shadow: 0 -2px 8px #3a57e81a;
        }

        .dash-view-btn p {
            margin-bottom: 0;
            font-weight: 500;
            color: #0e4c83;
            font-size: 1rem;
            letter-spacing: 1px;
        }

        .dash-view-btn span img {
            filter: drop-shadow(0 2px 6px #3a57e84d);
            width: 32px;
            transition: transform 0.2s;
        }

        .dash-card-start .card:hover .dash-view-btn span img {
            transform: translateX(6px) scale(1.1);
        }
    </style>
@endsection

