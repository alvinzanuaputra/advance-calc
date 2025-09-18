<?php

namespace App\Http\Controllers;

use App\Services\Calculators\StandardCalculatorService;
use Illuminate\Http\Request;

class StandardCalculatorController extends Controller
{
    public function __construct(private StandardCalculatorService $service)
    {
    }

    public function handle(Request $request)
    {
        $result = null;
        $display = $request->input('display', '0');
        $op = $request->input('op');
        $value = $request->input('value');
        $action = $request->input('action');

        $state = [
            'left' => $request->input('left'),
            'pending' => $request->input('pending'),
        ];
        if ($request->isMethod('post') || $request->query()) {
            [$display, $result, $state] = $this->service->process($display, $op, $value, $action, array_merge($request->all(), $state));
        }

        return view('calculator.standard', [
            'mode' => 'standard',
            'display' => $display,
            'result' => $result,
            'left' => $state['left'] ?? null,
            'pending' => $state['pending'] ?? null,
        ]);
    }
}
