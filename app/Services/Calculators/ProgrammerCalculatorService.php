<?php

namespace App\Services\Calculators;

class ProgrammerCalculatorService
{
    /**
     * Returns [display, result, base, state]
     */
    public function process(string $display, ?string $op, ?string $value, ?string $action, string $base, array $data): array
    {
        $base = strtoupper($base);
        $result = null;
        $state = [
            'left' => $data['left'] ?? null,
            'pending' => $data['pending'] ?? null,
        ];

        $parse = function (string $s, string $base): int {
            return match ($base) {
                'HEX' => intval($s, 16),
                'DEC' => intval($s, 10),
                'OCT' => intval($s, 8),
                'BIN' => intval($s, 2),
                default => intval($s, 10),
            };
        };

        $format = function (int $n, string $base): string {
            return match ($base) {
                'HEX' => strtoupper(dechex($n)),
                'DEC' => (string)$n,
                'OCT' => decoct($n),
                'BIN' => decbin($n),
                default => (string)$n,
            };
        };

        // Base switching
        if (($data['action'] ?? null) === 'set-base' && isset($data['base'])) {
            $newBase = strtoupper($data['base']);
            $n = $parse($display, $base);
            return [$format($n, $newBase), null, $newBase, $state];
        }

        if (($data['action'] ?? null) === 'digit' && isset($data['digit'])) {
            $digit = strtoupper($data['digit']);
            if ($display === '0') $display = '';
            $allowed = match ($base) {
                'HEX' => '0123456789ABCDEF',
                'DEC' => '0123456789',
                'OCT' => '01234567',
                'BIN' => '01',
                default => '0123456789',
            };
            if (str_contains($allowed, $digit)) $display .= $digit;
            if ($display === '') $display = '0';
            return [$display, null, $base, $state];
        }

        if (($data['action'] ?? null) === 'clear-all') {
            return ['0', null, $base, ['left' => null, 'pending' => null]];
        }

        // Unary ops
        if (($data['unary'] ?? null)) {
            $n = $parse($display, $base);
            switch ($data['unary']) {
                case 'NOT': $n = ~$n; break;
            }
            return [$format($n, $base), $n, $base, $state];
        }

        // Binary ops and shifts
        if (($data['action'] ?? null) === 'binary-op' && isset($data['operator'])) {
            $state = ['left' => $parse($display, $base), 'pending' => $data['operator']];
            return ['0', null, $base, $state];
        }

        if (($data['action'] ?? null) === 'shift' && isset($data['direction'])) {
            $n = $parse($display, $base);
            $by = intval($data['by'] ?? 1);
            if ($data['direction'] === 'left') $n = $n << $by; else $n = $n >> $by;
            return [$format($n, $base), $n, $base, $state];
        }

        if (($data['action'] ?? null) === 'equals') {
            $pending = $state['pending'] ?? null;
            $left = isset($state['left']) ? (int)$state['left'] : null;
            $right = $parse($display, $base);
            if ($pending !== null && $left !== null) {
                $n = 0;
                switch (strtoupper($pending)) {
                    case 'AND': $n = $left & $right; break;
                    case 'OR': $n = $left | $right; break;
                    case 'XOR': $n = $left ^ $right; break;
                    case 'NAND': $n = ~($left & $right); break;
                    case 'NOR': $n = ~($left | $right); break;
                    default: $n = $right; break;
                }
                return [$format($n, $base), $n, $base, ['left' => null, 'pending' => null]];
            }
        }

        return [$display, $result, $base, $state];
    }
}
