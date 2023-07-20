<html lang="en">
<body >
<div style="padding: 0 120px; margin-top: 90px">
    <div style="border-top: 5px solid #289FE1; border-bottom: 5px solid #289FE1; padding-top: 20px; padding-bottom: 20px">
        <h3 style='font-weight: 600;'>
            企業新規アカウント申請のお知らせ
        </h3>
        <div style="padding-left: 60px">
            <p>
                {{ $data['email'] }}　様
            </p>
            <p>
                企業の新規アカウント申請がありました。<br>
                <a href="{{ url('company/list') }}">こちら</a>より企業の審査を行ってください。
            </p>
        </div>
    </div>
</div>
</body>

</html>
