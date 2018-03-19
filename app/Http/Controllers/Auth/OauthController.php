<?php

namespace App\Http\Controllers\Auth;

use Session;
use Socialite;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\RepositoryProvider;
use App\Http\Controllers\Controller;
use App\Models\User\UserLoginProvider;
use App\Models\User\UserRepositoryProvider;

class OauthController extends Controller
{
    const GITHUB = 'github';

    public static $repositoryProviders = [
        self::GITHUB,
    ];

    /**
     * Handles provider requests.
     *
     * @param Request $request
     * @param $provider
     *
     * @return mixed
     */
    public function link(Request $request, $provider)
    {
        Session::put('url.intended', $request->headers->get('referer'));

        $scopes = null;

        $providerDriver = Socialite::driver($provider);

        switch ($provider) {
            case self::GITHUB:
                $scopes = 'repo admin:repo_hook';
                break;
        }

        $providerDriver->scopes([$scopes]);

        return $providerDriver->redirect();
    }

    /**
     * Handles the request from the provider.
     *
     * @param $provider
     *
     * @return mixed
     */
    public function getHandleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            if (! \Auth::user()) {
                $this->loginSocialUser($provider, $socialUser);
            }

            $this->saveRepositoryProvider($provider, $socialUser);

            return redirect()->intended('/');
        } catch (\Exception $e) {
            return redirect('/')->withErrors($e->getMessage());
        }
    }

    /**
     * @param $provider
     * @param $socialUser
     * @return mixed
     * @throws \Exception
     */
    protected function loginSocialUser($provider, $socialUser)
    {
        $userProvider = UserLoginProvider::withTrashed()
            ->with('user')
            ->has('user')
            ->where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if (empty($userProvider)) {
            $newLoginProvider = $this->createLoginProvider($provider, $socialUser);
            $newUserModel = $this->createUser($socialUser, $newLoginProvider);
            \Auth::loginUsingId($newUserModel->id, true);
        } else {
            if ($userProvider->deleted_at) {
                $userProvider->restore();
            }
            \Auth::loginUsingId($userProvider->user->id, true);
        }
    }

    /**
     * Creates a new user.
     *
     * @param $user
     * @param UserLoginProvider $userLoginProvider
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function createUser($user, UserLoginProvider $userLoginProvider)
    {
        return User::create([
            'email' => $user->getEmail(),
            'user_login_provider_id' => $userLoginProvider->id,
            'name' => empty($user->getName()) ? $user->getEmail() : $user->getName(),
        ]);
    }

    /**
     * Disconnects a service provider.
     *
     * @param $providerType
     * @param int $serviceID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDisconnectService($providerType, int $serviceID)
    {
        if (UserRepositoryProvider::class == $providerType) {
            if (! empty($userRepositoryProvider = \Auth::user()->userRepositoryProviders->where('id',
                $serviceID)->first())
            ) {
                $userRepositoryProvider->delete();
            }
        }

        return response()->json('OK');
    }

    /**
     * Creates a login provider.
     *
     * @param $provider
     * @param $user
     *
     * @return mixed
     */
    protected function createLoginProvider($provider, $user)
    {
        $userLoginProvider = UserLoginProvider::withTrashed()->firstOrNew([
            'provider'    => $provider,
            'provider_id' => $user->getId(),
        ]);

        $userLoginProvider->fill([
            'token'         => $user->token,
            'expires_in'    => isset($user->expiresIn) ? $user->expiresIn : null,
            'refresh_token' => isset($user->refreshToken) ? $user->refreshToken : null,
        ]);

        $userLoginProvider->save();

        return $userLoginProvider;
    }

    /**
     * Saves the users repository provider.
     *
     * @param $provider
     * @param $user
     *
     * @return mixed
     */
    protected function saveRepositoryProvider($provider, $user)
    {
        $userRepositoryProvider = UserRepositoryProvider::withTrashed()->firstOrNew([
            'repository_provider_id' => RepositoryProvider::where('provider_name', $provider)->first()->id,
            'provider_id'            => $user->getId(),
        ]);

        $userRepositoryProvider->fill([
            'token'         => $user->token,
            'user_id'       => \Auth::user()->id,
            'expires_in'    => isset($user->expiresIn) ? $user->expiresIn : null,
            'refresh_token' => isset($user->refreshToken) ? $user->refreshToken : null,
        ]);

        $userRepositoryProvider->save();

        $userRepositoryProvider->restore();

        return $userRepositoryProvider;
    }
}
