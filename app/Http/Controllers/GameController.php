<?php

namespace App\Http\Controllers;

use App\Models\Gamelog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GameController extends Controller
{

    public function index()
    {

        return Inertia::render('Games');
    }

    public function memory()
    {
        return Inertia::render('Memory');
    }

    public function memoryStart(Request $request)
    {

        $user = Auth::user();
        $user->credits = $user->credits - $request->get('wager');
        $user->save();
        return redirect()->route('game.memory');
    }

    public function memoryComplete(Request $request)
    {
        $hash = base64_decode($request->get('hash'));
        $hash = json_decode($hash, true);

        if($hash['timer'] != $request->get('timer')) {
            return response()->json(['message' => 'Invalid hash'], 500);
        }
        if($hash['wager'] != $request->get('wager')) {
            return response()->json(['message' => 'Invalid hash'], 500);
        }

        if($request->get('timer') < 30) {
            $user = Auth::user();
            $user->credits = $user->credits + $request->get('wager') * 2;
            $user->save();

            Gamelog::create([
                'user_id' => $user->id,
                'winnings' => $request->get('wager') * 2,
                'game' => 'memory'
            ]);
        }

        return redirect()->route('game.memory');
    }

    public function gift(Request $request){
        $user = Auth::user();
        if(Auth::user()->credits < $request->get('amount')) {
            return response()->json(['message' => 'Not enough credits'], 500);
        }
        $user->credits = $user->credits - $request->get('amount');
        $user->save();
        $giftUser = User::find($request->get('user'));
        $giftUser->credits = $giftUser->credits + $request->get('amount');
        $giftUser->save();
        return redirect()->route('games.index');
    }

    public function bragwall()
    {
        $gamelogs = Gamelog::with('user')->orderBy('created_at', 'desc')->limit(20)->get();
        return $gamelogs;
    }
}
