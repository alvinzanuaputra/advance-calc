@extends('layouts.app')

@section('content')
<div class="display">
    <div class="history">Programmer (Base: {{ $base }})</div>
    <div class="value">{{ $display }}</div>
    @if($result !== null)
    <div class="note">Result: {{ is_string($result) ? $result : $result }}</div>
    @endif
</div>

<form method="post" action="{{ route('calculator.programmer') }}">
    @csrf
    <input type="hidden" name="display" value="{{ $display }}">
    <input type="hidden" name="base" value="{{ $base }}">
    <input type="hidden" name="left" value="{{ $left }}">
    <input type="hidden" name="pending" value="{{ $pending }}">
    <div class="base-tabs">
        @foreach(['HEX','DEC','OCT','BIN'] as $b)
            <button name="action" value="set-base" class="key op" formaction="{{ route('calculator.programmer') }}?base={{ $b }}">{{ $b }}</button>
        @endforeach
    </div>

    <div class="grid">
        <button name="action" value="clear-all" class="key op">C</button>
        <button name="unary" value="NOT" class="key op">NOT</button>
        <button name="action" value="shift" class="key op" formaction="{{ route('calculator.programmer') }}?direction=left&by=1">Lsh</button>
        <button name="action" value="shift" class="key op" formaction="{{ route('calculator.programmer') }}?direction=right&by=1">Rsh</button>

        @php
            $digits = match($base){
                'HEX' => ['A','B','C','D','E','F'],
                'DEC' => [],
                'OCT' => [],
                'BIN' => [],
                default => [],
            };
        @endphp
        @foreach($digits as $d)
            <button name="action" value="digit" class="key" formaction="{{ route('calculator.programmer') }}?digit={{ $d }}">{{ $d }}</button>
        @endforeach

        @foreach([7,8,9,4,5,6,1,2,3,0] as $d)
            <button name="action" value="digit" class="key" formaction="{{ route('calculator.programmer') }}?digit={{ $d }}">{{ $d }}</button>
        @endforeach
        <button name="action" value="digit" class="key" formaction="{{ route('calculator.programmer') }}?digit=1">1</button>

        <button name="action" value="binary-op" class="key op" formaction="{{ route('calculator.programmer') }}?operator=AND">AND</button>
        <button name="action" value="binary-op" class="key op" formaction="{{ route('calculator.programmer') }}?operator=OR">OR</button>
        <button name="action" value="binary-op" class="key op" formaction="{{ route('calculator.programmer') }}?operator=XOR">XOR</button>
        <button name="action" value="binary-op" class="key op" formaction="{{ route('calculator.programmer') }}?operator=NAND">NAND</button>
        <button name="action" value="binary-op" class="key op" formaction="{{ route('calculator.programmer') }}?operator=NOR">NOR</button>
        <button name="action" value="equals" class="key eq">=</button>
    </div>
</form>
@endsection
