<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Stripe\SetupIntent;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function create()
    {
        $intent = Auth::user()->createSetupIntent();

        return view('subscription.create', compact('intent'));
    }

    public function store(Request $request)
    {
        // $user = Auth::user();

        $request->user()->newSubscription(
            'premium_plan', 'price_1PV7xYRrKTrlS1PyjGVwCKNL'
        )->create($request->paymentMethodId);

        return redirect()->route('home')->with('flash_message', '有料プランへの登録が完了しました。');
    }

    public function edit()
    {
        $user = Auth::user();
        $intent = Auth::user()->createSetupIntent();

        return view('subscription.edit', compact('user', 'intent'));
    }

    public function  update(Request $request, User $user)
    {
        $request->user()->updateDefaultPaymentMethod($request->paymentMethodId);

        return redirect()->route('home')->with('flash_message', 'お支払方法を変更しました。');
    }

    public function cancel()
    {
        return view('subscription.cancel');
    }

    public function destroy(User $user)
    {
        $user = Auth::user()->subscription('premium_plan')->cancelNow();

        $user->delete();

        return redirect()->route('home',compact('user'))->with('flash_message', '有料プランを解約しました。');
    }
}
