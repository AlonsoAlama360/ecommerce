<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Province;

class UbigeoController extends Controller
{
    public function departments()
    {
        return response()->json(Department::orderBy('name')->get(['id', 'name']));
    }

    public function provinces(int $departmentId)
    {
        return response()->json(
            Province::where('department_id', $departmentId)->orderBy('name')->get(['id', 'name'])
        );
    }

    public function districts(int $provinceId)
    {
        return response()->json(
            \App\Models\District::where('province_id', $provinceId)->orderBy('name')->get(['id', 'name'])
        );
    }
}
