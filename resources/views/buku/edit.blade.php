@extends('layouts.app-admin')

@section('title', 'Edit Buku')

@section('content')
<div class="page-header">
    <h3 class="page-title">Edit Buku</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('buku.index') }}">Buku</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Edit Buku</h4>
                
                <form action="{{ route('buku.update', $buku->idbuku) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="idkategori">Kategori</label>
                        <select class="form-control @error('idkategori') is-invalid @enderror" 
                                id="idkategori" 
                                name="idkategori">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->idkategori }}" 
                                        {{ old('idkategori', $buku->idkategori) == $kategori->idkategori ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('idkategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kode">Kode Buku</label>
                        <input type="text" 
                               class="form-control @error('kode') is-invalid @enderror" 
                               id="kode" 
                               name="kode" 
                               value="{{ old('kode', $buku->kode) }}"
                               placeholder="Contoh: NV-01">
                        @error('kode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="judul">Judul Buku</label>
                        <input type="text" 
                               class="form-control @error('judul') is-invalid @enderror" 
                               id="judul" 
                               name="judul" 
                               value="{{ old('judul', $buku->judul) }}"
                               placeholder="Masukkan judul buku">
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="pengarang">Pengarang</label>
                        <input type="text" 
                               class="form-control @error('pengarang') is-invalid @enderror" 
                               id="pengarang" 
                               name="pengarang" 
                               value="{{ old('pengarang', $buku->pengarang) }}"
                               placeholder="Masukkan nama pengarang">
                        @error('pengarang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-gradient-primary me-2">Update</button>
                    <a href="{{ route('buku.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection