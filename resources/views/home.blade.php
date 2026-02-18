@extends('layouts.app-admin')

@section('title', 'Dashboard')

@push('styles-page')
<style>
    .card-img-absolute {
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
    }
    .dashboard-card {
        transition: transform 0.3s ease;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span> Dashboard
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-6 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white dashboard-card">
            <div class="card-body">
                <img src="{{ asset('purple/assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Total Kategori
                    <i class="mdi mdi-bookmark mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5" id="total-kategori">{{ \App\Models\Kategori::count() }}</h2>
                <h6 class="card-text">Kategori Buku</h6>
            </div>
        </div>
    </div>
    <div class="col-md-6 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white dashboard-card">
            <div class="card-body">
                <img src="{{ asset('purple/assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Total Buku
                    <i class="mdi mdi-book-open-page-variant mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5" id="total-buku">{{ \App\Models\Buku::count() }}</h2>
                <h6 class="card-text">Koleksi Buku</h6>
            </div>
        </div>
    </div>
</div>
@endsection