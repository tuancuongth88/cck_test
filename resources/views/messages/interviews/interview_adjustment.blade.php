<div class="distribute-msg-frame">
    <div class="d-flex justify-content-between">
        <div>
            <h6 class="font-weight-bold">
                面接候補日が設定されました
            </h6>
        </div>
        <span>{{ $data['date'] }}</span>
    </div>

    <div class="distribute_msg-line" ></div>
    <div class="distribute-msg-frame__inputs-datas">
        <h6 class="my-4">{{ $data['company'] }} &nbsp;&nbsp;様</h6>
        <div>
            以下の面接候補日が設定されました。
            マッチング管理より詳細を確認してください。
        </div>
    </div>
    <!-- エントリー求人情報 / Entry job information -->
    @if(!empty($data['entry_code']))
    <div class="distribute-msg-frame__inputs-datas px-5">
        <h5 class="mt-4 font-weight-medium">エントリーID</h5>
        <span class="ml-3">{{ $data['entry_code'] }}</span>
    </div>
    @endif
    <div class="distribute-msg-frame__inputs-datas px-5">
        <h5 class="mt-4 font-weight-medium">
            求人情報
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
                @if(in_array($data['type'],[HR,HR_MANAGER]))
                    <td aria-colindex="1" role="cell" class=""><a href="{{ url('job-search/detail/'.@$data['job_id']) }}">{{ @$data['job'] }}</a></td>
                @else
                    <td aria-colindex="1" role="cell" class=""><a href="{{ url('job/detail/'.@$data['job_id']) }}">{{ @$data['job'] }}</a></td>
                @endif
                <td aria-colindex="2" role="cell" class="">{{ @$data['company'] }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="distribute-msg-frame__inputs-datas px-5">
        <h5 class="mt-4 font-weight-medium">人材情報</h5>
        <table role="table" aria-busy="false" aria-colcount="1" class="table b-table table-bordered">
            <thead role="rowgroup" class="">
            <tr role="row" class="">
                <th role="columnheader" scope="col" aria-colindex="1" class="" style="width: 50%; text-align: center;">
                    <div>企業名</div>
                </th>
                <th role="columnheader" scope="col" aria-colindex="2" class="" style="width: 50%; text-align: center;">
                    <div>企業名</div>
                </th>
            </tr>
            </thead>
            <tbody role="rowgroup">
                <tr role="row" class="">
                    <td aria-colindex="1" role="cell" class=""><a href="{{ url('hr/detail/'.@$data['hrs_id']) }}">{{ @$data['full_name'] }} {{ @$data['full_name_ja'] }}</a></td>
                    <td aria-colindex="1" role="cell" class="">承認</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
