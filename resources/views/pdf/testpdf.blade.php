@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>寄附金受領証明書</title>
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
            line-height: 1.5;
            margin: 20px;
        }

        h1, h2 {
            text-align: center;
        }

        p {
            font-size: 10pt;
            margin: 5px 0;
        }

        .section {
            padding: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .table-wrapper {
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10pt;
        }

        table, th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #eef2ff;
        }

        .section-box {
            padding: 10px;
            background-color: #fafafa;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .danger {
            background-color: #fff1f2;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="section text-right">
    <p>{{ Carbon::now()->format('Y年m月d日') }}</p>
    <h2>{{ Carbon::now()->format('Y') }}年分寄附金受領証明書</h2>
</div>

<div class="section">
    <p>寄附者ID: {{ $donor['donor_external_id'] }}</p>
    <p>寄附者住所: {{ $donor['address'] }}</p>
    <p>寄附者氏名または法人名: {{ $donor['name'] }} 様</p>
    <p>寄附者法人番号: {{ $donor['corporate_no'] }}</p>
    <p>年間寄附総額: <span class="bold">{{ number_format($total_amount) }}円</span></p>
</div>

<div class="section text-right">
    <p>認定通知書の番号 市市活第502号</p>
    <p>認定年月日 令和6年4月10日</p>
    <p>神奈川県横浜市栄区小菅ヶ谷一丁目2-1</p>
    <p>地球市民かながわプラザ NPOなどのための事務室内</p>
    <p>認定NPO法人多文化共生教育ネットワークかながわ</p>
    <p>理事長 武 一美</p>
</div>

<div class="section-box">
    <p class="text-center">平素は当法人の活動にご理解、ご協力を賜り、厚く御礼申し上げます。</p>
    <p class="text-center">頂戴した貴重なご寄附は、当団体の諸事業の運営に有効に使わせて頂きます。</p>
    <p class="text-center">今後とも、変わらぬご支援、ご協力をどうぞよろしくお願い申し上げます。</p>
</div>

<div class="section">
    <h2>◯ 寄附の内訳</h2>
</div>

<div class="table-wrapper">
    <table>
        <thead>
        <tr>
            <th>寄附年月日</th>
            <th>寄附ID</th>
            <th>寄附事業</th>
            <th>寄附種類</th>
            <th>寄附金額</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($donations as $donation)
            <tr>
                <td>{{ Carbon::parse($donation['created_at'])->format('Y年m月d日') }}</td>
                <td>{{ $donation['donation_external_id'] }}</td>
                <td>{{ $donation['donation_project'] }}</td>
                <td>{{ $donation['type'] }}</td>
                <td>{{ number_format($donation['amount']) }}円</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="section-box danger">
    <p class="text-center">
        寄附金の支出による税制上の優遇措置の適用を受けるためには、確定申告等が必要です。
        申告の際、この「寄附金受領証明書」が必要となりますので、大切に保存してください。
    </p>
</div>

</body>
</html>
