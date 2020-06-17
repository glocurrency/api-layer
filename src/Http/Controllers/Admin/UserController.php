<?php

namespace Glocurrency\ApiLayer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;

use Glocurrency\ApiLayer\Models\User;
use Glocurrency\ApiLayer\Http\Resources\UserResource;
use Glocurrency\ApiLayer\Http\Resources\AccessTokenResource;

class UserController
{
    public function getUsers(Request $request)
    {
        $users = User::all();

        return response()->json(UserResource::collection($users));
    }

    public function getUser(Request $request, $userId)
    {
        $validator = Validator::make($request->route()->parameters(), [
            'user_id' => 'bail|required|uuid|exists:Glocurrency\ApiLayer\Models\User,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user = User::findOrFail($request->user_id);

        return response()->json(new UserResource($user));
    }

    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codename' => 'bail|required|unique:Glocurrency\ApiLayer\Models\User,codename',
            'description' => 'nullable|min:3|max:255',
            'url' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user = User::create([
            'codename' => $request->codename,
            'description' => $request->description,
            'url' => $request->url,
            'enabled' => true,
        ]);

        return response()->json(new UserResource($user));
    }

    public function disableUser(Request $request, $userId)
    {
        $validator = Validator::make($request->route()->parameters(), [
            'user_id' => 'bail|required|uuid|exists:Glocurrency\ApiLayer\Models\User,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user = User::findOrFail($request->user_id);
        $user->enabled = false;
        $user->save();

        if ($user->hasAccessTokens()) {
            $user->tokens()->update(['revoked' => true]);
        }

        return response()->json(new UserResource($user));
    }

    public function enableUser(Request $request, $userId)
    {
        $validator = Validator::make($request->route()->parameters(), [
            'user_id' => 'bail|required|uuid|exists:Glocurrency\ApiLayer\Models\User,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user = User::findOrFail($request->user_id);
        $user->enabled = true;
        $user->save();

        return response()->json(new UserResource($user));
    }

    public function getAccessTokensForUser(Request $request, $userId)
    {
        $validator = Validator::make($request->route()->parameters(), [
            'user_id' => 'bail|required|uuid|exists:Glocurrency\ApiLayer\Models\User,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $tokens = User::find($request->user_id)->tokens()->get();

        return response()->json(AccessTokenResource::collection($tokens));
    }

    public function addAccessTokenForUser(Request $request, $userId)
    {
        $validator = Validator::make($request->route()->parameters(), [
            'user_id' => 'bail|required|uuid|exists:Glocurrency\ApiLayer\Models\User,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user = User::findOrfail($request->user_id);
        $createdToken = $this->createPersonalAccessTokenForUser($user);
        $token = $createdToken->token;

        // show accessToken when created
        $token->accessToken = $createdToken->accessToken;

        return response()->json(new AccessTokenResource($token));
    }

    public function rewokeAccessTokenForUser(Request $request, $userId, $accessTokenId)
    {
        $validator = Validator::make($request->route()->parameters(), [
            'user_id' => 'bail|required|uuid|exists:Glocurrency\ApiLayer\Models\User,id',
            'access_token_id' => [
                'bail',
                'required',
                Rule::exists(Passport::tokenModel(), 'id'),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $token = Token::findOrFail($request->access_token_id);
        $token->revoked = true;
        $token->save();

        return response()->json(new AccessTokenResource($token));
    }

    private function createPersonalAccessTokenForUser(User $user)
    {
        $tokenCountForUser = $user->tokens()->count();
        $tokenCountForUser += 1;
        return $user->createToken("api-customer-token # {$tokenCountForUser}", ['*']);
    }
}
