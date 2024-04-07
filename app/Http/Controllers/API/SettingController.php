<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $setting = Setting::find(1);
        // $setting = Setting::all();
        // return  $setting;
        // return new SettingResource($setting);
        if ($setting) {
            return ApiResponse::sendResponse(200, 'settings Retrieved successfully', new SettingResource($setting));
            // return ApiResponse::sendResponse(200, 'settings Retrieved successfully', SettingResource::collection($setting));
        }
        return ApiResponse::sendResponse(200, 'settings NoT Founded');
        // return SettingResource::collection($setting);

    }
}
