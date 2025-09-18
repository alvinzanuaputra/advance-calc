<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Calculator</title>
    <style>
        :root{
            --bg:#1f1f1f; --panel:#2a2a2a; --key:#333; --key-hover:#3a3a3a; --op:#3b3f4a; --op-hover:#454a56; --acc:#2563eb; --acc-hover:#1d4ed8; --txt:#f3f4f6; --muted:#9ca3af;
        }
        *{box-sizing:border-box;font-family:Segoe UI,Roboto,Helvetica,Arial,sans-serif}
        body{margin:0;background:var(--bg);color:var(--txt);display:flex;align-items:center;justify-content:center;height:100vh}
        .calc-wrap{width:380px;background:var(--panel);border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.45);overflow:hidden}
        .topbar{display:flex;gap:6px;padding:10px;background:#1b1b1b;align-items:center}
        .mode-switch a{color:var(--muted);text-decoration:none;padding:6px 10px;border-radius:8px}
        .mode-switch a.active{background:#111;color:#fff}
        .display{padding:20px;text-align:right;background:#111}
        .display .history{font-size:12px;color:var(--muted);height:18px}
        .display .value{font-size:40px;font-weight:600;letter-spacing:1px}
        form{padding:16px}
        .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:8px}
        button.key{background:var(--key);border:none;color:var(--txt);padding:14px;border-radius:10px;font-size:16px;cursor:pointer}
        button.key:hover{background:var(--key-hover)}
        .key.op{background:var(--op)}
        .key.op:hover{background:var(--op-hover)}
        .key.eq{background:var(--acc)}
        .key.eq:hover{background:var(--acc-hover)}
        .row-5{grid-column:span 2}
        .row-6{grid-column:span 3}
        .subgrid{display:grid;grid-template-columns:repeat(6,1fr);gap:8px}
        .base-tabs{display:flex;gap:8px;margin-bottom:8px}
        .base-tabs button{flex:1}
        .note{color:var(--muted);font-size:12px;margin-top:8px}
        input[type=hidden]{display:none}
    </style>
    @yield('head')
    @stack('styles')
</head>
<body>
    <div class="calc-wrap">
        <div class="topbar">
            <div class="mode-switch">
                <a href="{{ route('calculator.standard') }}" class="{{ ($mode ?? '')==='standard'?'active':'' }}">Standard</a>
                <a href="{{ route('calculator.scientific') }}" class="{{ ($mode ?? '')==='scientific'?'active':'' }}">Scientific</a>
                <a href="{{ route('calculator.programmer') }}" class="{{ ($mode ?? '')==='programmer'?'active':'' }}">Programmer</a>
            </div>
        </div>
        @yield('content')
    </div>
</body>
</html>
