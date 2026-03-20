<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------
    | Show Profile
    |--------------------------------------------------------------------------
    */
    public function edit()
    {
        $user = Auth::user();

        if (!$user->is_active) {
            abort(403, 'Account is inactive.');
        }

        return view('profile.edit', compact('user'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Profile OR Password
    |--------------------------------------------------------------------------
    */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->is_active) {
            abort(403, 'Account is inactive.');
        }

        /*
        |--------------------------------------------------------------------------
        | Detect Password Update (Security Tab)
        |--------------------------------------------------------------------------
        */
        if ($request->filled('password')) {

            $validated = $request->validate([
                'current_password' => ['required'],
                'password'         => ['required', 'confirmed', 'min:8'],
            ]);

            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Current password is incorrect.'])
                    ->withInput();
            }

            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()
                ->route('profile.edit')
                ->with('success', 'Password updated successfully.');
        }

        /*
        |--------------------------------------------------------------------------
        | Profile Information Update (Profile Tab)
        |--------------------------------------------------------------------------
        */
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:150'],
            'last_name'  => ['required', 'string', 'max:150'],
            'email'      => [
                'required',
                'email',
                'max:150',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone'      => ['nullable', 'string', 'max:25'],
            'job_title'  => ['nullable', 'string', 'max:255'],
            'avatar'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Handle Avatar Upload
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('avatar')) {

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = $request
                ->file('avatar')
                ->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Account (Soft Delete)
    |--------------------------------------------------------------------------
    */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Incorrect password.',
            ]);
        }

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();
        $user->delete();

        return redirect('/')
            ->with('success', 'Account deleted successfully.');
    }
}
