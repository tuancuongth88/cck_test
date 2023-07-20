<div class="distribute-msg-frame">
    <div class="d-flex justify-content-between">
        <div>
            <h6 class="font-weight-bold">
                人材団体新規アカウント申請のお知らせ
            </h6>
        </div>
        <span>{{ $data['date'] }}</span>
    </div>
    <div class="distribute_msg-line" ></div>
    <div class="distribute-msg-frame__inputs-datas">
        <h6 class="my-4">{{ $data['email'] }} &nbsp;&nbsp;様</h6>
        <div>人材団体の新規アカウント申請がありました。</div>
        <div>
            <a href="{{ url('hr-organization/list') }}">こちら</a>より人材団体の審査を行ってください。
        </div>
    </div>
</div>
