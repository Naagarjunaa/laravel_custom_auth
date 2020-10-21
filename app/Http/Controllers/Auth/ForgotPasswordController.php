<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
// use Mail;

class ForgotPasswordController extends Controller
{
    public function getEmail()
    {
        return view('auth.password.email');
    }

    public function postEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users'
        ]);

        $faker = \Faker\Factory::create();
        $token = $faker->uuid;

        DB::table('password_resets')->insert(
            ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
        );

        Mail::send('auth.verify', ['token' => $token], function ($message) use ($request) {
            $message->from($request->email);
            $message->to('arjunravikkumar007@gmail.com');
            $message->subject('Reset Password Notification');
        });

        return back()->with('message', 'We have e-mailed your password reset link!');
    }
}
