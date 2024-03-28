<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class AutosuggestController extends Controller
{
    // public function suggestions(Request $request)
    // {
    //     $q = $request->get('kode');
    //     $suggestions = array("Apple", "Banana", "Cherry", "Date", "Fig", "Grape");

    //     $hint = "";
    //     if (!empty($q)) {
    //         $q = strtolower($q);
    //         $len = strlen($q);
    //         foreach ($suggestions as $suggestion) {
    //             if (stristr($q, substr($suggestion, 0, $len))) {
    //                 if ($hint === "") {
    //                     $hint = $suggestion;
    //                 } else {
    //                     $hint .= ", $suggestion";
    //                 }
    //             }
    //         }
    //     }

    //     return response()->json(['suggestions' => $hint === "" ? "No suggestions" : $hint]);
    // }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');

        $results = Product::where('product_code', 'like', '%' . $query . '%')->limit(10)->get(); // Ubah sesuai kebutuhan

        return response()->json($results);
    }
}
