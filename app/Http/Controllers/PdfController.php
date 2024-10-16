<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Repositories\DonationRepository;
use App\Repositories\DonorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\PdfBuilder;
use function Spatie\LaravelPdf\Support\pdf;


class PdfController extends Controller
{
    public static function create($donor_external_id , $year): PdfBuilder // donor externalId を渡す？
    {
        $donor = DonorRepository::getDonorByExternalId($donor_external_id);
        $donations = DonationRepository::getDonationsByUserExternalId($donor_external_id);
        $certificateData = DonationRepository::getDonationCertificate($donor_external_id , $year);

        $total_amount = array_sum(array_column($certificateData, 'amount'));


        return pdf()->view('pdf.testpdf',[
            'donor' => $donor,
            'donations' => $certificateData,
            'total_amount' => $total_amount
        ])->format(Format::A4)->name('tested.pdf');
    }
}
