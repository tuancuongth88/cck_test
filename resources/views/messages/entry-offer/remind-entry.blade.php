<div class="distribute-msg-frame">
    <div class="d-flex justify-content-between">
        <div>
            <h6 class="font-weight-bold">
               リマインドのお知らせ
            </h6>
        </div>
        <span>{{ $data['date'] }}</span>
    </div>
    <hr>
    <div class="distribute_msg-line" ></div>
    <div class="distribute-msg-frame__inputs-datas">
        <h6 class="my-4">{{ $data['company'] }} &nbsp;&nbsp;様</h6>
        <div>
            申請されたエントリーを審査するのタスクが停滞しています。<br>
            <a href="{{ url('matching-management') }}">こちら</a>より確認してください。
        </div>
    </div>
</div>
