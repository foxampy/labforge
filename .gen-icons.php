<?php
/**
 * .gen-icons.php — генерация иконок из ОРИГИНАЛЬНОГО логотипа (БЕЗ перекраски).
 *
 * Берём assets/img/logo.png как есть (кот + ключи, чёрная графика с белым контуром),
 * только масштабируем. Создаём:
 *   - favicon.ico            (PNG-фреймы 16/32/48, прозрачный фон)
 *   - assets/img/favicon-32.png, favicon-16.png
 *   - assets/img/apple-touch-icon.png  (180×180, логотип на тёмном фоне бренда)
 *   - assets/img/og.jpg      (1200×630, фон hero.png + эмблема + надпись LEVADA SERVICE)
 *
 * Запуск:  php .gen-icons.php
 */
declare(strict_types=1);

$IMG       = __DIR__ . '/public/assets/img';
$logoPath  = $IMG . '/logo.png';
$heroPath  = $IMG . '/hero.png';
$fontBlack = 'C:/Windows/Fonts/ariblk.ttf';   // Arial Black — жирный дисплейный
$fontBold  = 'C:/Windows/Fonts/arialbd.ttf';

$INK = [10, 10, 11]; // #0a0a0b — фирменный почти-чёрный

/* ---------- загрузка и обрезка логотипа по непрозрачным пикселям ---------- */
$src = imagecreatefrompng($logoPath);
$sw  = imagesx($src);
$sh  = imagesy($src);

$minX = $sw; $minY = $sh; $maxX = 0; $maxY = 0;
for ($y = 0; $y < $sh; $y++) {
    for ($x = 0; $x < $sw; $x++) {
        $alpha = (imagecolorat($src, $x, $y) >> 24) & 0x7F; // 0=непрозрач., 127=прозрач.
        if ($alpha < 110) {
            if ($x < $minX) $minX = $x;
            if ($x > $maxX) $maxX = $x;
            if ($y < $minY) $minY = $y;
            if ($y > $maxY) $maxY = $y;
        }
    }
}
if ($maxX < $minX) { $minX = 0; $minY = 0; $maxX = $sw - 1; $maxY = $sh - 1; }
$cw = $maxX - $minX + 1;
$ch = $maxY - $minY + 1;

$logo = imagecreatetruecolor($cw, $ch);
imagealphablending($logo, false);
imagesavealpha($logo, true);
imagefilledrectangle($logo, 0, 0, $cw, $ch, imagecolorallocatealpha($logo, 0, 0, 0, 127));
imagecopy($logo, $src, 0, 0, $minX, $minY, $cw, $ch);

/* ---------- helpers ---------- */
function fit_logo($dst, $logo, int $dstW, int $dstH, int $pad): void
{
    $lw = imagesx($logo); $lh = imagesy($logo);
    $scale = min(($dstW - 2 * $pad) / $lw, ($dstH - 2 * $pad) / $lh);
    $nw = (int) round($lw * $scale);
    $nh = (int) round($lh * $scale);
    imagecopyresampled($dst, $logo, (int) (($dstW - $nw) / 2), (int) (($dstH - $nh) / 2), 0, 0, $nw, $nh, $lw, $lh);
}

function png_blob($img): string
{
    ob_start();
    imagepng($img);
    return (string) ob_get_clean();
}

function text_centered($img, string $font, float $size, int $cx, int $y, $color, string $str, float $spacing = 0.0): void
{
    if ($spacing == 0.0) {
        $bbox = imagettfbbox($size, 0, $font, $str);
        $w = $bbox[2] - $bbox[0];
        imagettftext($img, $size, 0, (int) ($cx - $w / 2), $y, $color, $font, $str);
        return;
    }
    // ручной трекинг (межбуквенный интервал) для дисплейного начертания
    $total = 0; $widths = [];
    foreach (preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY) as $ch) {
        $b = imagettfbbox($size, 0, $font, $ch);
        $w = $b[2] - $b[0];
        $widths[] = [$ch, $w];
        $total += $w + $spacing;
    }
    $total -= $spacing;
    $x = (int) ($cx - $total / 2);
    foreach ($widths as [$ch, $w]) {
        imagettftext($img, $size, 0, $x, $y, $color, $font, $ch);
        $x += (int) ($w + $spacing);
    }
}

