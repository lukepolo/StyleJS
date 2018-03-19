<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserProfileRequest;

class UserController extends Controller
{
    /**
     * @param UserProfileRequest $request
     *
     * @return UserResource
     */
    public function store(UserProfileRequest $request)
    {
        $user = $request->user();

        $user->update([
            'name'  => $request->get('name'),
            'email' => $request->get('email'),
        ]);

        return new UserResource($user);
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        if ($user->id == $id) {
            foreach ($user->repositories as $repository) {
                $repository->delete();
            }

            \Auth::user()->delete();

            return response()->json('OK');
        }

        return response()->json('Not Authorized', 401);
    }
}
