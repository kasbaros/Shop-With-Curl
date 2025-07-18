<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompareController extends Controller
{
    /**
     * Display the product comparison page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // In a real application, you would fetch the compared products from the session or database
        $comparedProducts = session('compared_products', []);

        return view('compare.index', compact('comparedProducts'));
    }

    /**
     * Add a product to the comparison list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->input('product_id');

        // In a real application, you would add the product to the comparison list in the session or database
        $comparedProducts = session('compared_products', []);

        if (!in_array($productId, $comparedProducts)) {
            $comparedProducts[] = $productId;
            session(['compared_products' => $comparedProducts]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to comparison list.',
            'count' => count($comparedProducts)
        ]);
    }

    /**
     * Remove a product from the comparison list.
     *
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function remove($product)
    {
        // In a real application, you would remove the product from the comparison list in the session or database
        $comparedProducts = session('compared_products', []);

        $comparedProducts = array_filter($comparedProducts, function($id) use ($product) {
            return $id != $product;
        });

        session(['compared_products' => array_values($comparedProducts)]);

        return redirect()->route('compare.index')
            ->with('success', 'Product removed from comparison list.');
    }
}
