@extends('layouts.app')

@section('title', $title)

@section('addon-css')
<link rel="stylesheet" href="{{ url('assets/modules/izitoast/css/iziToast.min.css') }}">
<style>
    #suggestions {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        width: 240px;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ccc;
        z-index: 1;
    }

    #suggestions li {
        padding: 10px;
        cursor: pointer;
    }

    #suggestions li:hover {
        background-color: #ddd;
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ $title }}</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">
                {{ $title }}
            </h2>
            <p class="section-lead">
                Halaman untuk membuat transaksi baru.
            </p>

            @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {{ $error }}
                </div>
            </div>
            @endforeach
            @endif

            <form action="{{ route('sale.store') }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                Informasi
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="far fa-user"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-key"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $transactionCode }}" name="transaction_code" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="far fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" value="{{ get_tanggal(date('Y-m-d H:i:s')) }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">

                        <div class="card">
                            <div class="card-header">
                                Produk
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-barcode"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" id="product_code" name="product_code" oninput="showAutoSuggestion(this.value)" placeholder="Kode Produk" value="{{ old('product_code') }}" required>
                                    </div>
                                    <div id="validator-product_code"></div>
                                    <div id="suggestions"></div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-file-signature"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" value="{{ old('quantity') }}" required>
                                    </div>
                                    <div id="validator-quantity"></div>
                                </div>
                            </div>
                            <div class="card-footer text-right" style="margin-bottom: -9px;">
                                <button type="button" class="btn btn-primary" onclick="tampilModal(product_code.value, quantity.value)" data-toggle="modal" data-target="#konfirmasi">Kirim</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg">
                        <div class="card card-block d-flex" style="height: 311px">
                            <div class="card-header">
                                Rp.
                            </div>
                            <div class="card-body text-center align-items-center d-flex justify-content-center">
                                <h1 class="display-1 priceDisplay">{{ number_format($subTotal, 0,',',',') }}</h1>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
            <div class="card">
                <div class="card-header">
                    Sales
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="saleTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col"></th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Total</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $item)
                                <tr>
                                    <th>{{ $index + 1 }}</th>
                                    <th>
                                        <img src="{{ Storage::disk('public')->exists($item->product->photo) ? Storage::url($item->product->photo) : url('assets/img/image_not_available.png') }}" alt="Foto Produk" class="img-fluid rounded mt-1 mb-1" height="10px" width="80px" />
                                    </th>
                                    <th>{{ $item->product->name }}</th>
                                    <th>Rp. {{ number_format($item->product_price, 0,',',',') }}</th>
                                    <th>{{ $item->quantity }}</th>
                                    <th>Rp. {{ number_format($item->total_price, 0,',',',') }}</th>
                                    <th class="text-right">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-success btn-icon icon-left" data-toggle="modal" data-target="#editItem-{{ $item->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form action="{{ route('sale.destroy', $item->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger btn-icon icon-left btn-delete" data-namaproduk="{{ $item->product->name }}">
                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </th>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        Belum ada produk yang dibeli.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('sale.getCoupon') }}" method="post">
                                @csrf
                                <input type="hidden" name="transaction_code" value="{{ $transactionCode }}">
                                <div class="form-group">
                                    <label>Kupon <code>(Jika ada)</code></label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="coupon_code" class="form-control" onkeyup="this.value = this.value.toUpperCase();" onkeypress="return event.charCode != 32" value="{{ session('coupon_code') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">Cek</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <form action="{{ route('transaction.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="transaction_code" value="{{ $transactionCode }}" />
                        <input type="hidden" id="tanggalTransaksi" name="tanggalTransaksi" value="" />
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Customer</label>
                                    <select name="customer_id" class="custom-select">
                                        @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                </div>

                <div class="col-lg">
                    <div class="card">
                        <div class="card-header">
                            Pembayaran
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="hidden" name="coupon_code" value="{{ session('coupon_code') }}" />
                                    <div class="form-group">
                                        <label>Diskon</label>
                                        <div class="input-group mb-2">
                                            <input type="text" name="discount" class="form-control" value="{{ session('discount') ? session('discount') : '0' }}" readonly>
                                            <div class="input-group-append">
                                                <div class="input-group-text">%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Potongan Diskon</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Rp.</div>
                                            </div>
                                            <input type="text" name="discount_price" class="form-control currency" value="0" readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Sub Total</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Rp.</div>
                                            </div>
                                            <input type="text" name="sub_total" class="form-control currency" value="{{ $subTotal }}" readonly />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Grand Total</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Rp.</div>
                                            </div>
                                            <input type="text" name="grand_total" class="form-control currency" value="{{ $subTotal }}" readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg">
                                    <div class="form-group">
                                        <label>Dibayar</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Rp.</div>
                                            </div>
                                            <input type="text" name="paid" class="form-control currency" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Kembalian</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Rp.</div>
                                            </div>
                                            <input type="text" name="change" class="form-control currency" value="0" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="text-right">
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <button type="submit" class="btn btn-primary" id="createTransaction" disabled>Buat Transaksi</button>
            </div>
            </form>

        </div>
    </section>
