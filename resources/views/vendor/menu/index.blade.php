@extends('layouts.app-admin')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Menu {{ $vendor->name }}</h4>
                        <p class="text-muted">Kelola menu makanan dan minuman</p>
                    </div>
                    <a href="{{ route('vendor.menu.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Tambah Menu
                    </a>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover" id="menuTable">
                        <thead>
                            <tr>
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                            <tr>
                                <td>
                                    <strong>{{ $menu->name }}</strong>
                                    @if($menu->description)
                                        <br><small class="text-muted">{{ Str::limit($menu->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($menu->category)
                                        <span class="badge badge-info">{{ $menu->category }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $menu->formatted_price }}</td>
                                <td>
                                    <div class="form-check form-check-success">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input toggle-availability" 
                                                   data-id="{{ $menu->id }}" 
                                                   {{ $menu->is_available ? 'checked' : '' }}>
                                            <i class="input-helper"></i>
                                            <span class="availability-text-{{ $menu->id }}">
                                                {{ $menu->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('vendor.menu.edit', $menu->id) }}" class="btn btn-sm btn-warning">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $menu->id }}" data-name="{{ $menu->name }}">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts-pages')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#menuTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        }
    });

    // Toggle Availability
    $('.toggle-availability').on('change', function() {
        const menuId = $(this).data('id');
        const isChecked = $(this).is(':checked');
        
        $.ajax({
            url: "/vendor/menu/" + menuId + "/toggle",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if(response.status === 'success') {
                    const text = response.data.is_available ? 'Tersedia' : 'Tidak Tersedia';
                    $('.availability-text-' + menuId).text(text);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Gagal mengubah status menu', 'error');
                // Revert checkbox
                $(this).prop('checked', !isChecked);
            }
        });
    });

    // Delete Menu
    $('.btn-delete').on('click', function() {
        const menuId = $(this).data('id');
        const menuName = $(this).data('name');
        
        Swal.fire({
            title: 'Hapus Menu?',
            text: 'Menu "' + menuName + '" akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/vendor/menu/" + menuId,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if(response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Gagal menghapus menu', 'error');
                    }
                });
            }
        });
    });
});
</script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endpush