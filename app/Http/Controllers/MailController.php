<?php

namespace App\Http\Controllers;

use App\Mail\MailSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index(Request $request)
    {
        $mailData = [
            'title' => 'test mail from laravel',
            'body' => 'I am testing email from gmail',
        ];

        Mail::to('wasubisu69@gmail.com')->send(new MailSender());

        dd('Email sent successfully!');
    }
}
