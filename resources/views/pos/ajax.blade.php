@extends('layouts.app-admin')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Point of Sales — jQuery AJAX</h4>

                {{-- Form Cari Barang --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode Barang</label>
                            <input type="text" class="form-control" id="kode_barang" placeholder="Ketik lalu tekan Enter">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" class="form-control" id="nama_barang" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Harga Barang</label>
                            <input type="text" class="form-control" id="harga_barang" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" value="1" min="1">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-success" id="btn-tambah" disabled onclick="tambahItem()">
                            Tambahkan
                        </button>
                    </div>
                </div>

                {{-- Tabel Keranjang --}}
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-body">
                            <tr id="empty-row">
                                <td colspan="6" class="text-center text-muted">Belum ada barang</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Total</td>
                                <td class="font-weight-bold" id="total-display">Rp 0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row mt-2">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-primary" id="btn-bayar" onclick="bayar()">
                            Bayar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts-page')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let barangDitemukan = null;
let keranjang = [];

// Cari barang saat Enter
$('#kode_barang').on('keydown', function(e) {
    if (e.key === 'Enter') cariBarang();
});

// Aktifkan tombol Tambahkan jika jumlah valid
$('#jumlah').on('input', function() {
    const jumlah = parseInt($(this).val());
    $('#btn-tambah').prop('disabled', !barangDitemukan || jumlah < 1);
});

function cariBarang() {
    const kode = $('#kode_barang').val().trim();
    if (!kode) return;

    barangDitemukan = null;
    $('#nama_barang').val('');
    $('#harga_barang').val('');
    $('#jumlah').val(1);
    $('#btn-tambah').prop('disabled', true);

    $.ajax({
        url: "{{ route('pos.get-barang') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            kode_barang: kode
        },
        success: function(res) {
            if (res.status === 'success') {
                barangDitemukan = res.data;
                $('#nama_barang').val(res.data.nama_barang);
                $('#harga_barang').val(res.data.harga);
                $('#jumlah').val(1);
                $('#btn-tambah').prop('disabled', false);
            }
        },
        error: function() {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Ditemukan',
                text: 'Kode barang tidak ada di database.'
            });
        }
    });
}

function tambahItem() {
    if (!barangDitemukan) return;

    const jumlah   = parseInt($('#jumlah').val());
    const subtotal = barangDitemukan.harga * jumlah;

    // Kalau barang sudah ada di keranjang, update jumlah & subtotal
    const idx = keranjang.findIndex(i => i.id_barang === barangDitemukan.id_barang);
    if (idx > -1) {
        keranjang[idx].jumlah  += jumlah;
        keranjang[idx].subtotal = keranjang[idx].harga * keranjang[idx].jumlah;
    } else {
        keranjang.push({
            id_barang:   barangDitemukan.id_barang,
            nama_barang: barangDitemukan.nama_barang,
            harga:       barangDitemukan.harga,
            jumlah:      jumlah,
            subtotal:    subtotal
        });
    }

    renderTabel();
    resetForm();
}

function renderTabel() {
    if (keranjang.length === 0) {
        $('#tabel-body').html('<tr id="empty-row"><td colspan="6" class="text-center text-muted">Belum ada barang</td></tr>');
        updateTotal();
        return;
    }

    let html = '';
    keranjang.forEach((item, idx) => {
        html += `<tr>
            <td>${item.id_barang}</td>
            <td>${item.nama_barang}</td>
            <td>Rp ${item.harga.toLocaleString('id-ID')}</td>
            <td>
                <input type="number" class="form-control form-control-sm" value="${item.jumlah}" min="1"
                    style="width:80px;" onchange="ubahJumlah(${idx}, this.value)">
            </td>
            <td>Rp ${item.subtotal.toLocaleString('id-ID')}</td>
            <td>
                <button class="btn btn-danger btn-sm" onclick="hapusItem(${idx})">Hapus</button>
            </td>
        </tr>`;
    });

    $('#tabel-body').html(html);
    updateTotal();
}

function ubahJumlah(idx, val) {
    const jumlah = parseInt(val);
    if (jumlah < 1) return;
    keranjang[idx].jumlah   = jumlah;
    keranjang[idx].subtotal = keranjang[idx].harga * jumlah;
    renderTabel();
}

function hapusItem(idx) {
    keranjang.splice(idx, 1);
    renderTabel();
}

function updateTotal() {
    const total = keranjang.reduce((sum, i) => sum + i.subtotal, 0);
    $('#total-display').text('Rp ' + total.toLocaleString('id-ID'));
}

function resetForm() {
    barangDitemukan = null;
    $('#kode_barang').val('').focus();
    $('#nama_barang').val('');
    $('#harga_barang').val('');
    $('#jumlah').val(1);
    $('#btn-tambah').prop('disabled', true);
}

function bayar() {
    if (keranjang.length === 0) {
        Swal.fire({
            icon: 'info',
            title: 'Keranjang Kosong',
            text: 'Tambahkan barang terlebih dahulu.'
        });
        return;
    }

    const total = keranjang.reduce((sum, i) => sum + i.subtotal, 0);

    $.ajax({
        url: "{{ route('pos.bayar') }}",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
            _token: "{{ csrf_token() }}",
            items:  keranjang,
            total:  total
        }),
        success: function(res) {
            if (res.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Transaksi berhasil disimpan.'
                }).then(() => {
                    keranjang = [];
                    renderTabel();
                    resetForm();
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat menyimpan transaksi.'
            });
        }
    });
}
</script>
@endpush