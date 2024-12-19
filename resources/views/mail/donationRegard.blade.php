@component('mail::message')
{{ $donorName }}様

この度は、{{ $donationProject }}にご寄付いただき、誠にありがとうございます。
以下の内容で寄付が完了いたしましたので、ご確認ください。

@component('mail::table')
    | 項目         | 詳細                                   |
    | :---         | :---                                   |
    | 寄付金額      | ¥{{ number_format($donationAmount) }}円 |
@endcomponent

ご支援のおかげで、{{ $donationProject }}を継続的に運営し、多くの方々を支えることができます。

寄付控除証明書は、来年の1月にメールで送信させていただきます。
ご不明点やご質問がございましたら、いつでもお気軽にお問い合わせください。

改めまして、この度のご支援に心より感謝申し上げます。
今後ともどうぞよろしくお願いいたします。
@endcomponent
