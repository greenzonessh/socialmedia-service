<?php

namespace App\Http\Controllers;

use App\Models\ProfileFollowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileFollowingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function storeFollowing(Request $request)
    {
        $validation = Validator::make($request->all(), ProfileFollowing::$rules['create']);
        if ($validation->fails()) {
            $response['message'] = 'failed';
            $response['error'] = $validation->errors();

            return response()->json($response, 501);
        } else {
            $check = ProfileFollowing::where('profile_id', $request->profile_id)->where('profile_following_id', $request->profile_following_id)->exists();
            if ($check) {
                $response['message'] = 'failed';
                $response['error'] = 'already followed';

                return response()->json($response, 501);
            } else {
                $profileFollower = ProfileFollowing::create($request->all());

                $response['message'] = 'success';
                $response['data'] = $profileFollower;
            }

            return response()->json($response, 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function showFollowing(Request $request)
    {
        $data = ProfileFollowing::with('profileFollowings')->where('profile_id', $request->profile_id)->get();

        $response['message'] = 'success';
        $response['data'] = $data;

        return response()->json($response, 200);
    }

    public function showFollower(Request $request)
    {
        $data = ProfileFollowing::with('profileFollowers')->where('profile_following_id', $request->profile_id)->get();

        $response['message'] = 'success';
        $response['data'] = $data;

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
}
