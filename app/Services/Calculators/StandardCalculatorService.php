<?php

namespace App\Services\Calculators;

class StandardCalculatorService
{
    /**
     * Process a standard calculator action.
     * Returns array [display, result, state]
     */
    public function process(string $display, ?string $op, ?string $value, ?string $action, array $data): array
    {
        $display = $display === '' ? '0' : $display;
        $result = null;
        $state = [
            'left' => $data['left'] ?? null,
            'pending' => $data['pending'] ?? null,
        ];

        $num = fn(string $s) => (float)str_replace(',', '.', $s);

        if ($action === 'clear-entry') {
            return ['0', null, ['left' => null, 'pending' => null]];
        }
        if ($action === 'clear-all') {
            return ['0', null, ['left' => null, 'pending' => null]];
        }
        if ($action === 'backspace') {
            $d = rtrim($display);
            $d = strlen($d) > 1 ? substr($d, 0, -1) : '0';
            return [$d, null, $state];
        }
        if ($action === 'toggle-sign') {
            if ($display === '0') return [$display, null, $state];
            return [str_starts_with($display, '-') ? substr($display, 1) : '-' . $display, null, $state];
        }
        if ($action === 'reciprocal') {
            $x = $num($display);
            $result = $x == 0.0 ? 'Cannot divide by zero' : (string)(1 / $x);
            return [$result, $result, $state];
        }
        if ($action === 'square') {
            $x = $num($display);
            $result = (string)($x * $x);
            return [$result, $result, $state];
        }
        if ($action === 'sqrt') {
            $x = $num($display);
            $result = $x < 0 ? 'Invalid input' : (string)sqrt($x);
            return [$result, $result, $state];
        }
        if ($action === 'percent') {
            $x = $num($display);
            $result = (string)($x / 100);
            return [$result, $result, $state];
        }

        if ($action === 'digit' && isset($data['digit'])) {
            $digit = $data['digit'];
            if ($display === '0' && $digit !== '.') {
                $display = $digit;
            } else {
                if ($digit === '.' && str_contains($display, '.')) {
                    // ignore
                } else {
                    $display .= $digit;
                }
            }
            return [$display, null, $state];
        }

        if ($action === 'binary-op' && isset($data['operator'])) {
            $operator = $data['operator'];
            $left = $num($display);
            $state = ['left' => $left, 'pending' => $operator];
            // Reset display for next number
            return ['0', null, $state];
        }

        if ($action === 'equals') {
            $pending = $state['pending'] ?? null;
            $left = isset($state['left']) ? (float)$state['left'] : null;
            $right = $num($display);
            if ($pending !== null && $left !== null) {
                switch ($pending) {
                    case '+': $result = $left + $right; break;
                    case '-': $result = $left - $right; break;
                    case '×':
                    case '*': $result = $left * $right; break;
                    case '÷':
                    case '/': $result = ($right == 0.0) ? 'Cannot divide by zero' : $left / $right; break;
                    default: $result = $right; break;
                }
                // Clear pending after equals
                return [is_string($result) ? $result : (string)$result, $result, ['left' => null, 'pending' => null]];
            }
        }

        return [$display, $result, $state];
    }
}
