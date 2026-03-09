@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-table"></i>
            </span> CRUD Table HTML
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">CRUD Table HTML</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Input Barang</h4>
                    <p class="card-description">Data tidak tersimpan ke database (Pure JavaScript)</p>
                    
                    <!-- ALERT SUCCESS -->
                    <div id="alert-success" class="alert alert-success alert-dismissible fade show" style="display: none;" role="alert">
                        <strong>Berhasil!</strong> <span id="alert-message"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                    <p class="card-description">Total: <span id="total-barang" class="badge badge-info">0</span> barang</p>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th width="10%">ID Barang</th>
                                    <th width="50%">Nama Barang</th>
                                    <th width="40%">Harga Barang</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <!-- Data akan ditambahkan via JavaScript -->
                                <tr id="empty-row">
                                    <td colspan="3" class="text-center text-muted">
                                        <i class="mdi mdi-inbox"></i> Belum ada data. Silakan tambahkan barang.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Data barang (array of objects)
let dataBarang = [];
let nextId = 1;

// Fungsi untuk render table
function renderTable() {
    const tableBody = document.getElementById('table-body');
    const emptyRow = document.getElementById('empty-row');
    const totalBadge = document.getElementById('total-barang');
    
    // Kalau data kosong, tampilkan empty row
    if (dataBarang.length === 0) {
        emptyRow.style.display = '';
        totalBadge.textContent = '0';
        return;
    }
    
    // Sembunyikan empty row
    emptyRow.style.display = 'none';
    
    // Clear table body (kecuali empty row)
    tableBody.innerHTML = '';
    tableBody.appendChild(emptyRow);
    
    // Render setiap barang
    dataBarang.forEach((barang) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${barang.id}</td>
            <td>${barang.nama}</td>
            <td>Rp ${formatRupiah(barang.harga)}</td>
        `;
        tableBody.appendChild(row);
    });
    
    // Update total
    totalBadge.textContent = dataBarang.length;
}

// Fungsi format rupiah
function formatRupiah(angka) {
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Fungsi validasi form
function validateForm() {
    let isValid = true;
    
    const nama = document.getElementById('nama_barang').value.trim();
    const harga = document.getElementById('harga_barang').value.trim();
    
    const errorNama = document.getElementById('error-nama');
    const errorHarga = document.getElementById('error-harga');
    
    // Reset error
    errorNama.style.display = 'none';
    errorHarga.style.display = 'none';
    
    // Validasi nama
    if (nama === '') {
        errorNama.style.display = 'block';
        isValid = false;
    }
    
    // Validasi harga
    if (harga === '' || harga <= 0) {
        errorHarga.style.display = 'block';
        isValid = false;
    }
    
    return isValid;
}

// Fungsi tampilkan alert success
function showAlert(message) {
    const alert = document.getElementById('alert-success');
    const alertMessage = document.getElementById('alert-message');
    
    alertMessage.textContent = message;
    alert.style.display = 'block';
    
    // Auto hide setelah 3 detik
    setTimeout(() => {
        alert.style.display = 'none';
    }, 3000);
}

// Event submit form
document.getElementById('form-barang').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validasi
    if (!validateForm()) {
        return;
    }
    
    // Ambil nilai input
    const nama = document.getElementById('nama_barang').value.trim();
    const harga = parseInt(document.getElementById('harga_barang').value);
    
    // Generate ID (BRG0001, BRG0002, ...)
    const id = 'BRG' + String(nextId).padStart(4, '0');
    nextId++;
    
    // Tambahkan ke array
    dataBarang.push({
        id: id,
        nama: nama,
        harga: harga
    });
    
    // Render ulang table
    renderTable();
    
    // Show alert
    showAlert('Barang "' + nama + '" berhasil ditambahkan!');
    
    // Reset form
    this.reset();
    
    // Focus ke input nama
    document.getElementById('nama_barang').focus();
});

// Initial render
renderTable();
</script>
@endsection