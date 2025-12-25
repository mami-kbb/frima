<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\Order;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        $profile = $user->profile;

        $tab = $request->get('page', 'sell');

        if ($tab === 'sell') {
            $query = Item::query()->orderBy('created_at', 'desc');

            if (auth()->check()) {
                $query->where('user_id', auth()->id());
            }

            $items = $query->get();
        }

        if ($tab === 'buy') {
            $items = $user->orders()->with('item')
            ->get()
            ->pluck('item');
        }

        return view('auth.profile', compact('user', 'profile', 'items', 'tab'));
    }

    public function edit()
    {
        $user = Auth::user();

        $profile = $user->profile;

        return view('auth.profile_edit', compact('user', 'profile'));
    }

    public function update(ProfileRequest $request)
    {

        $user = Auth::user();

        $isFirstTime = !$user->profile()->exists();

        $profile = $user->profile()->firstOrNew([]);

        if ($request->hasFile('profile_image')) {
                    $path = $request->file('profile_image')->store('profiles', 'public');
                    $profile->profile_image = $path;
        }

        $user->name = $request->name;
        $user->save();

        $profile->postal_code = $request->postal_code;
        $profile->address = $request->address;
        $profile->building = $request->building;
        $profile->save();

        if ($isFirstTime) {
            return redirect('/');
        }

        return redirect('/mypage');
    }
}
