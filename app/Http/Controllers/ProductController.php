<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

use App\Product;
use App\ProductCategory;

use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Daftar Produk";

        $items = Product::with([
            'category'
        ])->get();

        return view('pages.product.index', [
            'title' => $title,
            'items' => $items
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Create new product";

        $categories = ProductCategory::all();

        return view('pages.product.create', [
            'title' => $title,
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $photo = $request->file('photo');

        $data = $request->all();
        
        if ($photo){
            $data['photo'] = $photo->store(
                'assets/product', 'public'
            );
        }else{
            $data['photo'] = "";
        }

        $data['purchase_price'] = str_replace(',', '', $data['purchase_price']);
        $data['selling_price'] = null;

        Product::create($data);

        return redirect()->route('product.index')->with('success','Produk berhasil ditambahkan!');
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
        $title = "Edit Product";

        $item = Product::findOrFail($id);
        $categories = ProductCategory::all();

        return view('pages.product.edit', [
            'title' => $title,
            'item' => $item,
            'categories' => $categories
        ]);
    }

    public function report(Request $request)
    {
        $title = "Daftar Produk";

        $data = $request->all();
        $date = explode(' - ', $data['date']);

        $fromDate   = Carbon::parse($date[0])
            ->startOfDay()
            ->toDateTimeString();
        $toDate     = Carbon::parse($date[1])
            ->endOfDay()
            ->toDateTimeString();

        $items = Product::whereBetween('tanggal', [new Carbon($fromDate), new Carbon($toDate)])->get();

        return view('pages.product.index', [
            'title' => $title,
            'items' => $items
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $photo = $request->file('photo');

        $data = $request->all();
        $data['purchase_price'] = str_replace(',', '', $data['purchase_price']);
        // $data['selling_price'] = str_replace(',', '', $data['selling_price']);
        
        if ($photo){
            $data['photo'] = $photo->store(
                'assets/product', 'public'
            );
        }

        Product::findOrFail($id)->update($data);

        return redirect()->route('product.index')->with('success','Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return redirect()->route('product.index')->with('success','Produk berhasil dihapus!');
    }
}
