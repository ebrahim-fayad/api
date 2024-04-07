<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewAdRequest;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::paginate(2);
        if ($ads) {
            if ($ads->total() > $ads->perPage()) {
                $data=[
                    'records'=>AdResource::collection($ads),
                    'pagination Links'=>[
                        'current page'=> $ads->currentPage(),
                        'per page'=> $ads->perPage(),
                        'total'=> $ads->total(),
                        'Links'=>[
                            'first' => $ads->url(1),
                            'next page'=>$ads->nextPageUrl(),
                            'pervious page'=>$ads->previousPageUrl(),
                            'last' => $ads->url($ads->lastPage()),
                        ]
                    ]
                ];

                return ApiResponse::sendResponse(200, 'Ads Retrieved Successfully', $data);
            } else {
                return ApiResponse::sendResponse(200, 'Ads Retrieved Successfully', AdResource::collection($ads));
            }

        }
        return ApiResponse::sendResponse(200, 'There Is No Data To Show');
    }
    public function latest()
    {
        $ads = Ad::latest()->take(2)->get();
        if ($ads) {
            return ApiResponse::sendResponse(200, 'Latest Ads Retrieved Successfully', AdResource::collection($ads));
        }
        return ApiResponse::sendResponse(200, 'There Is No Ads To Retrieving');
    }
    public function domain($domain_id)
    {
        $ads = Ad::where('domain_id',$domain_id)->get();
        if ($ads) {
            return ApiResponse::sendResponse(200, 'Latest Ads Retrieved Successfully', AdResource::collection($ads));
        }
        return ApiResponse::sendResponse(200, 'There Is No Ads To Retrieving');
    }
    public function search(Request $request)
    {
        $word = $request->has('search') ? $request->input('search') : null;
        $ads = Ad::when($word != null, function ($q) use ($word) {
            $q->where('title', 'like', "%$word%");
        })->latest()->get();
        if ($ads) {
            return ApiResponse::sendResponse(200, 'Search Completed', AdResource::collection($ads));
        }
        return ApiResponse::sendResponse(200, 'No Much Data');
    }
    public function create( NewAdRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $record = Ad::create($data);
        if ($record) {
            return ApiResponse::sendResponse(201, 'New Ad Created Successfully', new AdResource($record));
        }
    }
    public function update(NewAdRequest $request,$ad_id)
    {
        $ad = Ad::findOrFail($ad_id);
        if ($ad->user_id != $request->user()->id) {
            return ApiResponse::sendResponse(403, 'You Aren\'t allowed to take this action');
        }
        $data = $request->validated();
        $updating = $ad->update($data);
        if ($updating)  return ApiResponse::sendResponse(201, 'Your Ad Updated Successfully', new AdResource($ad));

    }
    public function delete(Request $request,$ad_id)
    {
        $adExists = Ad::where('id', $ad_id)->exists();
        if (!$adExists) {
            return ApiResponse::sendResponse(404, 'There Is No Ad To Delete',[]);
        }
        $ad = Ad::findOrFail($ad_id);
        if ($ad->user_id != $request->user()->id) {
            return ApiResponse::sendResponse(403, 'You Aren\'t allowed to take this action');
        }

        $success = $ad->delete();
        if ($success)  return ApiResponse::sendResponse(200, 'Your Ad Deleted Successfully');

    }
    public function userAds(Request $request)
    {
        $ads = Ad::where('user_id', $request->user()->id)->paginate(2);
        if ($ads) {
            if ($ads->total() > $ads->perPage() ) {
                $data = [
                    'records' => AdResource::collection($ads),
                    'pagination Links' => [
                        'current page' => $ads->currentPage(),
                        'per page' => $ads->perPage(),
                        'total' => $ads->total(),
                        'Links' => [
                            'first' => $ads->url(1),
                            'next page' => $ads->nextPageUrl(),
                            'pervious page' => $ads->previousPageUrl(),
                            'last' => $ads->url($ads->lastPage()),
                        ]
                    ]
                ];
                return ApiResponse::sendResponse(200, 'Ads Retrieved Successfully', $data);
            }else{
                return ApiResponse::sendResponse(200, 'Ads Retrieved Successfully', AdResource::collection($ads));
            }
        }
        return ApiResponse::sendResponse(200, 'There Is No Ads For Retrieving');
    }
}
