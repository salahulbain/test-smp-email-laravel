<?php

namespace App\Http\Controllers;

use App\Mail\DemoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendEmailController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'driver'     => 'required',
            'host'       => 'required',
            'port'       => 'required',
            'username'   => 'required',
            'password'   => 'required',
            'encryption' => 'required',
            'email'      => 'required',
        ]);

        config([
            'mail.mailers.testemail' => [
                'transport'  => $request->driver,
                'host'       => $request->host,
                'port'       => $request->port,
                'username'   => $request->username,
                'password'   => $request->password,
                'encryption' => $request->encryption,
            ],
        ]);

        $mailData = [
            'title' => 'Mail from Test Email Universitas Serambi Mekkah',
            'body' => 'This is for testing email using smtp.'
        ];

        try {
            Mail::mailer('testemail')
                ->to($request->email)
                ->send(new DemoMail($mailData));
            return response([
                [
                    'message' => 'email berhasil dikirim, periksa email ' . $request->email,
                    'code'    => 200
                ], 200
            ]);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return response([
                [
                    'code' => 500,
                    'message' => $message,
                ], 500
            ]);
        }
    }
}
