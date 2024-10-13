<?php

namespace App\Http\Controllers;

use App\Mail\DonationRegardMailable;
use App\Mail\MailSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index(Request $request)
    {
        $name = 'Bishwas Koirala';
        $project = 'altervoice';
        $amount = 1000;
        $certificateUrl = "https://www.google.com/";

        Mail::to('wasubisu69@gmail.com')->send(new DonationRegardMailable($name, $project, $amount, $certificateUrl));

        dd('Email sent successfully!');
    }
}
