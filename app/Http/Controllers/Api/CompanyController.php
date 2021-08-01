<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Http\Resources\Company\Company as CompanyResource;
use App\Http\Resources\Company\UpdateCompanyResource;
use App\Http\Requests\Company\StoreRequest;
use App\Http\Requests\Company\UpdateRequest;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($q = $request->get('q')) {
            $companies = Company::where('name', 'like', '%'.$q.'%')->paginate(10);
        } else {
            $companiesQuery = Company::query();
            $companies = $companiesQuery->paginate(10);
        }

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
        $image = Image::make($request->file('image'))->resize(270, 150);
        $rndStr = Str::random(15);
        $image->save(public_path().'/images/'.$rndStr.'.jpg');

        $data = $request->validated();
        $data['image'] = $rndStr.'.jpg';

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
    public function update(UpdateRequest $request, $id)
    {
        $company = Company::findOrFail($id);

        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            
            $image = $request->file('image');

            // resize uploaded image
            if ($image->isValid()){
                $image = Image::make($request->file('image'))->resize(270, 150);
                $rndStr = Str::uuid();
                $image->save(public_path().'/images/'.$rndStr.'.jpg');

                $data['image'] = $rndStr.'.jpg';
            }
        }

        $company->fill($data);
        $company->save();

        return new UpdateCompanyResource($company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return new CompanyResource($company);
    }

    public function getCompanyProducts ($id) {

        $user = User::findOrFail($id);
        $companies = $user->companies;

        return CompanyResource::collection($companies);
    }
}
