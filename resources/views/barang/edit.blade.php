@extends('layouts.app-admin')

@section('title', 'Edit Barang')

@section('content')
<div class="page-header">
    <h3 class="page-title">Edit Barang</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Barang</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Edit Barang</h4>
                
                <form method="POST" action="{{ route('barang.update', $barang->id_barang) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- ID Barang (Read Only) -->
                    <div class="form-group">
                        <label>ID Barang</label>
                        <input type="text" 
                               class="form-control" 
                               value="{{ $barang->id_barang }}"
                               readonly>
                    </div>
                    
                    <!-- Nama Barang -->
                    <div class="form-group">
                        <label>Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_barang') is-invalid @enderror" 
                               name="nama_barang" 
                               value="{{ old('nama_barang', $barang->nama_barang) }}"
                               required>
                        @error('nama_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Harga -->
                    <div class="form-group">
                        <label>Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" 
                                   class="form-control @error('harga') is-invalid @enderror" 
                                   name="harga" 
                                   value="{{ old('harga', $barang->harga) }}"
                                   min="0"
                                   step="0.01"
                                   required>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Stok -->
                    <div class="form-group">
                        <label>Stok <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('stok') is-invalid @enderror" 
                               name="stok" 
                               value="{{ old('stok', $barang->stok) }}"
                               min="0"
                               required>
                        @error('stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Deskripsi -->
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  name="deskripsi" 
                                  rows="3">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-gradient-primary me-2">
                            <i class="mdi mdi-content-save"></i> Update
                        </button>
                        <a href="{{ route('barang.index') }}" class="btn btn-light">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection