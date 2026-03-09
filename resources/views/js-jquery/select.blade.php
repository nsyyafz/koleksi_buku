@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-format-list-bulleted"></i>
            </span> Select & Select2
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Select & Select2</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <!-- CARD 1: SELECT BIASA -->
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-format-list-bulleted-square"></i> Select Biasa
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Input Kota -->
                    <div class="form-group">
                        <label for="input_kota_1">Kota:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="input_kota_1" placeholder="Masukkan nama kota">
                            <div class="input-group-append">
                                <button class="btn btn-gradient-primary" type="button" id="btn_tambah_1">
                                    <i class="mdi mdi-plus"></i> Tambahkan
                                </button>
                            </div>
                        </div>
                        <small class="text-danger" id="error_kota_1" style="display: none;">Nama kota harus diisi!</small>
                    </div>

                    <!-- Select Kota -->
                    <div class="form-group">
                        <label for="select_kota_1">Select Kota:</label>
                        <select class="form-control" id="select_kota_1" size="5">
                            <option value="" disabled selected>-- Pilih Kota --</option>
                        </select>
                    </div>

                    <!-- Kota Terpilih -->
                    <div class="form-group">
                        <label>Kota Terpilih:</label>
                        <div class="card bg-light">
                            <div class="card-body">
                                <div id="kota_terpilih_1">
                                    <span class="text-muted"><i class="mdi mdi-information"></i> Belum ada kota yang dipilih</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 2: SELECT2 -->
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-header bg-gradient-info text-white">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-format-list-checkbox"></i> Select2
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Input Kota -->
                    <div class="form-group">
                        <label for="input_kota_2">Kota:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="input_kota_2" placeholder="Masukkan nama kota">
                            <div class="input-group-append">
                                <button class="btn btn-gradient-info" type="button" id="btn_tambah_2">
                                    <i class="mdi mdi-plus"></i> Tambahkan
                                </button>
                            </div>
                        </div>
                        <small class="text-danger" id="error_kota_2" style="display: none;">Nama kota harus diisi!</small>
                    </div>

                    <!-- Select Kota (Select2) -->
                    <div class="form-group">
                        <label for="select_kota_2">Select Kota:</label>
                        <select class="form-control" id="select_kota_2" multiple="multiple">
                            <!-- Options akan ditambahkan via JavaScript -->
                        </select>
                    </div>

                    <!-- Kota Terpilih -->
                    <div class="form-group">
                        <label>Kota Terpilih:</label>
                        <div class="card bg-light">
                            <div class="card-body">
                                <div id="kota_terpilih_2">
                                    <span class="text-muted"><i class="mdi mdi-information"></i> Belum ada kota yang dipilih</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts-page')
