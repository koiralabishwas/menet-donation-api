<?php

namespace App\Http\Controllers;

use App\Mail\Donor\DonorCheckEmailMailable;
use App\Services\MailInboxService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class XServerController extends Controller
{
    public function check_if_email_exists(Request $request): JsonResponse
    {
        App::setLocale($request['locale']);
        Mail::to($request['email'])->cc('menetplan@gmail.com')->send(new DonorCheckEmailMailable);

        sleep(8);

        $result = MailInboxService::checkIfEmailExist($request['email']);

        return response()->json(['exist' => $result, 'message' => 'Success'], 200);
    }
}
