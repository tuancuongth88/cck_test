<div class="distribute-msg-frame">
    <div class="d-flex justify-content-between">
        <div>
            <h6 class="font-weight-bold">
                海外人材マッチングシステム(仮)運営事務局
            </h6>
        </div>
        <span>{{ $data['date'] }}</span>
    </div>
    <div class="distribute_msg-line" ></div>
    <div class="distribute-msg-frame__inputs-datas">
        <h6 class="my-4">{{ $data['email'] }} &nbsp;&nbsp;様</h6>
        <div>企業の新規アカウント申請がありました。</div>
        <div>
            <a href="{{ url('company/list') }}">こちら</a>より企業の審査を行ってください。
        </div>
    </div>
</div>
