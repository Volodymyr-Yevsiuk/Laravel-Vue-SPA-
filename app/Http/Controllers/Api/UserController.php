<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\User\User as UserResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($q = $request->get('q')) {
            $users = User::where('name', 'like', '%'.$q.'%')->paginate(15);
        } else {
            $users = User::paginate(15);
        }
        
        $users->load('role', 'companies');

        return UserResource::collection($users);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $user->load('role', 'companies');

        return UserResource::make($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->companies) {
            $relatedCompanies = $user->companies;
            
            foreach ($relatedCompanies as $company) {
                if (file_exists(public_path().'/images/'.$company->image)) {
                    unlink(public_path().'/images/'.$company->image);
                }
                if ($company->products) {
                    $relatedProducts = $company->products;
                    foreach ($relatedProducts as $product) {
                        if (file_exists(public_path().'/images/'.$product->image)) {
                            unlink(public_path().'/images/'.$product->image);
                        }
                    }
                }
            }
        }

        $user->delete();

        return new UserResource($user);
    }

    public function deleteSelectedUsers(Request $request) {
        $ids = $request->get('ids');
        $users = User::whereIn('id', $ids)->get();

        foreach ($ids as $id) {
            $this->destroy($id);
        }

        return UserResource::collection($users);
    }
}
