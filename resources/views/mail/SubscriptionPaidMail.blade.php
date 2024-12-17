@component('mail::message')
{{ $donorName }}様

この度は、{{ $donationProject }}にご寄付いただき、誠にありがとうございます。
{{--TODO:メールテンプレートどうにか--}}
Subscription型です。
今月の寄付の支払いを受け付けました。

@component('mail::table')
|         |                                        |
| :---    | :---                                   |
| 寄付金額 | ¥{{ number_format($donationAmount) }}円 |
@endcomponent

ご支援のおかげで、今後も{{$donationProject}}を欠かさず永続できます。

寄付控除証明書は来年の1月にメールにて送信させていただきます。
引き続きよろしくお願いいたします。
@endcomponent
