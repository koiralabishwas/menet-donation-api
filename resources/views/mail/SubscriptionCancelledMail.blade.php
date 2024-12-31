@component('mail::message')
{{ $donorName }}様

この度は、{{ $donationProject }}に対する継続的なご支援をいただき、誠にありがとうございます。
先程の、寄付キャンセルの旨を受付いたしました。
次の支払いを持ちまして、以下の寄付を終了とさせていただきます。

@component('mail::table')
    | 項目         | 詳細                                   |
    | :---         | :---                                   |
    | 支払金額      | ¥{{ number_format($donationAmount) }}円 |
@endcomponent

ご支援のおかげで、{{ $donationProject }}を安定的に運営し、多くの方々を支える活動を続けることができました。

寄付控除証明書は、来年の1月にメールで送付させていただきます。
ご不明点や変更希望がございましたら、いつでもお気軽にお問い合わせください。

改めまして、ご支援を心より感謝申し上げます。
@endcomponent
