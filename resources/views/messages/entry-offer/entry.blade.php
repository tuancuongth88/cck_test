<div class="distribute-msg-frame">
    <div class="d-flex justify-content-between">
        <div>
            <h6 class="font-weight-bold">
                海外人材マッチングシステム(仮)運営事務局
            </h6>
            <h6 class="font-weight-bold">
                求人にエントリーがありました
            </h6>
        </div>
        <span>{{ $data['date'] }}</span>
    </div>

    <div class="distribute_msg-line" ></div>
    <div class="distribute-msg-frame__inputs-datas">
        <h6 class="my-4">{{ $data['email'] }} &nbsp;&nbsp;様</h6>
        <div>
            求人にエントリーが実行されました。
            <a href="{{ url('matching-management') }}">こちら</a>よりエントリーの内容を確認してください。
        </div>
    </div>
    <!-- エントリー求人情報 / Entry job information -->
    <div class="distribute-msg-frame__inputs-datas px-5">
        <h5 class="mt-4 font-weight-medium">エントリーID</h5>
        <span class="ml-3">23</span>
    </div>
    <div class="distribute-msg-frame__inputs-datas px-5">
        <h5 class="mt-4 font-weight-medium">
            エントリー求人情報
        </h5>
        <table role="table" aria-busy="false" aria-colcount="2" class="table b-table table-bordered">
            <thead role="rowgroup" class="">
            <tr role="row" class="">
                <th role="columnheader" scope="col" aria-colindex="1" class="" style="width: 50%; text-align: center;">
                    <div>求人名</div>
                </th>
                <th role="columnheader" scope="col" aria-colindex="2" class="" style="width: 50%; text-align: center;">
                    <div>企業名</div>
                </th>
            </tr>
            </thead>
            <tbody role="rowgroup">
            <tr role="row" class="">
                <td aria-colindex="1" role="cell" class="">{{ @$data['job'] }}</td>
                <td aria-colindex="2" role="cell" class="">{{ @$data['company'] }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="distribute-msg-frame__inputs-datas px-5">
        <h5 class="mt-4 font-weight-medium">エントリー人材</h5>
        <table role="table" aria-busy="false" aria-colcount="1" class="table b-table table-bordered">
            <thead role="rowgroup" class="">
            <tr role="row" class="">
                <th role="columnheader" scope="col" aria-colindex="1" class="" style="width: 50%; text-align: center;">
                    <div>企業名</div>
                </th>
            </tr>
            </thead>
            <tbody role="rowgroup">
            <tr role="row" class="">
                <td aria-colindex="1" role="cell" class="">{{ @$data['full_name_ja'] }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
