<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\Collection;
use Auth;
use App\AccountClassification;

class ClassificationController extends Controller
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

        $classification = AccountClassification::select('id', 'id_parent', 'classification_code', 'classification_name')
          ->whereHas('parent.business.company', function ($query) use ($user) {
            $query->where('id_user', $user->id);
          })->get();

        return new Collection($classification);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function parent($id)
    {
        $classification = AccountClassification::select('id', 'id_parent', 'classification_code', 'classification_name')
          ->where('id_parent', $id)->get();

        return new Collection($classification);

    }
}
