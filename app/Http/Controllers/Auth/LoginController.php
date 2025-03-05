<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Org\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToProvider()
    {
        Log::info('Using realm: ' . config('services.keycloak.realm'));

        return Socialite::driver('keycloak')->redirect();
    }

    public function handleProviderCallback()
    {
        $user = Socialite::driver('keycloak')->user();

        $userJson = json_encode($user);
     //   dd($user);

        // Extract the value after 'CN='
        $manager = $user->user['Manager'];
        if (preg_match('/CN=([^,]+)/', $manager, $matches)) {
            $reportsTo = $matches[1];
        } else {
            $reportsTo = null; // Default to null if 'CN=' is not found
        }
//dd($user);
        // Store user data in session
        session(['keycloak_user' => $userJson]);
        session(['cu.name' => $user['name'] ?? null]);
        session(['cu.email' => $user['email'] ?? null]);
        session(['cu.staffno' => $user->user['preferred_username'] ?? null]);
        session(['cu.department' => $user->user['department'] ?? null]);
        session(['cu.group' => $user->user['roles']['user_group'] ?? null]);
        session(['cu.realm' => $user->user['roles']['realm'] ?? null]);
        session(['cu.client' => $user->user['roles']['client'] ?? null]);
        session(['cu.jobtitle' => $user->user['job_title'] ?? null]);
        session(['cu.reportsTo' => $reportsTo]);

    //    dd(session('cu'));
        $authUser = User::firstOrCreate([
            'email' => $user->email,
        ], [
            'name' => $user->name,
            'password' => bcrypt(Str::random(24)),
        ]);

        Auth::login($authUser, true);

        if (!empty($user->user['roles']['user_group'])) {
            $userGroups = array_map(function ($group) {
                return str_replace('/', 'ug_', $group);
            }, $user->user['roles']['user_group']);

            $realmRoles = [];
            if (!empty($user->user['roles']['realm'])) {
                $realmRoles = array_map(function ($role) {
                    return 'rr_' . $role;
                }, $user->user['roles']['realm']);
            }

            $ClientAppRoles = [];
            if (!empty($user->user['roles']['client'])) {
                $ClientAppRoles = array_map(function ($role) {
                    return 'rc_' . $role;
                }, $user->user['roles']['client']);
            }

            $allGroups = array_merge($userGroups, $realmRoles, $ClientAppRoles);

            $this->syncGroups($authUser, $allGroups);
        }
        //dd($user->user['preferred_username']);
        // if (!empty($user->user['preferred_username'])) {
        //     $staffno = $user->user['preferred_username'];
        //     // Directly update or create the Staff entry
        //     $staff = Staff::updateOrCreate(
        //         ['staffno' => $staffno], // Condition to check
        //         [
        //             'staffno' => $staffno, // Ensure staffno is included in the attributes
        //             'user_id' => $authUser->id // Values to update or create
        //         ]
        //     );
        // }
        $homeUrl = $authUser->userType->home ?? '/';

        return redirect()->intended($homeUrl);
    }
    private function syncGroups(User $authUser, array $groups)
    {
        $groupIds = [];
        foreach ($groups as $groupName) {
            $group = Group::firstOrCreate(['name' => $groupName]);
            $groupIds[] = $group->id;
        }
        $authUser->groups()->sync($groupIds);
    }
}
