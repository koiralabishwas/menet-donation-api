@component('mail::message')
    {{ $donorName }}様

    この度は、{{ $donationProject }}にご寄付いただき、誠にありがとうございます。
    以下の形で毎月支払いされることをお知らせいたします。
    また毎支払いをキャンセルしたい場合はキャンセルURL似て確認くださいませ。
    キャンセルURL

    @component('mail::table')
        |         |                                        |
        | :---    | :---                                   |
        | 寄付金額 | ¥{{ number_format($donationAmount) }}円 |
    @endcomponent

    ご支援のおかげで、今後も{{$donationProject}}を欠かさず永続できます。

    寄付控除証明書は来年の1月にメールにて送信させていただきます。
    引き続きよろしくお願いいたします。
@endcomponent
