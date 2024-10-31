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

<p>この度は、{{ $donationProject }}にご寄付いただき、誠にありがとうございます。</p>

<p>寄付金額: ¥{{ number_format($donationAmount) }}</p>

<p>ご支援のおかげで、今後も{{$donationProject}}を欠かさず永続できます。</p>

<p>寄付控除証明書は来年の1月にメールにて送信させていただきます。</p>
<p>引き続きよろしくお願いいたします。</p>

<p>---------------------------------</p>
<p>認定NPO法人多文化共生教育ネットワークかながわ</p>
</body>
</html>
