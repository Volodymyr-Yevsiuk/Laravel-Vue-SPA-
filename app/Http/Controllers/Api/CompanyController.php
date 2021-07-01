<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Resources\Company\Company as CompanyResource;
use App\Http\Resources\Product\Product as ProductResource;
use App\Http\Requests\Company\StoreRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companiesQuery = Company::query();
        $companies = $companiesQuery->paginate(10);

        $companies->load('user');

        return CompanyResource::collection($companies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $pathToFile = $request->file('image')->store('images', 'public');
        $data = $request->validated();
        $data['image'] = $request->image->hashName();

        $company = Company::create($data);

        return new CompanyResource($company);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Company::findOrFail($id);
        $company->load('user');

        return CompanyResource::make($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getCompanyProducts ($id) {
        $company = Company::findOrFail($id);

        $products = $company->products;
        $products->load('company');
        
        return ProductResource::collection($products);
    }
}
