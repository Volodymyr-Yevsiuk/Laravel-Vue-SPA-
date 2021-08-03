<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\Product\Product as ProductResource;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($q = $request->get('q')) {
            $products = Product::where('name', 'like', '%'.$q.'%')->paginate(15);
        } else {
            $productsQuery = Product::query();
            $products = $productsQuery->paginate(15);
        }

        $products->load('company');

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {   
        $image = Image::make($request->file('image'))->resize(270, 150);
        $rndStr = Str::uuid();
        $image->save(public_path().'/images/'.$rndStr.'.jpg');

        $data = $request->validated();
        $data['image'] = $rndStr.'.jpg';

        $product = Product::create($data);

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $product->load('company');

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            
            $image = $request->file('image');

            // resize uploaded image
            if ($image->isValid()){
                $image = Image::make($request->file('image'))->resize(270, 150);
                $rndStr = Str::random(10);
                $image->save(public_path().'/images/'.$rndStr.'.jpg');

                $data['image'] = $rndStr.'.jpg';
                if ($data['image'] != $product->image) {
                    unlink(public_path().'/images/'.$product->image);
                }
            }
        }

        $product->fill($data);
        $product->save();

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        if (file_exists(public_path().'/images/'.$product->image)) {
            unlink(public_path().'/images/'.$product->image);
        }

        return new ProductResource($product);
    }

    public function deleteSelectedProducts(Request $request) 
    {
        $products = Product::whereIn('id', $request->get('ids'));
        $images = $products->pluck('image');
        
        $products->delete();

        foreach ($images as $image) {
            if (file_exists(public_path().'/images/'.$image)) {
                unlink(public_path().'/images/'.$image);
            }  
        }
    }
}
