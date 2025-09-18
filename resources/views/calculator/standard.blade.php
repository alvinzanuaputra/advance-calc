@extends('layouts.app')

@section('content')
<div class="display">
    <div class="history">Standard</div>
    <div class="value">{{ $display }}</div>
    @if($result !== null)
    <div class="note">Result: {{ is_string($result) ? $result : $result }}</div>
    @endif
</div>

<form method="post" action="{{ route('calculator.standard') }}">
    @csrf
    <input type="hidden" name="display" value="{{ $display }}">
    <input type="hidden" name="left" value="{{ $left }}">
    <input type="hidden" name="pending" value="{{ $pending }}">
    <div class="grid">
        <button name="action" value="clear-entry" class="key op">CE</button>
        <button name="action" value="clear-all" class="key op">C</button>
        <button name="action" value="backspace" class="key op">⌫</button>
        <button name="action" value="percent" class="key op">%</button>

        <button name="action" value="reciprocal" class="key op">1/x</button>
        <button name="action" value="square" class="key op">x²</button>
        <button name="action" value="sqrt" class="key op">√x</button>
        <button name="action" value="binary-op" class="key op" formaction="{{ route('calculator.standard') }}?operator=/">÷</button>

        @foreach([7,8,9] as $d)
            <button name="action" value="digit" class="key" formaction="{{ route('calculator.standard') }}?digit={{ $d }}">{{ $d }}</button>
        @endforeach
        <button name="action" value="binary-op" class="key op" formaction="{{ route('calculator.standard') }}?operator=*">×</button>

        @foreach([4,5,6] as $d)
            <button name="action" value="digit" class="key" formaction="{{ route('calculator.standard') }}?digit={{ $d }}">{{ $d }}</button>
        @endforeach
        <button name="action" value="binary-op" class="key op" formaction="{{ route('calculator.standard') }}?operator=-">−</button>

        @foreach([1,2,3] as $d)
            <button name="action" value="digit" class="key" formaction="{{ route('calculator.standard') }}?digit={{ $d }}">{{ $d }}</button>
        @endforeach
        <button name="action" value="binary-op" class="key op" formaction="{{ route('calculator.standard') }}?operator=%2B">+</button>

        <button name="action" value="toggle-sign" class="key">±</button>
        <button name="action" value="digit" class="key" formaction="{{ route('calculator.standard') }}?digit=0">0</button>
        <button name="action" value="digit" class="key" formaction="{{ route('calculator.standard') }}?digit=.">.</button>
        <button name="action" value="equals" class="key eq">=</button>
    </div>
</form>
@endsection
