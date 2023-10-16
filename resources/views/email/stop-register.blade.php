<html lang="en">
    <body >
        <div style="padding: 0 120px; margin-top: 90px">
            <div style="border-top: 5px solid #289FE1; border-bottom: 5px solid #289FE1; padding-top: 20px; padding-bottom: 20px">
                <div style="padding-left: 60px">
                    <p><b>Bluee Direct</b></p>
                    <div style="padding: 0 50px;" >
                        <p>海外マッチングシステムから登録申請が却下されました。<br>
                            今後このシステムを利用することができません。
                        </p>
                        <p>【利用停止日時】<br> {{@$data['date']}} </p>

                        @if(@$result['user_type'] == \App\Models\Company::class)
                            <p>【利用停止企業名】<br>{{@$data['name']}} </p>
                        @else
                            <p>【利用停止団体名】<br>{{@$data['name']}} </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
