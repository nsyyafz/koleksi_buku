@extends('layouts.app-admin')

@section('title', 'Cetak Label Harga')

@section('content')
<div class="page-header">
    <h3 class="page-title">Cetak Label Harga</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Barang</a></li>
            <li class="breadcrumb-item active">Cetak Label</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pilih Barang untuk Dicetak</h4>

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Pesan validasi dari controller jika tidak ada barang dipilih --}}
                @if($errors->has('selected_ids'))
                    <div class="alert alert-danger">
                        {{ $errors->first('selected_ids') }}
                    </div>
                @endif

                <form action="{{ route('barang.cetak') }}" method="POST" id="form-cetak" target="_blank">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Koordinat Awal Cetak</h5>
                                    <p class="text-muted small">
                                        Kertas label Tom & Jerry No 108 memiliki 5 kolom (X) dan 8 baris (Y).
                                    </p>

                                    <div class="form-group">
                                        <label>Koordinat X (Kolom) <span class="text-danger">*</span></label>
                                        <select name="koordinat_x" class="form-select" required>
                                            <option value="">Pilih Kolom</option>
                                            @for($x = 1; $x <= 5; $x++)
                                                <option value="{{ $x }}" {{ old('koordinat_x', '1') == $x ? 'selected' : '' }}>
                                                    Kolom {{ $x }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Koordinat Y (Baris) <span class="text-danger">*</span></label>
                                        <select name="koordinat_y" class="form-select" required>
                                            <option value="">Pilih Baris</option>
                                            @for($y = 1; $y <= 8; $y++)
                                                <option value="{{ $y }}" {{ old('koordinat_y', '1') == $y ? 'selected' : '' }}>
                                                    Baris {{ $y }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="alert alert-info">
                                        <small>
                                            <i class="mdi mdi-information"></i>
                                            <strong>Contoh:</strong> X=1, Y=1 = Mulai dari pojok kiri atas
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Layout Kertas Label</h5>
                                    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 2px;">
                                        @for($i = 1; $i <= 40; $i++)
                                            <div style="border: 1px solid #ddd; padding: 5px; text-align: center; font-size: 10px; background: #f8f9fa;">
                                                {{ $i }}
                                            </div>
                                        @endfor
                                    </div>
                                    <p class="text-muted small mt-2">
                                        5 kolom × 8 baris = 40 label per lembar
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="50">Pilih</th>
                                    <th>ID Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barang as $b)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox"
                                               name="selected_ids[]"
                                               value="{{ $b->id_barang }}"
                                               {{ in_array($b->id_barang, old('selected_ids', [])) ? 'checked' : '' }}>
                                    </td>
                                    <td>{{ $b->id_barang }}</td>
                                    <td>{{ $b->nama_barang }}</td>
                                    <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-gradient-primary btn-lg">
                            <i class="mdi mdi-printer"></i> Cetak PDF
                        </button>
                        <a href="{{ route('barang.index') }}" class="btn btn-light btn-lg">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection