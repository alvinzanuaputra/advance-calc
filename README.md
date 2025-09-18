# Laravel Calculator (Standard • Scientific • Programmer)

A Laravel 12 app with three calculator modes inspired by Windows 11 (desktop layout only) using pure CSS (no Tailwind).

-   Standard: +, −, ×, ÷, %, CE, C, backspace, 1/x, x², √, ±
-   Scientific: sin, cos, tan, log, ln, exp, x^y, factorial, etc.
-   Programmer: HEX/DEC/OCT/BIN, bitwise AND/OR/XOR/NOT, NAND, NOR, Lsh, Rsh

All calculations are processed via HTTP GET and POST to controllers. Buttons submit with POST while carrying operator/digit through query string for easy GET testing as well.

## Run locally (Windows PowerShell)

```powershell
cd "c:\ASUS TUF GAMING\Documentation\Semester 5\Pemrograman Berbasis Kerangka Kerja\calculator-laravel"
php artisan serve
```

Open http://127.0.0.1:8000 and switch modes from the top bar.

## Structure

-   `app/Http/Controllers/*CalculatorController.php` — per-mode controllers exposing a single `handle()` for GET/POST
-   `app/Services/Calculators/*CalculatorService.php` — pure-PHP logic per mode; controllers are thin
-   `resources/views/layouts/app.blade.php` — shared dark desktop UI and CSS grid
-   `resources/views/calculator/*.blade.php` — per-mode UIs and button grids
-   `routes/web.php` — routes for three modes with names

## Notes

-   UI is intentionally desktop-sized; not responsive.
-   Styling avoids any CSS frameworks; only vanilla CSS in the layout file.
-   Some scientific edge cases (domain errors) return a small message in the display.
-   Programmer mode uses PHP ints; negative results from NOT/NAND/NOR will be shown using two's complement behavior of PHP bitwise on ints.

## Usage and Test Cases

After running the server, open http://127.0.0.1:8000 and pick a mode from the top bar.

General notes

-   Use the “=” key to complete binary operations (e.g., +, −, ×, ÷, AND, OR, XOR). Chaining like 2 + 3 + 4 = is currently done as: 2 + 3 = + 4 =.
-   For GET testing in the browser address bar, the plus operator must be URL-encoded as `%2B`.

### Standard mode

-   Addition: 2 → + → 3 → = → expect 5
-   Subtraction: 9 → − → 4 → = → expect 5
-   Multiplication: 7 → × → 8 → = → expect 56
-   Division: 8 → ÷ → 2 → = → expect 4 (division by 0 shows “Cannot divide by zero”)
-   CE, C, Backspace:
    -   CE resets the current entry to 0
    -   C resets everything to 0 and clears pending state
    -   Backspace: 123 → ⌫ → shows 12
-   Percent (%): 50 → % → 0.5 (currently behaves as x/100)
-   Unary ops: 4 → 1/x → 0.25; 5 → x² → 25; 9 → √x → 3; 5 → ± → −5

GET examples (Standard):

-   2: `http://127.0.0.1:8000/calculator/standard?action=digit&digit=2`
-   plus: `http://127.0.0.1:8000/calculator/standard?action=binary-op&operator=%2B`
-   3: `http://127.0.0.1:8000/calculator/standard?action=digit&digit=3`
-   equals: `http://127.0.0.1:8000/calculator/standard?action=equals`

### Scientific mode

-   Clear (C): any number → C → display becomes 0 (no error)
-   Power: 2 → x^y → 10 → = → 1024
-   Unary ops: 4 → 1/x → 0.25; 5 → x² → 25; 9 → √x → 3
    -   0 → 1/x → “Cannot divide by zero”
    -   (−1) → √x → “Invalid input”
-   Logs/Exp: 100 → log → 2; 1 → ln → 0; 2 → exp → ~7.389056
-   Trig: 1.57079632679 → sin → ~1; 0 → cos → 1; 0 → tan → 0
-   Factorial: 5 → n! → 120 (negative or non-integer shows “Invalid input”)

GET examples (Scientific):

-   9: `http://127.0.0.1:8000/calculator/scientific?action=digit&digit=9`
-   sqrt: `http://127.0.0.1:8000/calculator/scientific?unary=sqrt`
-   plus: `http://127.0.0.1:8000/calculator/scientific?action=binary-op&operator=%2B`
-   equals: `http://127.0.0.1:8000/calculator/scientific?action=equals`

### Programmer mode

-   Base switching: DEC 10 → HEX (A) → BIN (1010)
-   Digits are constrained to the selected base (HEX: 0–9, A–F; DEC: 0–9; OCT: 0–7; BIN: 0–1)
-   Bitwise operations (DEC examples):
    -   6 AND 3 = 2 (110 & 011 = 010)
    -   6 OR 3 = 7 (110 | 011 = 111)
    -   6 XOR 3 = 5 (110 ^ 011 = 101)
    -   NAND, NOR may show negative results (two's complement representation)
-   Shifts: 2 Lsh = 4; 8 Rsh = 4

GET examples (Programmer):

-   Set base: `http://127.0.0.1:8000/calculator/programmer?action=set-base&base=HEX`
-   Digit A (HEX): `http://127.0.0.1:8000/calculator/programmer?action=digit&digit=A`
-   AND: `http://127.0.0.1:8000/calculator/programmer?action=binary-op&operator=AND`
-   Equals: `http://127.0.0.1:8000/calculator/programmer?action=equals`
-   Shift left: `http://127.0.0.1:8000/calculator/programmer?action=shift&direction=left&by=1`

### Troubleshooting

-   Plus operator not working in GET: ensure you use `%2B` instead of `+` in the URL.
-   Scientific Clear caused error: fixed by returning `[display, result, state]` consistently in the service.
-   Negative values in Programmer NOT/NAND/NOR: expected due to two’s complement behavior in PHP bitwise operations on ints.

---

# Panduan Penggunaan (Bahasa Indonesia)

Setelah server berjalan, buka http://127.0.0.1:8000 lalu pilih mode kalkulator di bar bagian atas.

Catatan Umum

-   Tekan tombol “=” untuk menyelesaikan operasi biner (mis. +, −, ×, ÷, AND, OR, XOR). Untuk chaining seperti 2 + 3 + 4 =, saat ini gunakan pola: 2 + 3 = + 4 =.
-   Untuk pengujian via URL (GET), operator “+” harus di-encode menjadi `%2B`.

### Mode Standard

-   Penjumlahan: 2 → + → 3 → = → hasil 5
-   Pengurangan: 9 → − → 4 → = → hasil 5
-   Perkalian: 7 → × → 8 → = → hasil 56
-   Pembagian: 8 → ÷ → 2 → = → hasil 4 (jika pembagian oleh 0, tampil “Cannot divide by zero”)
-   CE, C, Backspace:
    -   CE: menghapus input saat ini ke 0
    -   C: reset semua ke 0 dan menghapus state operasi
    -   Backspace: 123 → ⌫ → menjadi 12
-   Persen (%): 50 → % → 0.5 (saat ini perilaku x/100)
-   Operasi unary: 4 → 1/x → 0.25; 5 → x² → 25; 9 → √x → 3; 5 → ± → −5

Contoh GET (Standard):

-   2: `http://127.0.0.1:8000/calculator/standard?action=digit&digit=2`
-   plus: `http://127.0.0.1:8000/calculator/standard?action=binary-op&operator=%2B`
-   3: `http://127.0.0.1:8000/calculator/standard?action=digit&digit=3`
-   equals: `http://127.0.0.1:8000/calculator/standard?action=equals`

### Mode Scientific

-   Clear (C): angka apa pun → C → display jadi 0 (tanpa error)
-   Pangkat: 2 → x^y → 10 → = → hasil 1024
-   Unary: 4 → 1/x → 0.25; 5 → x² → 25; 9 → √x → 3
    -   0 → 1/x → “Cannot divide by zero”
    -   (−1) → √x → “Invalid input”
-   Log/Exp: 100 → log → 2; 1 → ln → 0; 2 → exp → ~7.389056
-   Trigonometri: 1.57079632679 → sin → ~1; 0 → cos → 1; 0 → tan → 0
-   Faktorial: 5 → n! → 120 (negatif atau non-integer → “Invalid input”)

Contoh GET (Scientific):

-   9: `http://127.0.0.1:8000/calculator/scientific?action=digit&digit=9`
-   sqrt: `http://127.0.0.1:8000/calculator/scientific?unary=sqrt`
-   plus: `http://127.0.0.1:8000/calculator/scientific?action=binary-op&operator=%2B`
-   equals: `http://127.0.0.1:8000/calculator/scientific?action=equals`

### Mode Programmer

-   Ganti basis: DEC 10 → HEX (A) → BIN (1010)
-   Digit sesuai basis: HEX (0–9, A–F), DEC (0–9), OCT (0–7), BIN (0–1)
-   Operasi bitwise (contoh DEC):
    -   6 AND 3 = 2 (110 & 011 = 010)
    -   6 OR 3 = 7 (110 | 011 = 111)
    -   6 XOR 3 = 5 (110 ^ 011 = 101)
    -   NAND, NOR bisa menghasilkan bilangan negatif (dua’s complement di PHP)
-   Shift: 2 Lsh = 4; 8 Rsh = 4

Contoh GET (Programmer):

-   Set base: `http://127.0.0.1:8000/calculator/programmer?action=set-base&base=HEX`
-   Digit A (HEX): `http://127.0.0.1:8000/calculator/programmer?action=digit&digit=A`
-   AND: `http://127.0.0.1:8000/calculator/programmer?action=binary-op&operator=AND`
-   Equals: `http://127.0.0.1:8000/calculator/programmer?action=equals`
-   Shift kiri: `http://127.0.0.1:8000/calculator/programmer?action=shift&direction=left&by=1`

### Troubleshooting (ID)

-   Operator plus di GET tidak jalan: gunakan `%2B` alih-alih `+`.
-   Error saat tombol C di Scientific: sudah diperbaiki (service selalu mengembalikan `[display, result, state]`).
-   Nilai negatif pada NOT/NAND/NOR di Programmer: itu perilaku wajar karena bitwise PHP memakai dua’s complement.

## License

MIT

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[WebReinvent](https://webreinvent.com/)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Jump24](https://jump24.co.uk)**
-   **[Redberry](https://redberry.international/laravel/)**
-   **[Active Logic](https://activelogic.com)**
-   **[byte5](https://byte5.de)**
-   **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
#   a d v a n c e - c a l c  
 