@extends('layouts.app-admin')

@section('title', 'Data Barang')

{{-- CSS DataTables --}}
@push('styles-page')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<style>
    .checkbox-cell {
        text-align: center;
        width: 50px;
    }
    .action-cell {
        width: 150px;
    }
</style>
@endpush

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
                        <button id="btn-cetak" class="btn btn-gradient-success btn-sm me-2" disabled>
                            <i class="mdi mdi-printer"></i> Cetak Label
                        </button>
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

                <div class="table-responsive">
                    <table id="table-barang" class="table table-hover">
                        <thead>
                            <tr>
                                <th class="checkbox-cell">
                                    <input type="checkbox" id="check-all">
                                </th>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th class="action-cell">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data dari Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Input Koordinat -->
<div class="modal fade" id="modalKoordinat" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Koordinat Awal Cetak</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-cetak" action="{{ route('barang.cetak') }}" method="POST" target="_blank">
                @csrf
                <div class="modal-body">
                    <p class="text-muted">
                        Kertas label Tom & Jerry No 108 memiliki 5 kolom (X) dan 8 baris (Y).
                        <br>Tentukan posisi awal cetak:
                    </p>
                    
                    <input type="hidden" name="selected_ids" id="selected-ids">
                    
                    <div class="mb-3">
                        <label class="form-label">Koordinat X (Kolom)</label>
                        <select name="koordinat_x" class="form-select" required>
                            <option value="">Pilih Kolom</option>
                            <option value="1">Kolom 1</option>
                            <option value="2">Kolom 2</option>
                            <option value="3">Kolom 3</option>
                            <option value="4">Kolom 4</option>
                            <option value="5">Kolom 5</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Koordinat Y (Baris)</label>
                        <select name="koordinat_y" class="form-select" required>
                            <option value="">Pilih Baris</option>
                            <option value="1">Baris 1</option>
                            <option value="2">Baris 2</option>
                            <option value="3">Baris 3</option>
                            <option value="4">Baris 4</option>
                            <option value="5">Baris 5</option>
                            <option value="6">Baris 6</option>
                            <option value="7">Baris 7</option>
                            <option value="8">Baris 8</option>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <small>
                            <strong>Jumlah barang terpilih:</strong> <span id="jumlah-terpilih">0</span>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-printer"></i> Cetak PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- JS DataTables --}}
@push('scripts-page')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTables
    const table = $('#table-barang').DataTable({
        ajax: {
            url: '{{ route('barang.data') }}',
            dataSrc: 'data'
        },
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `<input type="checkbox" class="check-item" value="${row.id_barang}">`;
                }
            },
            { data: 'id_barang' },
            { data: 'nama_barang' },
            { 
                data: 'harga',
                render: function(data) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(data);
                }
            },
            { data: 'stok' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <a href="/barang/${row.id_barang}/edit" class="btn btn-sm btn-gradient-info">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        <form action="/barang/${row.id_barang}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-gradient-danger btn-delete">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </form>
                    `;
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        }
    });
    
    // Check all
    $('#check-all').on('change', function() {
        $('.check-item').prop('checked', this.checked);
        updateButtonCetak();
    });
    
    // Check item
    $(document).on('change', '.check-item', function() {
        updateButtonCetak();
        
        // Update check-all
        const total = $('.check-item').length;
        const checked = $('.check-item:checked').length;
        $('#check-all').prop('checked', total === checked);
    });
    
    // Update button cetak
    function updateButtonCetak() {
        const checked = $('.check-item:checked').length;
        $('#btn-cetak').prop('disabled', checked === 0);
    }
    
    // Button cetak diklik
    $('#btn-cetak').on('click', function() {
        const selectedIds = $('.check-item:checked').map(function() {
            return this.value;
        }).get();
        
        if (selectedIds.length === 0) {
            alert('Pilih minimal 1 barang!');
            return;
        }
        
        // Set hidden input
        $('#selected-ids').val(selectedIds.join(','));
        $('#jumlah-terpilih').text(selectedIds.length);
        
        // Show modal
        const modal = new bootstrap.Modal('#modalKoordinat');
        modal.show();
    });
    
    // Konfirmasi hapus
    $(document).on('click', '.btn-delete', function(e) {
        if (!confirm('Yakin ingin menghapus barang ini?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush