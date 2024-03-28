@extends('layouts.app')

@section('title', $title)

@section('addon-css')
<link rel="stylesheet" href="{{ url('assets/modules/izitoast/css/iziToast.min.css') }}">
<style>
    #suggestions {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        width: 95%;
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
                Halaman untuk menambahkan produk baru.
            </p>

            <div class="card">

                <form action="{{ route('pembelian.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

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

                        <div class="row">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label>
                                        Tanggal
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <input type="datetime-local" class="form-control" name="tanggal" value="<?=date("Y-m-d H:i:s")?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Kode Produk</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-key"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" id="product_code" name="product_code" oninput="showAutoSuggestion(this.value)" value="{{ old('product_code') }}">
                                    </div>
                                    <div id="suggestions"></div>
                                </div>
                                <div class="form-group">
                                    <label>Kategori <br/>
                                        @if ($categories->isEmpty())
                                            <code>Belum ada kategori klik <a href="{{ route('product-category.index') }}">disini</a> untuk menambah kategori.</code>
                                        @endif
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-tag"></i>
                                            </div>
                                        </div>
                                        <select class="form-control" name="category_id">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-pencil-alt"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Stok</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-layer-group"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control currency" name="stock">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>
                                        Harga Beli
                                    </label>
                                    
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <b>Rp</b>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control currency" name="purchase_price">
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label>
                                        Harga Jual
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <b>Rp</b>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control currency" name="selling_price">
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@section('addon-script')
<script src="{{ url('assets/modules/cleave-js/dist/cleave.min.js') }}"></script>
<script src="{{ url('js/my_cleave.js') }}"></script>
<script src="{{ url('js/image_upload.js') }}"></script>

<script>
    signal = {};
    signal.produk = <?=json_encode($produk)?>;

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
                document.getElementById('name').value = suggestion.name;
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
</script>
@endsection
