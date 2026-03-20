<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected function resolveRequest(string $token): DocumentRequest
    {
        $hashedToken = hash('sha256', trim($token));

        $documentRequest = DocumentRequest::with('client')
            ->where('access_token', $hashedToken)
            ->firstOrFail();

        if (!$documentRequest->client) {
            abort(404);
        }

        if (Auth::guard('client')->check()) {

            $loggedInClient = Auth::guard('client')->user();

            // 🔒 Strict isolation check
            if (
                $loggedInClient->id !== $documentRequest->client_id ||
                $loggedInClient->owner_id !== $documentRequest->owner_id
            ) {
                Auth::guard('client')->logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();

                abort(403, 'Unauthorized portal access.');
            }
        }

        return $documentRequest;
    }
    public function showActivate(string $token)
    {
        $documentRequest = $this->resolveRequest($token);
        $client = $documentRequest->client;

        if ($client->password) {
            return redirect()->route('portal.login', $token);
        }

        return view('portal.auth.activate', compact('client', 'token'));
    }

    public function activate(Request $request, string $token)
    {
        $documentRequest = $this->resolveRequest($token);
        $client = $documentRequest->client;

        if ($client->password) {
            return redirect()->route('portal.login', $token);
        }

        $request->validate([
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $client->update([
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('client')->login($client);

        return redirect()->route('portal.access', $token);
    }

    public function showLogin(string $token)
    {
        $documentRequest = $this->resolveRequest($token);
        $client = $documentRequest->client;

        if (!$client->password) {
            return redirect()->route('portal.activate', $token);
        }

        return view('portal.auth.login', compact('client', 'token'));
    }

    public function login(Request $request, string $token)
    {
        $documentRequest = $this->resolveRequest($token);
        $client = $documentRequest->client;

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (
            $request->email !== $client->email ||
            !Hash::check($request->password, $client->password)
        ) {
            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ]);
        }

        Auth::guard('client')->login($client);

        return redirect()->route('portal.access', $token);
    }

    public function logout(Request $request)
    {
        Auth::guard('client')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->back();
    }
}
