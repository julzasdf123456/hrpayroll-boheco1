<aside class="main-sidebar {{ $userCache->ColorProfile != null ? 'sidebar-dark-primary' : 'sidebar-light' }} elevation-4">
    <a href="{{ route('home') }}" class="brand-link" title="{{ env('APP_FULLNAME') }}">
        <img src="{{ URL::asset('imgs/logo.png'); }}"
             alt="{{ config('app.name') }} Logo"
             class="brand-image img-circle">
        <span class="brand-text {{ $userCache->ColorProfile != null ? 'brand-text-light' : 'brand-text-dark' }} font-weight-bold">{{ config('app.name') }}</span>
    </a>

    <div class="sidebar">
        <div class="form-inline" style="margin-top: 8px; margin-bottom: 8px;">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control sidebar-search" type="search" placeholder="Search Menu" aria-label="Search">
                {{-- <div class="input-group-append">
                    <button class="btn btn-sm btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                    </button>
                </div> --}}
            </div>
        </div>
        <nav class="mt-4">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-child-indent nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu')
            </ul>
        </nav>
    </div>
</aside>
