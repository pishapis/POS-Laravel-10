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
                Daftar transaksi yang sudah dilakukan.
            </p>
            <div class="card">
                <div class="card-body">
                <form action="{{ route('transaction.report') }}" method="post">
                @csrf
                    <div class="print:hidden mb-2" style="width:350px;">
                        <label>Rentang Waktu</label>
                        <div class="flex">
                            <div class="input-group" style="width:350px;">
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
                <form action="{{ route('sale.store') }}" method="POST">
                @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered" id="myTable">
                            <thead>                                 
                                <tr>
                                    <th class="text-center">
                                    #
                                    </th>
                                    <th>Kode Item</th>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Harga Satuan</th>
                                    <th>Harga</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $total = 0;  
                                @endphp
                                @forelse ($items as $index => $item)
                                @php 
                                    $total += $item->grand_total;  
                                @endphp
                                @foreach ($sales as $sale)
                                    @if($item->transaction_code == $sale->transaction_code)
                                        @foreach ($produk as $barang)
                                            @if($barang->id == $sale->product_id)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $barang->product_code }}</td>
                                                    <td>{{ get_tanggal($item->tanggal) }}</td>
                                                    <td class="capitalize">{{ $barang->name }}</td>
                                                    <td>{{ $sale->quantity }}</td>
                                                    <td>{{ number_format($item->sub_total, 0, ',', ',') }}</td>
                                                    <td>{{ number_format($item->grand_total, 0, ',', ',') }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ route('transaction.struk', $item->transaction_code) }}" class="btn btn-primary btn-icon icon-left">
                                                            <i class="fas fa-eye"></i> Lihat
                                                        </a>
                                                        <a href="#" class="btn btn-primary btn-icon icon-left" data-toggle="modal" data-target="#modalItem-{{ $index + 1 }}">
                                                            <i class="fa fa-undo"></i> Retur
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            Belum ada data transaksi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                @if(isset ($items))
                                    <tr bgcolor="yellow" class="text-center font-bold">
                                        <th colspan="6">TOTAL</th>
                                        <th colspan="2" class="text-left">Rp {{number_format($total, 0, ',', ',')}}</th>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </section>
</div>
@foreach ($items as $index => $item)
    @foreach ($sales as $sale)
        @if($item->transaction_code == $sale->transaction_code)
            @foreach ($produk as $barang)
                @if($barang->id == $sale->product_id)
                    <div class="modal fade" tabindex="-1" role="dialog" id="modalItem-{{ $index + 1 }}">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="" method="POST">
                                    @method('GET')
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tampil Data Retur</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-1 text-center font-medium text-lg">Jumlah Retur</div>
                                        <div class="input-group flex justify-center">
                                            <div>
                                                <input type="number" class="h-20 w-20 bg-slate-700 text-center text-white rounded-full border text-3xl" id="jml_retur-{{ $index + 1 }}" name="jml_retur" value="" required>
                                                <input type="hidden" name="saleId" value="{{$sale->id}}"/>
                                            </div>
                                        </div>
                                        <div id="validator-jml_retur{{ $index + 1 }}" class="text-red-500 font-semibold mb-2 text-center"></div>
                                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                            <table class="w-full text-sm text-left rtl:text-right text-gray-100">
                                                <thead id="tableKu" class="text-xs uppercase bg-gray-700 text-white">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3">
                                                            #
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                            Kode Transaksi
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                            Barang
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                            Qty
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                            Harga Satuan
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                            Harga Total
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr id="isiTrFeedback" class="bg-white border-b font-semibold">
                                                        <td class="p-4 text-gray-900">
                                                            {{$index + 1}}
                                                        </td>
                                                        <td class="px-6 py-4 text-gray-900 capitalize">
                                                            {{$sale->transaction_code}}
                                                        </td>
                                                        <td class="px-6 py-4 text-gray-900">
                                                            {{$barang->name}}
                                                        </td>
                                                        <td class="px-6 py-4 text-gray-900 capitalize">
                                                            {{$sale->quantity}}
                                                        </td>
                                                        <td class="px-6 py-4 text-gray-900 capitalize">
                                                            {{number_format($sale->product_price, 0, ',', ',')}}
                                                        </td>
                                                        <td class="px-6 py-4 text-gray-900 capitalize">
                                                            {{number_format($sale->total_price, 0, ',', ',')}}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-whitesmoke br">
                                        <button type="button" class="btn btn-primary" onclick="deleteSale('{{$sale->id}}','{{$sale->quantity}}','{{ $index + 1 }}')" data-namaproduk="{{$barang->name}}">Retur</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    @endforeach
@endforeach
@endsection

@section('addon-script')
<script src="{{ url('assets/modules/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ url('assets/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ url('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/modules/izitoast/js/iziToast.min.js') }}"></script>
<script src="{{ url('js/my_datatables.js')}}"></script>
<script src="{{ url('js/my_sweetalert.js')}}"></script>
@endsection

<script>
let signal = {};
signal.item = <?=json_encode($items)?>;
signal.sale = <?=json_encode($sales)?>;
signal.produk = <?=json_encode($produk)?>;

function deleteSale(saleId, qty, i) {
    let jmlRetur = document.getElementById(`jml_retur-${i}`);
    let validator = document.getElementById(`validator-jml_retur${i}`);
    if(jmlRetur.value == "" || jmlRetur.value == 0){
        validator.innerText = "Jumlah Retur Wajib Diisi!";
        return false;
    }else{
        validator.innerText = "";
    }
    if(jmlRetur.value > qty){
        ALERT("Jumlah retur melebihi pembelian","bad");
        return false;
    }
    qtyIdSale = [saleId, jmlRetur.value];
    if (confirm('Yakin ingin meretur barang ini?')) {
        fetch(`/sale/hapus/${qtyIdSale}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                ALERT("berhasil retur barang","ok");
                location.reload();
            } else {
                console.error('Failed to delete sale');
            }
        })
        .catch(error => {
            console.error('Error deleting sale:', error);
        });
    }
}

function closeModal(divModal, transisimodal){
    let modalUpload = document.getElementById("divModalUpload");
    let divdata = document.getElementById("divData");
    modalUpload.classList.add("hidden");
    divdata.innerHTML = "";
}
</script>