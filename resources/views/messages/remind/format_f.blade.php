<div class="distribute-msg-frame">
    <div class="distribute-msg-frame__title-input">
        <div class="title-input-title" style="justify-content: space-between;">
            <span>{{ $data['title'] }}</span>
            <span>{{ $data['date'] }}</span>
        </div>
    </div>
    <div class="distribute_msg-line"></div>
    @if($data['image'])
    <div>
        <img src="{{ asset($data['image']) }}" alt="Example Image">
    </div>
    @endif
    <div class="distribute-msg-content">
        {{ $data['text'] }}
    </div>
</div>
