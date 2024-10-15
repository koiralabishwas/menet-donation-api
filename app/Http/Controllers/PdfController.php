<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\PdfBuilder;
use function Spatie\LaravelPdf\Support\pdf;


class PdfController extends Controller
{
    public static function create(Request $request , $donor_external_id): PdfBuilder // donor externalId を渡す？
    {
        $data = [
            "name" => "Bishwas Koirala",
            "donor_external_id" => $donor_external_id,
            "donation" => "123092",
            "amount" => "1990",
        ];
        return pdf()->view('pdf.testpdf',['data' => $data])->format(Format::A4)->name('tested.pdf');
    }
}
