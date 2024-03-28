@extends('layouts.app')

@section('title', $title)

@section('addon-css')
<link rel="stylesheet" href="{{ url('assets/modules/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ url('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('assets/modules/izitoast/css/iziToast.min.css') }}">

<style>
    @media print {
  .hidden-print {
    display: none !important;
  }
}
</style>
@endsection

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header print:hidden hidden-print">
            <h1>{{ $title }}</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title print:hidden hidden-print">
                {{ $title }}
            </h2>
            <p class="section-lead print:hidden">
                Berisi daftar pembelian produk.
            </p>

            <div class="card">
                <div class="card-header print:hidden">
                    <a href="{{ route('pembelian.create') }}" class="btn btn-icon icon-left btn-primary print:hidden"><i class="fas fa-plus"></i> Tambah</a>
                </div>
                <div class="card-body">
                <form action="{{ route('pembelian.report') }}" method="post">
                @csrf
                    <div class="form-group print:hidden" style="width:300px;">
                        <label>Rentang Waktu</label>
                        <div class="flex">
                            <div class="input-group px-2" style="width:400px;">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control daterange-cus" name="date">
                            </div>
                            <div class="ml-2 pt-1"><button type="submit" class="btn btn-primary">Proses</button></div>
                        </div>
                    </div>
                </form>
                <div class="text-2xl font-semibold text-center mt-2 mb-2 hidden print:block">Daftar Pembelian Produk</div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tablePembelian">
                            <thead>                                 
                                <tr>
                                    <th class="text-center">
                                    #
                                    </th>
                                    <th>Tanggal</th>
                                    <th>Kode Produk</th>
                                    <th>Nama</th>
                                    <th>Harga Beli</th>
                                    <th>Stok</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    @if (Auth::user()->roles == 'admin')
                                        <th id="aksiTh" class="text-center hidden-print">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $total = 0;  
                                @endphp
                                @forelse ($items as $index => $item)
                                @php 
                                    $total += $item->purchase_price;  
                                @endphp
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">
                                            {{get_tanggal($item->tanggal)}}
                                        </td>
                                        <td>{{ $item->product_code }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>Rp. {{ number_format($item->purchase_price, 0,',','.') }}</td>
                                        <td>{{ $item->stock }}</td>
                                        <td>{{ $item->category->name }}</td>
                                        <td class="capitalize">{{ $item->status }}</td>
                                        @if (Auth::user()->roles == 'admin')
                                        <td id="aksiTd" class="text-center hidden-print">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('pembelian.edit', $item->id) }}" class="btn btn-success btn-icon icon-left">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('pembelian.destroy', $item->id) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger btn-icon icon-left btn-delete ml-1">
                                                        <i class="fas fa-trash-alt"></i> 
                                                    </button>
                                                </form>
                                                <form action="{{ route('pembelian.acc', $item->id) }}" method="post">
                                                    @csrf
                                                    @method('post')
                                                    <button type="submit" class="btn btn-primary btn-icon icon-left btn-acc ml-1">
                                                        <i class="fas fa-check"></i> 
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            Belum ada data produk.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                            @if(isset ($items))
                                    <tr bgcolor="yellow">
                                        <th colspan="4" class="text-center font-bold">TOTAL</th>
                                        <th class="text-end font-bold">Rp {{number_format($total, 0, ',', ',')}}</th>
                                        <th colspan="4"></th>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('addon-script')
<script src="{{ url('assets/modules/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ url('assets/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ url('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/modules/izitoast/js/iziToast.min.js') }}"></script>
<script src="{{ url('js/my_datatables.js')}}"></script>
<script src="{{ url('js/my_sweetalert.js')}}"></script>

<script>
    let elements = document.querySelectorAll(".btn-secondary");

    if (elements) {
        [...elements].forEach(element => {
            element.classList.add("print:hidden");
        });
    }

    let filter = document.getElementById('tablePembelian_filter');
    if (filter) {
        console.log("🚀 ~ filter:", filter)
        filter.classList.add("print:hidden");
    }
</script>
@endsection