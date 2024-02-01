<div>
    @if(is_string($getState()))

    <a href="tel:{{ $getState() }}">{{ $getState() }}</a>

    @endif
</div>
