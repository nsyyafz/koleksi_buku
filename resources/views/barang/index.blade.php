@extends('layouts.app-admin')

@section('title', 'Data Barang')

@section('content')
<div class="page-header">
    <h3 class="page-title">Data Barang</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Barang</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Daftar Barang</h4>
                    <div>
                        <a href="{{ route('barang.cetak.index') }}" class="btn btn-gradient-success btn-sm me-2">
                            <i class="mdi mdi-printer"></i> Cetak Label
                        </a>
                        <a href="{{ route('barang.create') }}" class="btn btn-gradient-primary btn-sm">
                            <i class="mdi mdi-plus"></i> Tambah Barang
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Form Pencarian --}}
                <form method="GET" action="{{ route('barang.index') }}" class="mb-3">
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Cari barang..."
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">Cari</button>
                        @if(request('search'))
                            <a href="{{ route('barang.index') }}" class="btn btn-outline-danger">Reset</a>
                        @endif
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barang as $b)
                            <tr>
                                <td>{{ $b->id_barang }}</td>
                                <td>{{ $b->nama_barang }}</td>
                                <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                                <td>{{ $b->stok }}</td>
                                <td>
                                    <a href="{{ route('barang.edit', $b->id_barang) }}" class="btn btn-sm btn-gradient-info">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <form action="{{ route('barang.destroy', $b->id_barang) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-gradient-danger">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada data barang.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection