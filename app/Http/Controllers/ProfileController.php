<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\ProfileFollowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $profile = Profile::join('users', 'users.id', '=', 'profiles.user_id')
            ->where('users.username', $request->username)
            ->first();

        if ($profile != null) {
            $data['profile'] = [
                'id' => $profile->id,
                'user_id' => $profile->user_id,
                'firstname' => $profile->firstname,
                'lastname' => $profile->lastname,
                'dob' => $profile->dob,
                'url_image' => $profile->url_image,
                'phonenumber' => $profile->phonenumber,
                'created_at' => $profile->created_at,
                'updated_at' => $profile->updated_at,
                'deleted_at' => $profile->deleted_at
            ];

            $data['user'] = [
                'id' => $profile->user_id,
                'username' => $profile->username,
                'email' => $profile->email
            ];

            $data['followers'] = ProfileFollowing::with('profileFollowers')->where('profile_following_id', $profile->id)->get();
            $data['followings'] = ProfileFollowing::with('profileFollowings')->where('profile_id', $profile->id)->get();

        }

        $response['message'] = 'success';
        $response['data'] = $data;

        return response()->json($response, 200);
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), Profile::$rules['update']);
        if ($validation->fails()) {
            $response['message'] = 'failed';
            $response['error'] = $validation->errors();

            return response()->json($response, 501);
        } else {
            $profile = Profile::find($request->id)->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'dob' => $request->dob,
                'url_image' => $request->url_image,
                'phonenumber' => $request->phonenumber,
            ]);

            $response['message'] = 'success';
            $response['data'] = $profile;

            return response()->json($response, 200);
        }

    }

    public function search(Request $request)
    {
        $data = Profile::join('users', 'users.id', '=', 'profiles.user_id')
            ->where(DB::raw('LOWER(users.username)'), 'LIKE', '%' . strtolower($request->search) . '%')
            ->orWhere(DB::raw('LOWER(profiles.firstname)'), 'LIKE', '%' . strtolower($request->search) . '%')
            ->orWhere(DB::raw('LOWER(profiles.lastname)'), 'LIKE', '%' . strtolower($request->search) . '%')
            ->get(["profiles.id","profiles.user_id","users.username",
                "users.email","profiles.firstname","profiles.lastname",
                "profiles.dob","profiles.url_image","profiles.phonenumber",
                "profiles.created_at","profiles.updated_at","profiles.deleted_at"]);

        $response['message'] = 'success';
        $response['data'] = $data;

        return response()->json($response, 200);
    }

    public function destroy(string $id)
    {
        //
    }
}