</div>

@foreach ($items as $item)
<div class="modal fade" tabindex="-1" role="dialog" id="editItem-{{ $item->id }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('sale.update', $item->id) }}" method="POST">
                @method('PUT')
                @csrf
                <input type="hidden" name="transaction_code" value="{{ $item->transaction_code }}">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $item->product->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Produk</label>
                        <input type="text" class="form-control" value="{{ $item->product->product_code }}" readonly />
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" required />
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endforeach
<div id="modalHideUp">
</div>
@endsection

@section('addon-script')
<script src="{{ url('assets/modules/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ url('assets/modules/izitoast/js/iziToast.min.js') }}"></script>
<script src="{{ url('assets/modules/cleave-js/dist/cleave.min.js') }}"></script>
<script src="{{ url('js/my_cleave.js') }}"></script>
<script src="{{ url('js/my_sweetalert.js')}}"></script>
<script>
    signal = {};
    signal.produk = <?=json_encode(App\Product::all())?>;
    let auth = "<?=Auth::user()->name?>";
    let transaksikode = "<?=$transactionCode?>";
    let tglPenjualan = "<?=date("Y-m-d H:i:s")?>";
    
    $(document).ready(function() {

        function currencyFormat(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        let discount = $('[name="discount"]').val();
        let discountPrice = $('[name="discount_price"]');
        let subTotal = $('[name="sub_total"]').val().replace(/,/g, '');
        let grandTotal = $('[name="grand_total"]');
        let paid = $('[name="paid"]');
        let change = $('[name="change"]');
        let priceDisplay = $('.priceDisplay');

        let sumDiscountPrice = subTotal * discount / 100;

        discountPrice.val(currencyFormat(sumDiscountPrice));
        grandTotal.val(currencyFormat(subTotal - sumDiscountPrice));
        priceDisplay.html(currencyFormat(subTotal - sumDiscountPrice));

        paid.on('input', function() {
            paidValue = paid.val().replace(/,/g, '');
            changeValue = paidValue - grandTotal.val().replace(/,/g, '');

            if (changeValue < 0) {
                change.val(0)
            } else {
                change.val(currencyFormat(changeValue));
            }

            if (paidValue >= (subTotal - sumDiscountPrice)) {
                $(':input[id="createTransaction"]').prop('disabled', false);
            } else {
                $(':input[id="createTransaction"]').prop('disabled', true);
            }
        });
    });

function showAutoSuggestion(input){
    var inputValue = input.toLowerCase();
    
    if(inputValue.length > 2){
        var suggestions = signal.produk.filter(function(item) {
            let code =  item.product_code.toLowerCase().indexOf(inputValue) > -1;
            let name =  item.name.toLowerCase().indexOf(inputValue) > -1;
            return code || name;
        });
        showSuggestions(suggestions);
    }else{
        hideSuggestions();
    }
}
function showSuggestions(suggestions) {
    let suggestionList = document.getElementById('suggestions');
    suggestionList.innerHTML = '';
    if(suggestions.length > 0){
        suggestions.forEach(suggestion => {
            let li = document.createElement('li');
            li.textContent = `${suggestion.product_code.toUpperCase()} || ${suggestion.name.toUpperCase()}`;
            li.addEventListener('click', function() {
                document.getElementById('product_code').value = suggestion.product_code;
                hideSuggestions();
            });
            suggestionList.appendChild(li);
        });
    }else{
        let li = document.createElement('li');
        li.textContent = `Produk Tidak Ada`;
        suggestionList.appendChild(li);
    }
    suggestionList.style.display = 'block';
}

function hideSuggestions() {
    document.getElementById('suggestions').style.display = 'none';
}

function tampilModal(code, qty){
    let modalHideUp = document.getElementById("modalHideUp");
    let validatorcode = document.getElementById("validator-product_code");
    let validatorqty = document.getElementById("validator-quantity");

    validatorcode.innerHTML = "<p class='text-sm text-red-500'>*Kode wajib diisi!</p>";
    validatorqty.innerHTML = "<p class='text-sm text-red-500'>*Stok wajib diisi!</p>";

    if(code !== ""){
        validatorcode.innerHTML = "";
        modalHideUp.innerHTML = ``;
    }
    if(qty !== ""){
        validatorqty.innerHTML = "";
        modalHideUp.innerHTML = ``;
    }

    if(code !== "" && qty !== ""){
        for(let data of signal.produk){
            if(data.product_code == code){
                modalHideUp.innerHTML = `
                <div class="modal fade" tabindex="-1" role="dialog" id="konfirmasi">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('sale.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="transaction_code" value="${transaksikode}" />
                            <input type="hidden" id="quantity" name="quantity" value="${qty}"/>
                            <input type="hidden" id="product_code" name="product_code" value="${code}"/>
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Harga Jual</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body ">
                                    <div class="font-semibold mb-1">Harga Beli : ${rp(data.purchase_price,'rp')}</div>
                                    <div class="form-group">
                                        <label>Tanggal Jual</label>
                                        <input type="date" id="tglJual" name="tglJual" class="form-control" value="<?=date("Y-m-d");?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label>Harga Jual</label>
                                        <input type="text" id="harga_jual" name="harga_jual" oninput="formatRp(this,event)" class="form-control" value="${rp(qty*data.purchase_price)}" required />
                                    </div>
                                </div>
                                <div class="modal-footer bg-whitesmoke br">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
                `;
            }
        }
        validatorcode.innerHTML = "";
        validatorqty.innerHTML = "";
    }else{
        modalHideUp.innerHTML = ``;
    }
}

function formatRp(elm, event){
    if (!elm.value) {return;}
    let hargajual = elm.value;
    hargajual = hargajual.replace(/\D/ig,"");
    elm.value = rp(hargajual);
}

function closeModal(divModal, transisimodal){
    let modalUpload = document.getElementById("divModalUpload");
    let divdata = document.getElementById("divData");
    modalUpload.classList.add("hidden");
    divdata.innerHTML = "";
}

</script>
<script>
function rp (angka, prefix) {
    if (!angka) { return }
    if (parseInt(angka) == NaN) { return 0; }
    if (parseInt(angka) == 0) { return 0; }

    minus = false;
    if (angka < 0) {
        minus = true; angka = Math.abs(angka);
    }
    numberString = angka.toString(),
        split = numberString.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

    if (minus) {
        return prefix == undefined ? '-' + rupiah : (rupiah ? 'Rp. -' + rupiah : '');
    }
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
};
</script>
@endsection