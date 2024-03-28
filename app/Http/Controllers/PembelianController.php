<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

use App\Product;
use App\Pembelian;
use App\ProductCategory;

use App\Http\Requests\PembelianRequest;

class PembelianController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index()
     {
        $title = "Daftar Pembelian Produk";

        $items = Pembelian::with(['category'])->get();

        return view('pages.pembelian.index', [
            'title' => $title,
            'items' => $items
        ]);
     }

     public function create()
    {
        $title = "Buat Pembelian Product";

        $categories = ProductCategory::all();
        $produk = Product::all();

        return view('pages.pembelian.create', [
            'title' => $title,
            'categories' => $categories,
            'produk'=>$produk
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PembelianRequest $request)
    {
        $data = $request->all();

        $data['purchase_price'] = str_replace(',', '', $data['purchase_price']);
        $data['status'] = "belum acc";

        Pembelian::create($data);

        return redirect()->route('pembelian.index')->with('success','Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = "Edit Pembelian";

        $item = Pembelian::findOrFail($id);
        $categories = ProductCategory::all();

        return view('pages.pembelian.edit', [
            'title' => $title,
            'item' => $item,
            'categories' => $categories
        ]);
    }

    public function report(Request $request)
    {
        $title = "Daftar Pembelian Produk";

        $data = $request->all();
        $date = explode(' - ', $data['date']);

        $fromDate   = Carbon::parse($date[0])
            ->startOfDay()
            ->toDateTimeString();
        $toDate     = Carbon::parse($date[1])
            ->endOfDay()
            ->toDateTimeString();

        $items = Pembelian::whereBetween('tanggal', [new Carbon($fromDate), new Carbon($toDate)])->get();

        return view('pages.pembelian.index', [
            'title' => $title,
            'items' => $items
        ]);
    }

    public function acc($id){
        $pembelian = Pembelian::where('id',$id)->get();
        if ($pembelian->isEmpty()) {
            return redirect()->back()->withErrors('Produk tidak ditemukan.');
        }

        foreach($pembelian as $beli){
            $beliId = $beli->id;
            $code = $beli->product_code;
            $name = $beli->name;
            $purchase = $beli->purchase_price;
            $qty = $beli->stock;
            $kategori = $beli->category_id;
            $status = $beli->status;
        }

        if($status == "acc"){
                return redirect()->route('pembelian.index')->with('fail','Produk ini sudah pernah diacc!');
        }

        $produk = Product::where('product_code', $code)->get();
        if ($produk->isEmpty()) {
            if ($qty == 0 || $name == ""){
                return redirect()->back()->with('fail','Qty atau nama barang tidak boleh kosong!');
            }else{
                $create = [
                    'product_code' => $code,
                    'photo' => "",
                    'tanggal'=>date("Y-m-d H:i:s"),
                    'name'=> $name,
                    'purchase_price' => $purchase,
                    'stock' => $qty,
                    'category_id' => $kategori
                ];
                $produk = Product::create($create);
            }
        }else{
            foreach ($produk as $value) {
                $productId = $value->id;
                $qtyProduct = $value->stock;
            }
            $qtyJadi = $qtyProduct + $qty;
            $produkUpdate = 
            [
                "stock" => $qtyJadi,
                "purchase_price" => $purchase
            ];
            $produk = Product::where('product_code', $code)->update($produkUpdate);
        }

        $update = ["status"=>"acc"];
        Pembelian::where('id', $beliId)->update($update);
        return redirect()->route('pembelian.index')->with('success','Produk berhasil diacc!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PembelianRequest $request, $id)
    {
        $pembelian = Pembelian::where('id',$id)->get();
        if ($pembelian->isEmpty()) {
            return redirect()->back()->withErrors('Produk tidak ditemukan.');
        }

        foreach($pembelian as $beli){
            $status = $beli->status;
        }
        
        if($status == "acc"){
                return redirect()->route('pembelian.index')->with('fail','Produk ini sudah pernah diacc!');
        }

        $data = $request->all();
        $data['purchase_price'] = str_replace(',', '', $data['purchase_price']);
        // $data['selling_price'] = str_replace(',', '', $data['selling_price']);

        Pembelian::findOrFail($id)->update($data);

        return redirect()->route('pembelian.index')->with('success','Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pembelian::findOrFail($id)->delete();

        return redirect()->route('pembelian.index')->with('success','Produk berhasil dihapus!');
    }
}