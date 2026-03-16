@extends('layouts.app-admin')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Wilayah Indonesia - jQuery AJAX</h4>
                <p class="card-description">
                    Dependent Dropdown: Provinsi → Kota → Kecamatan → Kelurahan
                </p>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="provinsi">Provinsi</label>
                            <select class="form-control" id="provinsi" name="provinsi">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinsi as $prov)
                                    <option value="{{ $prov->kode }}">{{ $prov->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kota">Kota/Kabupaten</label>
                            <select class="form-control" id="kota" name="kota" disabled>
                                <option value="">-- Pilih Kota --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan</label>
                            <select class="form-control" id="kecamatan" name="kecamatan" disabled>
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kelurahan">Kelurahan/Desa</label>
                            <select class="form-control" id="kelurahan" name="kelurahan" disabled>
                                <option value="">-- Pilih Kelurahan --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3" role="alert" id="selectedInfo" style="display: none;">
                    <strong>Pilihan Anda:</strong>
                    <div id="infoText"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts-page')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    
    // Event: Provinsi berubah
    $('#provinsi').on('change', function() {
        var provinsiKode = $(this).val();
        var provinsiNama = $(this).find('option:selected').text();
        
        // Reset dropdown di bawahnya
        $('#kota').html('<option value="">-- Pilih Kota --</option>').prop('disabled', true);
        $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
        $('#kelurahan').html('<option value="">-- Pilih Kelurahan --</option>').prop('disabled', true);
        $('#selectedInfo').hide();
        
        if(provinsiKode) {
            // AJAX request untuk get kota
            $.ajax({
                url: "{{ route('wilayah.get-kota') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    provinsi_kode: provinsiKode
                },
                beforeSend: function() {
                    $('#kota').html('<option value="">Loading...</option>');
                },
                success: function(response) {
                    if(response.status === 'success' && response.data.length > 0) {
                        $('#kota').html('<option value="">-- Pilih Kota --</option>');
                        
                        $.each(response.data, function(index, kota) {
                            $('#kota').append('<option value="'+ kota.kode +'">'+ kota.nama +'</option>');
                        });
                        
                        $('#kota').prop('disabled', false);
                        
                        updateInfo();
                    } else {
                        $('#kota').html('<option value="">Tidak ada data kota</option>');
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal mengambil data kota'
                    });
                }
            });
        }
    });
    
    // Event: Kota berubah
    $('#kota').on('change', function() {
        var kotaKode = $(this).val();
        
        // Reset dropdown di bawahnya
        $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
        $('#kelurahan').html('<option value="">-- Pilih Kelurahan --</option>').prop('disabled', true);
        
        if(kotaKode) {
            // AJAX request untuk get kecamatan
            $.ajax({
                url: "{{ route('wilayah.get-kecamatan') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    kota_kode: kotaKode
                },
                beforeSend: function() {
                    $('#kecamatan').html('<option value="">Loading...</option>');
                },
                success: function(response) {
                    if(response.status === 'success' && response.data.length > 0) {
                        $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>');
                        
                        $.each(response.data, function(index, kecamatan) {
                            $('#kecamatan').append('<option value="'+ kecamatan.kode +'">'+ kecamatan.nama +'</option>');
                        });
                        
                        $('#kecamatan').prop('disabled', false);
                        
                        updateInfo();
                    } else {
                        $('#kecamatan').html('<option value="">Tidak ada data kecamatan</option>');
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal mengambil data kecamatan'
                    });
                }
            });
        }
        
        updateInfo();
    });
    
    // Event: Kecamatan berubah
    $('#kecamatan').on('change', function() {
        var kecamatanKode = $(this).val();
        
        // Reset dropdown kelurahan
        $('#kelurahan').html('<option value="">-- Pilih Kelurahan --</option>').prop('disabled', true);
        
        if(kecamatanKode) {
            // AJAX request untuk get kelurahan
            $.ajax({
                url: "{{ route('wilayah.get-kelurahan') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    kecamatan_kode: kecamatanKode
                },
                beforeSend: function() {
                    $('#kelurahan').html('<option value="">Loading...</option>');
                },
                success: function(response) {
                    if(response.status === 'success' && response.data.length > 0) {
                        $('#kelurahan').html('<option value="">-- Pilih Kelurahan --</option>');
                        
                        $.each(response.data, function(index, kelurahan) {
                            $('#kelurahan').append('<option value="'+ kelurahan.kode +'">'+ kelurahan.nama +'</option>');
                        });
                        
                        $('#kelurahan').prop('disabled', false);
                        
                        updateInfo();
                    } else {
                        $('#kelurahan').html('<option value="">Tidak ada data kelurahan</option>');
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal mengambil data kelurahan'
                    });
                }
            });
        }
        
        updateInfo();
    });
    
    // Event: Kelurahan berubah
    $('#kelurahan').on('change', function() {
        updateInfo();
    });
    
    // Function untuk update info pilihan
    function updateInfo() {
        var provinsi = $('#provinsi option:selected').text();
        var kota = $('#kota option:selected').text();
        var kecamatan = $('#kecamatan option:selected').text();
        var kelurahan = $('#kelurahan option:selected').text();
        
        var info = '';
        
        if(provinsi !== '-- Pilih Provinsi --') {
            info += '<div><strong>Provinsi:</strong> ' + provinsi + '</div>';
        }
        
        if(kota !== '-- Pilih Kota --' && kota !== 'Loading...' && kota !== '') {
            info += '<div><strong>Kota/Kabupaten:</strong> ' + kota + '</div>';
        }
        
        if(kecamatan !== '-- Pilih Kecamatan --' && kecamatan !== 'Loading...' && kecamatan !== '') {
            info += '<div><strong>Kecamatan:</strong> ' + kecamatan + '</div>';
        }
        
        if(kelurahan !== '-- Pilih Kelurahan --' && kelurahan !== 'Loading...' && kelurahan !== '') {
            info += '<div><strong>Kelurahan/Desa:</strong> ' + kelurahan + '</div>';
        }
        
        if(info !== '') {
            $('#infoText').html(info);
            $('#selectedInfo').show();
        } else {
            $('#selectedInfo').hide();
        }
    }
});
</script>
@endpush