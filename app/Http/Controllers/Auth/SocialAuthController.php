<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Auth;


class SocialAuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Get the user info from provider and check if user exist for specific provider
     * then log them in otherwise
     * create a new user then log them in
     * Once user is logged in then redirect to authenticated home page
     *
     * @return Response
     */
    public function callback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
            $input['name'] = $user->getName();
            $input['email'] = $user->getEmail();
            $input['provider'] = $provider;
            $input['provider_id'] = $user->getId();

            $authUser = $this->findOrCreate($input);
            Auth::loginUsingId($authUser->id);

            return redirect()->route('home');


        } catch (Exception $e) {

            return redirect('auth/'.$provider);

        }
    }
    public function findOrCreate($input){
        $checkIfExist = User::where('provider',$input['provider'])
            ->where('provider_id',$input['provider_id'])
            ->first();

        if($checkIfExist){
            return $checkIfExist;
        }

        return User::create($input);
    }
}
