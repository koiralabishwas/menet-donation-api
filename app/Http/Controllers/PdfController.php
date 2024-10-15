<?php

namespace App\Http\Controllers;

use function Spatie\LaravelPdf\Support\pdf;


class PdfController extends Controller
{
    public static function create() // donor externalId を渡す？
    {
        $data = [
            "name" => "Bishwas Koirala",
            "donation" => "123092",
            "amount" => "1990"
        ];
        return pdf()->view('pdf.testpdf',['data' => $data])->name('tested.pdf');
    }
}
