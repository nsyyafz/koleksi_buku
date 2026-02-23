@extends('layouts.app-admin')

@section('title', 'Generate PDF')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-file-pdf"></i>
        </span> Generate PDF
    </h3>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center">
                <i class="mdi mdi-certificate mdi-48px text-primary mb-3"></i>
                <h4 class="card-title">Sertifikat</h4>
                <p class="card-description">Format Landscape A4</p>
                <p class="text-muted small">Sertifikat penyelesaian Modul 2</p>
                <a href="{{ route('pdf.sertifikat') }}" class="btn btn-gradient-primary" target="_blank">
                    <i class="mdi mdi-download"></i> Download Sertifikat
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center">
                <i class="mdi mdi-email-open mdi-48px text-info mb-3"></i>
                <h4 class="card-title">Undangan</h4>
                <p class="card-description">Format Portrait A4 dengan Header</p>
                <p class="text-muted small">Undangan seminar nasional</p>
                <a href="{{ route('pdf.undangan') }}" class="btn btn-gradient-info" target="_blank">
                    <i class="mdi mdi-download"></i> Download Undangan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection