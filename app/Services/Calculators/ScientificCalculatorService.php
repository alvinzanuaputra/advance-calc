<?php

namespace App\Services\Calculators;

class ScientificCalculatorService
{
    /**
     * Process a scientific calculator action.
     * Returns array [display, result, state]
     */
    public function process(string $display, ?string $op, ?string $value, ?string $action, array $data): array
    {
        $result = null;
        $display = $display === '' ? '0' : $display;
        $x = (float)$display;
        $state = [
            'left' => $data['left'] ?? null,
            'pending' => $data['pending'] ?? null,
        ];

        $unary = $data['unary'] ?? null;
        if ($unary) {
            switch ($unary) {
                case 'sin': $result = sin($x); break;
                case 'cos': $result = cos($x); break;
                case 'tan': $result = tan($x); break;
                case 'asin': $result = asin($x); break;
                case 'acos': $result = acos($x); break;
                case 'atan': $result = atan($x); break;
                case 'log': $result = $x <= 0 ? 'Invalid input' : log10($x); break;
                case 'ln': $result = $x <= 0 ? 'Invalid input' : log($x); break;
                case 'exp': $result = exp($x); break;
                case 'x2': $result = $x * $x; break;
                case 'sqrt': $result = $x < 0 ? 'Invalid input' : sqrt($x); break;
                case 'fact': $result = ($x < 0 || floor($x) != $x) ? 'Invalid input' : (string)$this->fact((int)$x); break;
                case 'inv': $result = $x == 0.0 ? 'Cannot divide by zero' : 1 / $x; break;
            }
            return [is_string($result) ? $result : (string)$result, $result, $state];
        }

        if (($data['action'] ?? null) === 'digit' && isset($data['digit'])) {
            $digit = $data['digit'];
            if ($display === '0' && $digit !== '.') $display = $digit; else {
                if ($digit === '.' && str_contains($display, '.')) { /* ignore */ }
                else $display .= $digit;
            }
            return [$display, null, $state];
        }

        if (($data['action'] ?? null) === 'binary-op' && isset($data['operator'])) {
            $operator = $data['operator'];
            $state = ['left' => (float)$display, 'pending' => $operator];
            return ['0', null, $state];
        }

        if (($data['action'] ?? null) === 'equals') {
            $pending = $state['pending'] ?? null;
            $left = isset($state['left']) ? (float)$state['left'] : null;
            $right = (float)$display;
            if ($pending !== null && $left !== null) {
                switch ($pending) {
                    case '+': $result = $left + $right; break;
                    case '-': $result = $left - $right; break;
                    case '*': $result = $left * $right; break;
                    case '/': $result = ($right == 0.0) ? 'Cannot divide by zero' : $left / $right; break;
                    case '^': $result = pow($left, $right); break;
                }
                return [is_string($result) ? $result : (string)$result, $result, ['left' => null, 'pending' => null]];
            }
        }

        if (($data['action'] ?? null) === 'clear-all') {
            return ['0', null, ['left' => null, 'pending' => null]];
        }

        return [$display, $result, $state];
    }

    private function fact(int $n): int
    {
        $res = 1;
        for ($i = 2; $i <= $n; $i++) $res *= $i;
        return $res;
    }
}
