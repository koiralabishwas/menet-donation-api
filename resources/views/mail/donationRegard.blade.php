<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>寄付完了のお知らせ</title>
</head>
<body>
<p>{{ $donorName }}様、</p>

<p>この度は、{{ $donationProject }}プロジェクトにご寄付いただき、誠にありがとうございます。</p>

<p>寄付金額: ¥{{ number_format($donationAmount) }}</p>

<p>皆様からのご支援は、プロジェクトの成功に向けて大変貴重です。心より感謝申し上げます。</p>

<p><a href="{{ $donationCertificateUrl }}" target="_blank">寄付控除証明書を表示する</a></p>

<p>引き続きよろしくお願いいたします。</p>

<p>{{ config('app.name') }}</p>
</body>
</html>
