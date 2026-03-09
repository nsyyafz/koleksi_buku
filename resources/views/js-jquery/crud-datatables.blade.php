@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-table-large"></i>
            </span> CRUD DataTables + Modal
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">CRUD DataTables</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Input Barang</h4>
                    <p class="card-description">Data tidak tersimpan ke database (Pure JavaScript + jQuery)</p>
                    
                    <!-- ALERT SUCCESS -->
                    <div id="alert-success" class="alert alert-success alert-dismissible fade show" style="display: none;" role="alert">
                        <strong>Berhasil!</strong> <span id="alert-message"></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- FORM INPUT -->
                    <form id="form-barang" class="forms-sample">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_barang" placeholder="Masukkan nama barang" required>
                                    <small class="text-danger" id="error-nama" style="display: none;">Nama barang harus diisi!</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga_barang">Harga Barang <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="harga_barang" placeholder="Masukkan harga" required>
                                    <small class="text-danger" id="error-harga" style="display: none;">Harga barang harus diisi!</small>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-gradient-primary me-2">
                            <i class="mdi mdi-content-save"></i> Submit
                        </button>
                        <button type="reset" class="btn btn-light">Reset</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE BARANG -->
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Barang</h4>
                    <p class="card-description">
                        <i class="mdi mdi-information"></i> Klik row untuk edit/hapus
                    </p>
                    
                    <div class="table-responsive">
                        <table id="table-barang" class="table table-striped table-bordered table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th width="15%">ID Barang</th>
                                    <th width="50%">Nama Barang</th>
                                    <th width="35%">Harga Barang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan ditambahkan via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT/DELETE (Bootstrap 4) -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="modalEditLabel">
                    <i class="mdi mdi-pencil"></i> Edit / Hapus Barang
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-edit">
                    <div class="form-group">
                        <label for="edit_id">ID Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_id" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_nama">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama" required>
                        <small class="text-danger" id="error-edit-nama" style="display: none;">Nama barang harus diisi!</small>
                    </div>
                    <div class="form-group">
                        <label for="edit_harga">Harga Barang <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="edit_harga" required>
                        <small class="text-danger" id="error-edit-harga" style="display: none;">Harga barang harus diisi!</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn-delete">
                    <i class="mdi mdi-delete"></i> Hapus
                </button>
                <button type="button" class="btn btn-primary" id="btn-update">
                    <i class="mdi mdi-content-save"></i> Ubah
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts-page')
<!-- DataTables CSS & JS (Bootstrap 4 compatible) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    console.log('=================================');
    console.log('🚀 CRUD DataTables Initializer');
    console.log('=================================');
    
    // Data barang
    let dataBarang = [];
    let nextId = 1;
    let currentEditId = null;
    let table;
    
    // Inisialisasi DataTables
    table = $('#table-barang').DataTable({
        data: dataBarang,
        columns: [
            { data: 'id' },
            { data: 'nama' },
            { 
                data: 'harga',
                render: function(data, type, row) {
                    return 'Rp ' + formatRupiah(data);
                }
            }
        ],
        language: {
            emptyTable: "Belum ada data. Silakan tambahkan barang.",
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        pageLength: 10,
        ordering: true,
        searching: true
    });
    
    console.log('✅ DataTables initialized');
    
    // Format rupiah
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // Fungsi validasi form input
    function validateForm() {
        let isValid = true;
        
        const nama = $('#nama_barang').val().trim();
        const harga = $('#harga_barang').val().trim();
        
        $('#error-nama').hide();
        $('#error-harga').hide();
        
        if (nama === '') {
            $('#error-nama').show();
            isValid = false;
        }
        
        if (harga === '' || harga <= 0) {
            $('#error-harga').show();
            isValid = false;
        }
        
        return isValid;
    }
    
    // Fungsi validasi form edit
    function validateEditForm() {
        let isValid = true;
        
        const nama = $('#edit_nama').val().trim();
        const harga = $('#edit_harga').val().trim();
        
        $('#error-edit-nama').hide();
        $('#error-edit-harga').hide();
        
        if (nama === '') {
            $('#error-edit-nama').show();
            isValid = false;
        }
        
        if (harga === '' || harga <= 0) {
            $('#error-edit-harga').show();
            isValid = false;
        }
        
        return isValid;
    }
    
    // Fungsi tampilkan alert
    function showAlert(message) {
        $('#alert-message').text(message);
        $('#alert-success').show();
        
        setTimeout(function() {
            $('#alert-success').fadeOut();
        }, 3000);
    }
    
    // Submit form tambah barang
    $('#form-barang').on('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            return;
        }
        
        const nama = $('#nama_barang').val().trim();
        const harga = parseInt($('#harga_barang').val());
        
        // Generate ID
        const id = 'BRG' + String(nextId).padStart(4, '0');
        nextId++;
        
        // Tambahkan ke array
        const barang = {
            id: id,
            nama: nama,
            harga: harga
        };
        
        dataBarang.push(barang);
        
        // Update DataTables
        table.row.add(barang).draw();
        
        console.log('✅ Barang ditambahkan:', barang);
        
        // Show alert
        showAlert('Barang "' + nama + '" berhasil ditambahkan!');
        
        // Reset form
        this.reset();
        $('#nama_barang').focus();
    });
    
    // Klik row → Buka modal
    $('#table-barang tbody').on('click', 'tr', function() {
        // Cek apakah row bukan "No data available"
        if ($(this).hasClass('dataTables_empty')) {
            return;
        }
        
        const data = table.row(this).data();
        
        if (data) {
            currentEditId = data.id;
            
            console.log('🔧 Row clicked:', data);
            
            // Isi form modal
            $('#edit_id').val(data.id);
            $('#edit_nama').val(data.nama);
            $('#edit_harga').val(data.harga);
            
            // Reset error
            $('#error-edit-nama').hide();
            $('#error-edit-harga').hide();
            
            // Buka modal (Bootstrap 4)
            $('#modal-edit').modal('show');
        }
    });
    
    // Button Update
    $('#btn-update').on('click', function() {
        if (!validateEditForm()) {
            return;
        }
        
        const nama = $('#edit_nama').val().trim();
        const harga = parseInt($('#edit_harga').val());
        
        // Cari index barang di array
        const index = dataBarang.findIndex(b => b.id === currentEditId);
        
        if (index !== -1) {
            // Update data
            dataBarang[index].nama = nama;
            dataBarang[index].harga = harga;
            
            // Update DataTables
            table.clear();
            table.rows.add(dataBarang);
            table.draw();
            
            console.log('✅ Barang diupdate:', dataBarang[index]);
            
            // Show alert
            showAlert('Barang "' + nama + '" berhasil diupdate!');
            
            // Tutup modal (Bootstrap 4)
            $('#modal-edit').modal('hide');
        }
    });
    
    // Button Delete
    $('#btn-delete').on('click', function() {
        if (confirm('Yakin ingin menghapus barang ini?')) {
            // Cari index barang di array
            const index = dataBarang.findIndex(b => b.id === currentEditId);
            
            if (index !== -1) {
                const namaBarang = dataBarang[index].nama;
                
                console.log('🗑️ Barang dihapus:', dataBarang[index]);
                
                // Hapus dari array
                dataBarang.splice(index, 1);
                
                // Update DataTables
                table.clear();
                table.rows.add(dataBarang);
                table.draw();
                
                // Show alert
                showAlert('Barang "' + namaBarang + '" berhasil dihapus!');
                
                // Tutup modal (Bootstrap 4)
                $('#modal-edit').modal('hide');
            }
        }
    });
    
    // CSS untuk hover pointer
    $('#table-barang tbody').css('cursor', 'pointer');
    
    console.log('=================================');
    console.log('✅ All event handlers attached');
    console.log('=================================');
});
</script>
@endpush