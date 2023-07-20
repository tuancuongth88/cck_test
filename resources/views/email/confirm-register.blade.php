<html lang="en">
<body >
<div style="padding: 0 120px; margin-top: 90px">
    <div style="border-top: 5px solid #289FE1; border-bottom: 5px solid #289FE1; padding-top: 20px; padding-bottom: 20px">
        <h3 style='font-weight: 600;'>
            海外人材マッチングシステム
        </h3>
        <div style="padding-left: 60px">
            <p>
                海外マッチングシステムから登録申請が承認されました。
            </p>
            <p>
                詳細は以下の通りです。
            </p>
            <p style=" margin-top: 26px;">
                【申請日時】
                <br> {{ \Carbon\Carbon::now()->format('Y年m月d日  H:i') }}
            </p>
            <p>
                【申請団体名】<br> {{ $data['name'] }}
            </p>
            <p>
                【ログイン情報】
                <br>ログインID：{{ $data['mail_address'] }}
                <br> パスワード：{{ $data['password'] }}
            </p>
            <p>
                <a href='{{ url('login') }}'>こちら</a>
                よりログインしてください。
            </p>

        </div>
    </div>
</div>
</body>

</html>
