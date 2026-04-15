@extends('layouts.app-admin')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pesan Makanan & Minuman</h4>
                <p class="card-description">Pilih vendor dan menu favorit Anda</p>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="vendor">Pilih Vendor/Kantin</label>
                            <select class="form-control form-control-lg" id="vendor">
                                <option value="">-- Pilih Vendor --</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="menuSection" style="display: none;">
                    <h5 class="mb-3">Menu Tersedia</h5>
                    <div class="row" id="menuList"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Shopping Cart -->
    <div class="col-lg-12 grid-margin stretch-card" id="cartSection" style="display: none;">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Keranjang Belanja</h4>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cartItems"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                                <td colspan="2"><strong id="totalAmount">Rp 0</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="form-group">
                    <label for="orderNotes">Catatan (opsional)</label>
                    <textarea class="form-control" id="orderNotes" rows="2" placeholder="Contoh: Pakai sambal extra, es batu sedikit"></textarea>
                </div>

                <button class="btn btn-success btn-lg btn-block" id="btnCheckout">
                    <i class="mdi mdi-cart-outline"></i> Bayar Sekarang
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts-page')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
let selectedVendor = null;
let cart = [];

// Event: Vendor berubah
document.getElementById('vendor').addEventListener('change', async function() {
    const vendorId = this.value;
    selectedVendor = vendorId;
    
    if(!vendorId) {
        document.getElementById('menuSection').style.display = 'none';
        return;
    }
    
    try {
        const response = await axios.post("{{ route('customer.get-menus') }}", {
            vendor_id: vendorId,
            _token: "{{ csrf_token() }}"
        });
        
        if(response.data.status === 'success') {
            displayMenus(response.data.data);
            document.getElementById('menuSection').style.display = 'block';
        }
    } catch(error) {
        console.error(error);
        Swal.fire('Error!', 'Gagal mengambil data menu', 'error');
    }
});

// Display menu cards
function displayMenus(menus) {
    const menuList = document.getElementById('menuList');
    menuList.innerHTML = '';
    
    if(menus.length === 0) {
        menuList.innerHTML = '<div class="col-12"><p class="text-muted">Tidak ada menu tersedia</p></div>';
        return;
    }
    
    menus.forEach(menu => {
        const col = document.createElement('div');
        col.className = 'col-md-4 mb-3';
        
        col.innerHTML = `
            <div class="card menu-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0">${menu.name}</h5>
                        ${menu.category ? `<span class="badge badge-info">${menu.category}</span>` : ''}
                    </div>
                    ${menu.description ? `<p class="card-text text-muted small">${menu.description}</p>` : ''}
                    <h6 class="text-primary mb-3">Rp ${formatRupiah(menu.price)}</h6>
                    <button class="btn btn-sm btn-primary btn-block" onclick="addToCart(${menu.id}, '${menu.name}', ${menu.price})">
                        <i class="mdi mdi-cart-plus"></i> Tambah ke Keranjang
                    </button>
                </div>
            </div>
        `;
        
        menuList.appendChild(col);
    });
}

// Add to cart
function addToCart(menuId, menuName, price) {
    const existingItem = cart.find(item => item.menu_id === menuId);
    
    if(existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            menu_id: menuId,
            name: menuName,
            price: price,
            quantity: 1
        });
    }
    
    updateCart();
    
    Swal.fire({
        icon: 'success',
        title: 'Ditambahkan!',
        text: menuName + ' ditambahkan ke keranjang',
        timer: 1500,
        showConfirmButton: false
    });
}

// Update cart display
function updateCart() {
    const cartItems = document.getElementById('cartItems');
    const cartSection = document.getElementById('cartSection');
    
    if(cart.length === 0) {
        cartSection.style.display = 'none';
        return;
    }
    
    cartSection.style.display = 'block';
    cartItems.innerHTML = '';
    
    let total = 0;
    
    cart.forEach((item, index) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.name}</td>
            <td>Rp ${formatRupiah(item.price)}</td>
            <td>
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-outline-secondary" onclick="decreaseQty(${index})">-</button>
                    <button class="btn btn-sm btn-outline-secondary disabled">${item.quantity}</button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="increaseQty(${index})">+</button>
                </div>
            </td>
            <td>Rp ${formatRupiah(subtotal)}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">
                    <i class="mdi mdi-delete"></i>
                </button>
            </td>
        `;
        
        cartItems.appendChild(row);
    });
    
    document.getElementById('totalAmount').textContent = 'Rp ' + formatRupiah(total);
}

// Increase quantity
function increaseQty(index) {
    cart[index].quantity++;
    updateCart();
}

// Decrease quantity
function decreaseQty(index) {
    if(cart[index].quantity > 1) {
        cart[index].quantity--;
        updateCart();
    }
}

// Remove from cart
function removeFromCart(index) {
    cart.splice(index, 1);
    updateCart();
}

// Checkout
document.getElementById('btnCheckout').addEventListener('click', async function() {
    if(cart.length === 0) {
        Swal.fire('Oops!', 'Keranjang belanja masih kosong', 'warning');
        return;
    }
    
    if(!selectedVendor) {
        Swal.fire('Oops!', 'Pilih vendor terlebih dahulu', 'warning');
        return;
    }
    
    const btnCheckout = this;
    btnCheckout.disabled = true;
    btnCheckout.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Processing...';
    
    try {
        const orderData = {
            vendor_id: selectedVendor,
            items: cart.map(item => ({
                menu_id: item.menu_id,
                quantity: item.quantity
            })),
            notes: document.getElementById('orderNotes').value,
            _token: "{{ csrf_token() }}"
        };
        
        const response = await axios.post("{{ route('customer.order.create') }}", orderData);
        
        if(response.data.status === 'success') {
            const snapToken = response.data.data.snap_token;
            const orderNumber = response.data.data.order_number;
            
            // Open Midtrans Snap
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        text: 'Terima kasih atas pesanan Anda',
                        confirmButtonText: 'Lihat Nota'
                    }).then(() => {
                        window.location.href = "{{ url('/order/receipt') }}/" + orderNumber;
                    });
                },
                onPending: function(result) {
                    Swal.fire('Pending', 'Pembayaran Anda sedang diproses', 'info');
                    btnCheckout.disabled = false;
                    btnCheckout.innerHTML = '<i class="mdi mdi-cart-outline"></i> Bayar Sekarang';
                },
                onError: function(result) {
                    Swal.fire('Error!', 'Terjadi kesalahan saat pembayaran', 'error');
                    btnCheckout.disabled = false;
                    btnCheckout.innerHTML = '<i class="mdi mdi-cart-outline"></i> Bayar Sekarang';
                },
                onClose: function() {
                    btnCheckout.disabled = false;
                    btnCheckout.innerHTML = '<i class="mdi mdi-cart-outline"></i> Bayar Sekarang';
                }
            });
        }
    } catch(error) {
        console.error(error);
        Swal.fire('Error!', error.response?.data?.message || 'Gagal membuat pesanan', 'error');
        btnCheckout.disabled = false;
        btnCheckout.innerHTML = '<i class="mdi mdi-cart-outline"></i> Bayar Sekarang';
    }
});

// Format rupiah helper
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID').format(angka);
}
</script>

<style>
.menu-card {
    transition: transform 0.2s;
}
.menu-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endpush