<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\Collection;
use Auth;
use App\Account;

class AccountController extends Controller
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

        $account = Account::select('id', 'id_classification', 'account_code', 'account_name', 'position')
          ->whereHas('classification.parent.business.company', function ($query) use ($user) {
            $query->where('id_user', $user->id);
          })->get();

        return new Collection($account);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function classification($id)
    {
        $account = Account::select('id', 'id_classification', 'account_code', 'account_name', 'position')
          ->where('id_classification', $id)->get();

        return new Collection($account);

    }
}
