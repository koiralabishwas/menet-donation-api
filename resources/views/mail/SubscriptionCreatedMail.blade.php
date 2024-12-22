@component('mail::message')
{{ $donorName }}様

この度は、{{ $donationProject }}への月額ご支援をお申し込みいただき、誠にありがとうございます。
以下の内容で毎月型の寄付が確定しましたので、ご確認ください。

@component('mail::table')
    | 項目         | 詳細                                   |
    | :---         | :---                                   |
    | 寄付事業      | {{$donationProject}}                   |
    | 寄付金額      | ¥{{ number_format($donationAmount) }}円 |

@endcomponent

ご支援のおかげで、{{ $donationProject }}を継続的に運営することができます。

寄付控除証明書は来年の1月にメールにて送信させていただきます。
その他のご質問や変更事項がございましたら、いつでもご連絡ください。

引き続き、どうぞよろしくお願いいたします。

@endcomponent
