@extends('layouts.app')

@section('title', $title)

@section('addon-css')
<link rel="stylesheet" href="{{ url('assets/modules/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ url('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('assets/modules/izitoast/css/iziToast.min.css') }}">
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
                Berisi daftar barang tukar tambah.
            </p>

            <div class="card">
                <div class="card-body">
                <form action="{{ route('retur.report') }}" method="post">
                @csrf
                    <div class="form-group" style="width:300px;">
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
                    <div class="table-responsive">
                        <table class="table table-bordered" id="myTable">
                            <thead>                                 
                                <tr>
                                    <th class="text-center">
                                    #
                                    </th>
                                    <th>Tanggal</th>
                                    <th>Kode Transaksi</th>
                                    <th>Kode Produk</th>
                                    <th>Nama</th>
                                    <th>Harga Beli</th>
                                    <th>Jumlah Retur</th>
                                    <th>Kategori</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $item)
                                    @foreach ($produk as $barang)
                                        @if($item->product_id == $barang->id)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="text-center">{{get_tanggal($item->tanggal_retur)}}</td>
                                            <td>{{ $item->transaction_code }}</td>
                                            <td>{{ $barang->product_code }}</td>
                                            <td>{{ $barang->name }}</td>
                                            <td>Rp. {{ number_format($item->product_price, 0,',','.') }}</td>
                                            <td>{{ $item->jumlah_retur }}</td>
                                            <td>{{ $barang->category->name }}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            Belum ada data retur.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
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
@endsection