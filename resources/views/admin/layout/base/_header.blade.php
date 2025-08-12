{{-- Header --}}
<div id="kt_header" class="header {{ Metronic::printClasses('header', false) }}" {{ Metronic::printAttrs('header') }}>

    {{-- Container --}}
    <div class="container-fluid d-flex align-items-center justify-content-between">
        @if (config('layout.header.self.display'))

        @php
        $kt_logo_image = 'logo-light.png';
        @endphp

        @if (config('layout.header.self.theme') === 'light')
        @php $kt_logo_image = 'logo-dark.png' @endphp
        @elseif (config('layout.header.self.theme') === 'dark')
        @php $kt_logo_image = 'logo-light.png' @endphp
        @endif

        {{-- Header Menu --}}
        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
            @if(config('layout.aside.self.display') == false)
            <div class="header-logo">
                <a href="{{ url('/') }}">
                    <img alt="Logo" src="{{ asset('media/logos/'.$kt_logo_image) }}" />
                </a>
            </div>
            @endif

            {{-- only get back url when it available --}}

            {{-- <div>
                @if(session('previous_url'))
                    <a href="{{ session('previous_url') }}"
                       class="btn btn-primary font-weight-bolder approve-list"
                       style="color: #FFFFFF; background-color: #0e4c83 !important; border-color: #0e4c83 !important;">
                        Back
                    </a>
                @else
                    <a href="{{ url()->previous() }}"
                       class="btn btn-primary font-weight-bolder approve-list"
                       style="color: #FFFFFF; background-color: #0e4c83 !important; border-color: #0e4c83 !important;">
                        Back
                    </a>
                @endif
            </div> --}}


    </div>

    @else
    <div></div>
    @endif

    @include('admin.layout.partials.extras._topbar')
</div>
</div>
