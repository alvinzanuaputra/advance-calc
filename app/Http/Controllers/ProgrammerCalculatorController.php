<?php

namespace App\Http\Controllers;

use App\Services\Calculators\ProgrammerCalculatorService;
use Illuminate\Http\Request;

class ProgrammerCalculatorController extends Controller
{
    public function __construct(private ProgrammerCalculatorService $service)
    {
    }

    public function handle(Request $request)
    {
        $result = null;
        $display = $request->input('display', '0');
        $op = $request->input('op');
        $value = $request->input('value');
        $action = $request->input('action');
        $base = $request->input('base', 'DEC');
        $state = [
            'left' => $request->input('left'),
            'pending' => $request->input('pending'),
        ];

        if ($request->isMethod('post') || $request->query()) {
            [$display, $result, $base, $state] = $this->service->process($display, $op, $value, $action, $base, array_merge($request->all(), $state));
        }

        return view('calculator.programmer', [
            'mode' => 'programmer',
            'display' => $display,
            'result' => $result,
            'base' => $base,
            'left' => $state['left'] ?? null,
            'pending' => $state['pending'] ?? null,
        ]);
    }
}
