@extends('layouts.app')

@section('content')
<div class="display">
    <div class="history">Scientific</div>
    <div class="value">{{ $display }}</div>
    @if($result !== null)
    <div class="note">Result: {{ is_string($result) ? $result : $result }}</div>
    @endif
</div>

<form method="post" action="{{ route('calculator.scientific') }}">
    @csrf
    <input type="hidden" name="display" value="{{ $display }}">
    <input type="hidden" name="left" value="{{ $left }}">
    <input type="hidden" name="pending" value="{{ $pending }}">
    <div class="grid">
        <button name="action" value="clear-all" class="key op">C</button>
        <button name="unary" value="inv" class="key op">1/x</button>
        <button name="unary" value="x2" class="key op">x²</button>
        <button name="unary" value="sqrt" class="key op">√x</button>

        <button name="unary" value="sin" class="key op">sin</button>
        <button name="unary" value="cos" class="key op">cos</button>
        <button name="unary" value="tan" class="key op">tan</button>
        <button name="unary" value="fact" class="key op">n!</button>

        <button name="unary" value="log" class="key op">log</button>
        <button name="unary" value="ln" class="key op">ln</button>
        <button name="unary" value="exp" class="key op">exp</button>
        <button name="action" value="binary-op" class="key op" formaction="{{ route('calculator.scientific') }}?operator=^">x^y</button>

        @foreach([7,8,9] as $d)
            <button name="action" value="digit" class="key" formaction="{{ route('calculator.scientific') }}?digit={{ $d }}">{{ $d }}</button>
        @endforeach
        <button name="action" value="binary-op" class="key op" formaction="{{ route('calculator.scientific') }}?operator=/">÷</button>

        @foreach([4,5,6] as $d)
            <button name="action" value="digit" class="key" formaction="{{ route('calculator.scientific') }}?digit={{ $d }}">{{ $d }}</button>
        @endforeach
        <button name="action" value="binary-op" class="key op" formaction="{{ route('calculator.scientific') }}?operator=*">×</button>

        @foreach([1,2,3] as $d)
            <button name="action" value="digit" class="key" formaction="{{ route('calculator.scientific') }}?digit={{ $d }}">{{ $d }}</button>
        @endforeach
        <button name="action" value="binary-op" class="key op" formaction="{{ route('calculator.scientific') }}?operator=-">−</button>

        <button name="action" value="digit" class="key" formaction="{{ route('calculator.scientific') }}?digit=0">0</button>
        <button name="action" value="digit" class="key" formaction="{{ route('calculator.scientific') }}?digit=.">.</button>
    <button name="action" value="binary-op" class="key op" formaction="{{ route('calculator.scientific') }}?operator=%2B">+</button>
        <button name="action" value="equals" class="key eq">=</button>
    </div>
</form>
@endsection
