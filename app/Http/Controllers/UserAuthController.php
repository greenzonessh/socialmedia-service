<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    /**
     * Register User
     */
    public function register(Request $request)
    {
        $validationUser = Validator::make($request->all(), User::$rules['create']);
        $validationProfile = Validator::make($request->all(), Profile::$rules['create']);
        if ($validationUser->fails()) {
            $response['message'] = 'failed';
            $response['error'] = $validationUser->errors();

            return response()->json($response, 501);
        } else if ($validationProfile->fails()){
            $response['message'] = 'failed';
            $response['error'] = $validationProfile->errors();

            return response()->json($response, 501);
        } else {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            if ($user) {
                try {
                    $dataProfile = $request->all();
                    $dataProfile['user_id'] = $user->id;
                    Profile::create($dataProfile);

                    $token = $user->createToken($user->id+Carbon::now()->timestamp)->accessToken;

                    $response['message'] = 'success';
                    $response['data'] = $user;
                    $response['token'] = $token;
                    return response()->json($response, 200);
                } catch (\Exception $e) {
                    $user->forceDelete();

                    $response['message'] = 'failed';
                    $response['error'] = $e->getMessage();
                    return response()->json($response, 501);
                }

            } else {
                $response['message'] = 'failed';
                $response['error'] = $user;
                return response()->json($response, 501);
            }
        }
    }

    /**
     * Login.
     */
    public function login(Request $request)
    {
        if ($request->has('username')
            && $request->filled('username')
            && $request->username != null && $request->username != " ")
        {
            $data = [
                'username' => $request->username,
                'password' => $request->password
            ];
        } else if ($request->has('email')
            && $request->filled('email')
            && $request->email != null && $request->email != " ")
        {
            $data = [
                'email' => $request->email,
                'password' => $request->password
            ];
        }

        if (Auth::attempt($data)) {
            $token = Auth::user()->createToken(Auth::user()->id+Carbon::now()->timestamp)->accessToken;

            $response['message'] = 'success';
            $response['data'] = Auth::user();
            $response['token'] = $token;
            return response()->json($response, 200);
        } else {
            $response['message'] = 'failed';
            $response['error'] = 'Unauthorized';
            return response()->json($response, 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
