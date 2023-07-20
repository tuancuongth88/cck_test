<html lang="en">
<body >
<header>
    <style>
        table, td, th {
            border: 1px solid;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</header>
<div style="padding: 0 120px; margin-top: 90px">
    <div style="border-top: 5px solid #289FE1; border-bottom: 5px solid #289FE1; padding-top: 20px; padding-bottom: 20px">
        <div>
            <div style='font-weight: 600; float: left'>
                リマインドのお知らせ
            </div>
            <div style='font-weight: 600; text-align: right'>
              {{ $data['date'] }}
            </div>
        </div>
        <br>
        <hr>
        <div style="padding-left: 60px">
            <p>
                {{ $data['company'] }}　様
            </p>
            <p>

                以下の面接候補日が設定されました。<br>
                <a href="{{ url('matching-management') }}">こちら</a>より詳細を確認してください。
            </p>
        </div>
        <div style="padding-left: 120px">
            <p>
                エントリーID
            </p>
            <p>
                {{ $data['entry_code'] }}
            </p>
            <p>
                <br>
                <label style="font-size: 20px; font-family: Inter;">エントリー求人情報 </label><br>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid">
                <tr>
                    <td style="border: 1px solid; font-size: 12px">求人名</td>
                    <td style="border: 1px solid;font-size: 12px">企業名</td>
                </tr>
                <tr>
                    <td style="border: 1px solid;font-size: 12px">{{ $data['job'] }}</td>
                    <td style="border: 1px solid;font-size: 12px">{{ $data['company'] }}</td>
                </tr>
            </table>
            <br>
            <p>
                <label style="font-size: 20px; font-family: Inter;">エントリー人材 </label><br>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid">
                <tr>
                    <td style="text-align: center;border: 1px solid; font-size: 12px">氏名</td>
                    <td style="text-align: center;border: 1px solid; font-size: 12px">承認可否</td>
                </tr>
                <tr>
                    <td style="border: 1px solid;font-size: 12px">{{ $data['full_name_ja'] }}</td>
                    <td style="border: 1px solid;font-size: 12px">承認</td>
                </tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>