<!-- Select2 CSS & JS (Bootstrap 4 compatible) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    console.log('=================================');
    console.log('🚀 Select & Select2 Initializer');
    console.log('=================================');
    
    // =============================================
    // CARD 1: SELECT BIASA
    // =============================================
    
    let kotaList1 = [];
    
    // Button tambah kota (Select Biasa)
    $('#btn_tambah_1').on('click', function() {
        const inputKota = $('#input_kota_1').val().trim();
        const errorKota = $('#error_kota_1');
        
        // Reset error
        errorKota.hide();
        
        // Validasi
        if (inputKota === '') {
            errorKota.show();
            return;
        }
        
        // Cek duplikat
        if (kotaList1.includes(inputKota)) {
            alert('Kota "' + inputKota + '" sudah ada!');
            return;
        }
        
        // Tambahkan ke array
        kotaList1.push(inputKota);
        
        console.log('✅ Kota ditambahkan (Select Biasa):', inputKota);
        
        // Tambahkan ke select
        const option = $('<option></option>')
            .attr('value', inputKota)
            .text(inputKota);
        $('#select_kota_1').append(option);
        
        // Reset input
        $('#input_kota_1').val('').focus();
    });
    
    // Event change select (Select Biasa)
    $('#select_kota_1').on('change', function() {
        const selectedKota = $(this).val();
        
        if (selectedKota) {
            console.log('🔧 Kota dipilih (Select Biasa):', selectedKota);
            updateKotaTerpilih1(selectedKota);
        }
    });
    
    // Fungsi update kota terpilih (Select Biasa)
    function updateKotaTerpilih1(kota) {
        const container = $('#kota_terpilih_1');
        
        // Cek apakah kota sudah ada
        const existingBadges = container.find('.badge');
        let kotaExists = false;
        
        existingBadges.each(function() {
            if ($(this).data('kota') === kota) {
                kotaExists = true;
            }
        });
        
        // Kalau belum ada, tambahkan
        if (!kotaExists) {
            // Hapus pesan "Belum ada kota"
            container.find('.text-muted').remove();
            
            // Tambahkan badge
            const badge = $('<span></span>')
                .addClass('badge badge-primary mr-1 mb-1')
                .attr('data-kota', kota)
                .html('<i class="mdi mdi-map-marker"></i> ' + kota);
            
            container.append(badge);
        }
    }
    
    // =============================================
    // CARD 2: SELECT2
    // =============================================
    
    let kotaList2 = [];
    
    // Inisialisasi Select2 (Bootstrap 4 theme)
    $('#select_kota_2').select2({
        theme: 'bootstrap4',
        placeholder: '-- Pilih Kota --',
        allowClear: true,
        width: '100%'
    });
    
    console.log('✅ Select2 initialized');
    
    // Button tambah kota (Select2)
    $('#btn_tambah_2').on('click', function() {
        const inputKota = $('#input_kota_2').val().trim();
        const errorKota = $('#error_kota_2');
        
        // Reset error
        errorKota.hide();
        
        // Validasi
        if (inputKota === '') {
            errorKota.show();
            return;
        }
        
        // Cek duplikat
        if (kotaList2.includes(inputKota)) {
            alert('Kota "' + inputKota + '" sudah ada!');
            return;
        }
        
        // Tambahkan ke array
        kotaList2.push(inputKota);
        
        console.log('✅ Kota ditambahkan (Select2):', inputKota);
        
        // Tambahkan ke select2
        const newOption = new Option(inputKota, inputKota, false, false);
        $('#select_kota_2').append(newOption).trigger('change');
        
        // Reset input
        $('#input_kota_2').val('').focus();
    });
    
    // Event change select2
    $('#select_kota_2').on('change', function() {
        const selectedKota = $(this).val(); // Array of selected values
        
        console.log('🔧 Kota dipilih (Select2):', selectedKota);
        updateKotaTerpilih2(selectedKota);
    });
    
    // Fungsi update kota terpilih (Select2)
    function updateKotaTerpilih2(kotaArray) {
        const container = $('#kota_terpilih_2');
        
        // Clear container
        container.empty();
        
        if (kotaArray && kotaArray.length > 0) {
            kotaArray.forEach(function(kota) {
                const badge = $('<span></span>')
                    .addClass('badge badge-info mr-1 mb-1')
                    .html('<i class="mdi mdi-map-marker"></i> ' + kota);
                container.append(badge);
            });
        } else {
            container.html('<span class="text-muted"><i class="mdi mdi-information"></i> Belum ada kota yang dipilih</span>');
        }
    }
    
    // Enter key untuk submit (kedua input)
    $('#input_kota_1').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#btn_tambah_1').click();
        }
    });
    
    $('#input_kota_2').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#btn_tambah_2').click();
        }
    });
    
    console.log('=================================');
    console.log('✅ All event handlers attached');
    console.log('=================================');
});
</script>

<!-- Custom CSS untuk Bootstrap 4 -->
<style>
/* Fix untuk input-group di Bootstrap 4 */
.input-group-append .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

/* Badge spacing */
.badge {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

/* Select2 custom height */
.select2-container .select2-selection--multiple {
    min-height: 120px;
}

/* Card header padding */
.card-header {
    padding: 1rem 1.25rem;
}
</style>
@endpush