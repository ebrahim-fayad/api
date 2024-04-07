<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\DistrictResource;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($city_id)
    // public function __invoke(Request $request)
    {
        // for test another way in postman
        // $districts = District::where('city_id', $request->input('city'))->get();
        $districts = District::where('city_id', $city_id)->get();
        if ($districts) {
            return ApiResponse::sendResponse(200, 'Cities Retrieved Successfully', DistrictResource::collection($districts));
        }
        return ApiResponse::sendResponse(200, 'Cities Retrieved Successfully', []);
    }
}
