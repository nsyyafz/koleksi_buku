@extends('layouts.app-admin')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Wilayah Indonesia - Axios</h4>
                <p class="card-description">
                    Dependent Dropdown: Provinsi → Kota → Kecamatan → Kelurahan (Promise-based)
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

                <div class="alert alert-success mt-3" role="alert" id="selectedInfo" style="display: none;">
                    <strong>Pilihan Anda:</strong>
                    <div id="infoText"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts-page')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Event: Provinsi berubah
document.getElementById('provinsi').addEventListener('change', async function() {
    const provinsiKode = this.value;
    const provinsiNama = this.options[this.selectedIndex].text;
    
    // Reset dropdown di bawahnya
    resetDropdown('kota', '-- Pilih Kota --', true);
    resetDropdown('kecamatan', '-- Pilih Kecamatan --', true);
    resetDropdown('kelurahan', '-- Pilih Kelurahan --', true);
    document.getElementById('selectedInfo').style.display = 'none';
    
    if(provinsiKode) {
        try {
            // Set loading
            document.getElementById('kota').innerHTML = '<option value="">Loading...</option>';
            
            // Axios request untuk get kota
            const response = await axios.post("{{ route('wilayah.get-kota') }}", {
                provinsi_kode: provinsiKode,
                _token: "{{ csrf_token() }}"
            });
            
            if(response.data.status === 'success' && response.data.data.length > 0) {
                let options = '<option value="">-- Pilih Kota --</option>';
                
                response.data.data.forEach(kota => {
                    options += `<option value="${kota.kode}">${kota.nama}</option>`;
                });
                
                document.getElementById('kota').innerHTML = options;
                document.getElementById('kota').disabled = false;
                
                updateInfo();
            } else {
                document.getElementById('kota').innerHTML = '<option value="">Tidak ada data kota</option>';
            }
        } catch(error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Gagal mengambil data kota'
            });
        }
    }
});

// Event: Kota berubah
document.getElementById('kota').addEventListener('change', async function() {
    const kotaKode = this.value;
    
    // Reset dropdown di bawahnya
    resetDropdown('kecamatan', '-- Pilih Kecamatan --', true);
    resetDropdown('kelurahan', '-- Pilih Kelurahan --', true);
    
    if(kotaKode) {
        try {
            // Set loading
            document.getElementById('kecamatan').innerHTML = '<option value="">Loading...</option>';
            
            // Axios request untuk get kecamatan
            const response = await axios.post("{{ route('wilayah.get-kecamatan') }}", {
                kota_kode: kotaKode,
                _token: "{{ csrf_token() }}"
            });
            
            if(response.data.status === 'success' && response.data.data.length > 0) {
                let options = '<option value="">-- Pilih Kecamatan --</option>';
                
                response.data.data.forEach(kecamatan => {
                    options += `<option value="${kecamatan.kode}">${kecamatan.nama}</option>`;
                });
                
                document.getElementById('kecamatan').innerHTML = options;
                document.getElementById('kecamatan').disabled = false;
                
                updateInfo();
            } else {
                document.getElementById('kecamatan').innerHTML = '<option value="">Tidak ada data kecamatan</option>';
            }
        } catch(error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Gagal mengambil data kecamatan'
            });
        }
    }
    
    updateInfo();
});

// Event: Kecamatan berubah
document.getElementById('kecamatan').addEventListener('change', async function() {
    const kecamatanKode = this.value;
    
    // Reset dropdown kelurahan
    resetDropdown('kelurahan', '-- Pilih Kelurahan --', true);
    
    if(kecamatanKode) {
        try {
            // Set loading
            document.getElementById('kelurahan').innerHTML = '<option value="">Loading...</option>';
            
            // Axios request untuk get kelurahan
            const response = await axios.post("{{ route('wilayah.get-kelurahan') }}", {
                kecamatan_kode: kecamatanKode,
                _token: "{{ csrf_token() }}"
            });
            
            if(response.data.status === 'success' && response.data.data.length > 0) {
                let options = '<option value="">-- Pilih Kelurahan --</option>';
                
                response.data.data.forEach(kelurahan => {
                    options += `<option value="${kelurahan.kode}">${kelurahan.nama}</option>`;
                });
                
                document.getElementById('kelurahan').innerHTML = options;
                document.getElementById('kelurahan').disabled = false;
                
                updateInfo();
            } else {
                document.getElementById('kelurahan').innerHTML = '<option value="">Tidak ada data kelurahan</option>';
            }
        } catch(error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Gagal mengambil data kelurahan'
            });
        }
    }
    
    updateInfo();
});

// Event: Kelurahan berubah
document.getElementById('kelurahan').addEventListener('change', function() {
    updateInfo();
});

// Function untuk reset dropdown
function resetDropdown(id, defaultText, disabled) {
    const dropdown = document.getElementById(id);
    dropdown.innerHTML = `<option value="">${defaultText}</option>`;
    dropdown.disabled = disabled;
}

// Function untuk update info pilihan
function updateInfo() {
    const provinsi = document.getElementById('provinsi').options[document.getElementById('provinsi').selectedIndex].text;
    const kota = document.getElementById('kota').options[document.getElementById('kota').selectedIndex].text;
    const kecamatan = document.getElementById('kecamatan').options[document.getElementById('kecamatan').selectedIndex].text;
    const kelurahan = document.getElementById('kelurahan').options[document.getElementById('kelurahan').selectedIndex].text;
    
    let info = '';
    
    if(provinsi !== '-- Pilih Provinsi --') {
        info += `<div><strong>Provinsi:</strong> ${provinsi}</div>`;
    }
    
    if(kota !== '-- Pilih Kota --' && kota !== 'Loading...' && kota !== '') {
        info += `<div><strong>Kota/Kabupaten:</strong> ${kota}</div>`;
    }
    
    if(kecamatan !== '-- Pilih Kecamatan --' && kecamatan !== 'Loading...' && kecamatan !== '') {
        info += `<div><strong>Kecamatan:</strong> ${kecamatan}</div>`;
    }
    
    if(kelurahan !== '-- Pilih Kelurahan --' && kelurahan !== 'Loading...' && kelurahan !== '') {
        info += `<div><strong>Kelurahan/Desa:</strong> ${kelurahan}</div>`;
    }
    
    if(info !== '') {
        document.getElementById('infoText').innerHTML = info;
        document.getElementById('selectedInfo').style.display = 'block';
    } else {
        document.getElementById('selectedInfo').style.display = 'none';
    }
}
</script>
@endpush