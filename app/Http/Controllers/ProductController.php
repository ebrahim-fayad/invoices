<?php

namespace App\Http\Controllers;


use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    function __construct()
    {

    $this->middleware('permission:المنتجات', ['only' => ['index']]);
    }

    public function index()
    {
        $sections = Section::all();
        $products = Product::all();
        return view('products.index',compact('sections','products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        Product::create([
            'product_name'=>$request->product_name,
            'description'=>$request->description,
            'section_id'=>$request->section_id,
        ]);
        return redirect()->route('products.index')->with('success','تم اضافة المنتج بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product->update([
            'product_name'=>$request->product_name,
            'description'=>$request->description,
            'section_id'=>$request->section_id,
        ]);
        return redirect()->route('products.index')->with('success','تم تعديل المنتج بنجاح'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success','تم حذف المنتج بنجاح'); 
    }
}
