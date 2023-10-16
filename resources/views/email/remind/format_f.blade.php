<html lang="en">
<body>
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
                {!! $data['date'] !!}
            </div>
        </div>
        <br>
        <hr>
        <div style="padding-left: 60px">
            <p>
                {{ @$data['permission'] }}様
            </p>
            <p>
                {{ $data['content'] }}
            </p>
        </div>
    </div>
</div>
</body>
</html>
