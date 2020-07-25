<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\Collection;
use Auth;
use App\AccountParent;

class ParentController extends Controller
{

    public $successStatus = 200;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::guard('api')->user();

        $account_parent = AccountParent::select('id', 'id_business', 'parent_code', 'parent_name')
        ->whereHas('business.company', function ($query) use ($user) {
          $query->where('id_user', $user->id);
        })->get();

        return new Collection($account_parent);

    }

    public function indexChild()
    {
        $user = Auth::guard('api')->user();

        $account_parent = AccountParent::select('id', 'id_business', 'parent_code', 'parent_name')->with([
          'classification' => function ($query) {
            $query->select('id', 'id_parent', 'classification_code', 'classification_name');
          }, 
          'classification.account' => function ($query) {
            $query->select('id', 'id_classification', 'account_code', 'account_name', 'position');
          }
        ])
        ->whereHas('business.company', function ($query) use ($user) {
          $query->where('id_user', $user->id);
        })
        ->get();

        return new Collection($account_parent);

    }
}