/* ---------- favicon (прозрачный фон, оригинальные цвета) ---------- */
function favicon_frame($logo, int $size)
{
    $c = imagecreatetruecolor($size, $size);
    imagealphablending($c, false);
    imagesavealpha($c, true);
    imagefilledrectangle($c, 0, 0, $size, $size, imagecolorallocatealpha($c, 0, 0, 0, 127));
    imagealphablending($c, true);
    fit_logo($c, $logo, $size, $size, max(1, (int) ($size * 0.03)));
    return $c;
}

$f16 = favicon_frame($logo, 16);
$f32 = favicon_frame($logo, 32);
$f48 = favicon_frame($logo, 48);
imagepng($f16, $IMG . '/favicon-16.png');
imagepng($f32, $IMG . '/favicon-32.png');

// ICO с PNG-фреймами (поддерживается всеми современными браузерами)
$frames = [16 => png_blob($f16), 32 => png_blob($f32), 48 => png_blob($f48)];
$count  = count($frames);
$dir    = pack('vvv', 0, 1, $count);
$entries = '';
$data    = '';
$offset  = 6 + 16 * $count;
foreach ($frames as $size => $blob) {
    $len = strlen($blob);
    $wh  = $size >= 256 ? 0 : $size;
    $entries .= pack('CCCC', $wh, $wh, 0, 0) . pack('vv', 1, 32) . pack('VV', $len, $offset);
    $data    .= $blob;
    $offset  += $len;
}
file_put_contents(__DIR__ . '/favicon.ico', $dir . $entries . $data);

/* ---------- apple-touch-icon (логотип на тёмном фоне бренда) ---------- */
$apple = imagecreatetruecolor(180, 180);
imagefilledrectangle($apple, 0, 0, 180, 180, imagecolorallocate($apple, ...$INK));
imagealphablending($apple, true);
fit_logo($apple, $logo, 180, 180, 22);
imagepng($apple, $IMG . '/apple-touch-icon.png');

/* ---------- og.jpg (фон hero.png + эмблема + надпись) ---------- */
$ogW = 1200; $ogH = 630;
$og = imagecreatetruecolor($ogW, $ogH);

// фон: hero.png по принципу cover
$hero = imagecreatefrompng($heroPath);
$hw = imagesx($hero); $hh = imagesy($hero);
$scale = max($ogW / $hw, $ogH / $hh);
$nw = (int) round($hw * $scale);
$nh = (int) round($hh * $scale);
imagecopyresampled($og, $hero, (int) (($ogW - $nw) / 2), (int) (($ogH - $nh) / 2), 0, 0, $nw, $nh, $hw, $hh);

// затемняющая вуаль для читаемости
imagealphablending($og, true);
imagefilledrectangle($og, 0, 0, $ogW, $ogH, imagecolorallocatealpha($og, 0, 0, 0, 44));

// эмблема по центру сверху
fit_logo_at($og, $logo, $ogW, 60, 300);
function fit_logo_at($dst, $logo, int $canvasW, int $top, int $boxH): void
{
    $lw = imagesx($logo); $lh = imagesy($logo);
    $scale = $boxH / $lh;
    $nw = (int) round($lw * $scale);
    $nh = (int) round($lh * $scale);
    imagecopyresampled($dst, $logo, (int) (($canvasW - $nw) / 2), $top, 0, 0, $nw, $nh, $lw, $lh);
}

$white = imagecolorallocate($og, 255, 255, 255);
$grey  = imagecolorallocate($og, 173, 176, 184);
text_centered($og, $fontBlack, 78, $ogW / 2, 470, $white, 'LEVADA SERVICE', 6);
text_centered($og, $fontBold, 27, $ogW / 2, 530, $grey, 'Автосервис в Минске · Китайские авто · EV · Все марки');
text_centered($og, $fontBold, 24, $ogW / 2, 582, $grey, 'Диагностика · ТО · Ходовая · Тормоза · Чип-тюнинг · Антикор');

imagejpeg($og, $IMG . '/og.jpg', 88);

echo "OK: favicon.ico, favicon-16/32.png, apple-touch-icon.png, og.jpg\n";
echo "logo bbox: {$cw}x{$ch} (from {$sw}x{$sh})\n";
