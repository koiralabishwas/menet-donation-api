<?php

namespace App\Http\Controllers;

use App\Mail\DonationRegardMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index(Request $request)
    {
        $donationMetadata =[
            'donor_name' => "Bishwas Koirala Hello World",
            'donation_project' => 'altervoice',
            'amount' => 1000,
            'tax_deduction_certificate_url' => 'www.google.com'
        ];


        Mail::to('wasubisu69@gmail.com')->send(new DonationRegardMailable($donationMetadata));

        dd('Email sent successfully!');
    }
}
