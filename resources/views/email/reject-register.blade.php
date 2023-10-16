<html lang="en">
<body >
<div style="padding: 0 120px; margin-top: 90px">
    <div style="border-top: 5px solid #289FE1; border-bottom: 5px solid #289FE1; padding-top: 20px; padding-bottom: 20px">
        <div style="padding-left: 60px">
            <p><b>Bluee Direct</b></p>
            <div style="padding: 0 50px;" >
                <p>海外マッチングシステムから登録申請が却下されました。<br>
                    詳細は以下の通りです。
                </p>
                <p>【却下日時】<br> {{@$data['date']}} </p>

                @if(@$data['user_type'] == \App\Models\Company::class)
                    <p>【申請企業名】<br>{{@$data['name']}} </p>
                @else
                    <p>【申請団体名】<br>{{@$data['name']}} </p>
                @endif
            </div>
        </div>
    </div>
</div>
</body>

</html>
