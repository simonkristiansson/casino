<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GameController extends Controller
{
    public function index()
    {

        return Inertia::render('Games', [
            'auth' => [
                'user' => auth()->user(),
            ]
            ]
        );
    }

    public function startGame(Request $request)
    {

        $user = Auth::user();
        $user->credits = $user->credits - $request->get('wager');
        $user->save();
        return Inertia::render('Games', [
            'auth' => [
                'user' => auth()->user(),
            ]
            ]
        );
    }
}
