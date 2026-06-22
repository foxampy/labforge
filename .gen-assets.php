<?php
declare(strict_types=1);
/* One-shot asset generator. Run via CLI, then delete.
 * Source of truth: assets/img/logo.png  — LEVADA SERVICE emblem
 * (angry cat + crossed wrenches), BLACK artwork on a transparent canvas.
 * Produces: assets/img/cat.png (white, header/footer), favicon.ico,
 * assets/img/apple-touch-icon.png, assets/img/og.jpg.
 */

$IMG       = __DIR__ . '/assets/img';
$LOGO      = "$IMG/logo.png";
$FONT_DISP = 'C:/Windows/Fonts/bahnschrift.ttf';
$FONT_BOLD = 'C:/Windows/Fonts/arialbd.ttf';
$FONT_REG  = 'C:/Windows/Fonts/arial.ttf';

/* ---------- helpers ---------- */
function load_png(string $p)
{
    $im = imagecreatefrompng($p);
    imagealphablending($im, false);
    imagesavealpha($im, true);
    return $im;
}

function png_blob($im): string
{
    ob_start();
    imagepng($im);
    return (string) ob_get_clean();
}

/** Bounding box of opaque-ish pixels (alpha 0=opaque..127=transparent). */
function bbox($im, int $thr = 110): array
{
    $w = imagesx($im);
    $h = imagesy($im);
    $x0 = $w; $y0 = $h; $x1 = -1; $y1 = -1;
    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            $a = (imagecolorat($im, $x, $y) >> 24) & 0x7F;
            if ($a < $thr) {
                if ($x < $x0) $x0 = $x;
                if ($x > $x1) $x1 = $x;
                if ($y < $y0) $y0 = $y;
                if ($y > $y1) $y1 = $y;
            }
        }
    }
    return $x1 < 0 ? [0, 0, $w - 1, $h - 1] : [$x0, $y0, $x1, $y1];
}

/** A WHITE (negated) copy of the black source, alpha preserved. */
function white_copy($src)
{
    $w = imagesx($src);
    $h = imagesy($src);
    $out = imagecreatetruecolor($w, $h);
    imagealphablending($out, false);
    imagesavealpha($out, true);
    imagecopy($out, $src, 0, 0, 0, 0, $w, $h);
    imagefilter($out, IMG_FILTER_NEGATE); // black art -> white, alpha untouched
    return $out;
}

/** Square canvas: transparent (bg=null) or solid bg, with the trimmed white mark centered. */
function compose($white, array $bb, int $size, float $padFrac, ?array $bg)
{
    $canvas = imagecreatetruecolor($size, $size);
    imagealphablending($canvas, false);
    imagesavealpha($canvas, true);
    if ($bg === null) {
        imagefilledrectangle($canvas, 0, 0, $size, $size, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
    } else {
        imagefilledrectangle($canvas, 0, 0, $size, $size, imagecolorallocate($canvas, $bg[0], $bg[1], $bg[2]));
    }
    imagealphablending($canvas, true);

    [$x0, $y0, $x1, $y1] = $bb;
    $cw = $x1 - $x0 + 1;
    $ch = $y1 - $y0 + 1;
    $avail = (int) round($size * (1 - 2 * $padFrac));
    $scale = min($avail / $cw, $avail / $ch);
    $dw = (int) round($cw * $scale);
    $dh = (int) round($ch * $scale);
    $dx = (int) round(($size - $dw) / 2);
    $dy = (int) round(($size - $dh) / 2);
    imagecopyresampled($canvas, $white, $dx, $dy, $x0, $y0, $dw, $dh, $cw, $ch);
    return $canvas;
}

/* ---------- prepare source ---------- */
$src   = load_png($LOGO);
$bb    = bbox($src);
$white = white_copy($src);
printf("logo %dx%d  bbox=[%d,%d %d,%d]\n", imagesx($src), imagesy($src), $bb[0], $bb[1], $bb[2], $bb[3]);

/* ---------- 1) cat.png — white emblem on transparent (header/footer) ---------- */
$cat = compose($white, $bb, 512, 0.04, null);
imagepng($cat, "$IMG/cat.png");
imagedestroy($cat);
echo "cat.png written (" . filesize("$IMG/cat.png") . " bytes)\n";

/* ---------- 2) favicon.ico — white emblem on near-black rounded square ---------- */
$BG = [11, 11, 13]; // #0b0b0d
$base = compose($white, $bb, 256, 0.14, $BG);
$frames = [];
foreach ([16, 32, 48, 64] as $size) {
    $dst = imagecreatetruecolor($size, $size);
    imagecopyresampled($dst, $base, 0, 0, 0, 0, $size, $size, 256, 256);
    $frames[] = ['w' => $size, 'h' => $size, 'data' => png_blob($dst)];
    imagedestroy($dst);
}
imagedestroy($base);

$count  = count($frames);
$ico    = pack('vvv', 0, 1, $count);
$offset = 6 + 16 * $count;
$blobs  = '';
foreach ($frames as $f) {
    $len = strlen($f['data']);
    $ico    .= pack('CCCCvvVV', $f['w'], $f['h'], 0, 0, 1, 32, $len, $offset);
    $offset += $len;
    $blobs  .= $f['data'];
}
file_put_contents(__DIR__ . '/favicon.ico', $ico . $blobs);
echo "favicon.ico written (" . filesize(__DIR__ . '/favicon.ico') . " bytes)\n";

/* ---------- 3) apple-touch-icon.png 180x180 — white emblem on near-black ---------- */
$ati = compose($white, $bb, 180, 0.16, $BG);
imagepng($ati, "$IMG/apple-touch-icon.png");
imagedestroy($ati);
echo "apple-touch-icon.png written (" . filesize("$IMG/apple-touch-icon.png") . " bytes)\n";

/* ---------- 4) og.jpg (1200x630) — hero bg + text + white emblem ---------- */
$W = 1200; $H = 630;
$og = imagecreatetruecolor($W, $H);

$hero = imagecreatefrompng("$IMG/hero.png");
$sw = imagesx($hero); $sh = imagesy($hero);
$ratio = max($W / $sw, $H / $sh);
$nw = (int) ceil($sw * $ratio); $nh = (int) ceil($sh * $ratio);
$dx = (int) (($W - $nw) / 2); $dy = (int) (($H - $nh) / 2);
imagecopyresampled($og, $hero, $dx, $dy, 0, 0, $nw, $nh, $sw, $sh);
imagedestroy($hero);

imagealphablending($og, true);
imagefilledrectangle($og, 0, 0, $W, $H, imagecolorallocatealpha($og, 5, 5, 7, 100));
for ($y = 0; $y < $H; $y++) {
    $t = $y / $H;
    $a = (int) round(110 * max(0, ($t - 0.30) / 0.70));
    if ($a <= 0) continue;
    imageline($og, 0, $y, $W, $y, imagecolorallocatealpha($og, 3, 3, 5, 127 - $a));
}
for ($x = 0; $x < 760; $x++) {
    $a = (int) round(78 * (1 - $x / 760));
    if ($a <= 0) continue;
    imageline($og, $x, 0, $x, $H, imagecolorallocatealpha($og, 3, 3, 5, 127 - $a));
}

/* white emblem, top-right */
$mark = compose($white, $bb, 168, 0.0, null);
imagecopy($og, $mark, $W - 168 - 60, 54, 0, 0, 168, 168);
imagedestroy($mark);

$wht = imagecolorallocate($og, 255, 255, 255);
$gray = imagecolorallocate($og, 205, 205, 210);
$violet = imagecolorallocate($og, 139, 92, 255);

imagettftext($og, 92, 0, 64, 250, $wht, $FONT_DISP, 'LEVADA SERVICE');
imagefilledrectangle($og, 66, 276, 66 + 240, 276 + 8, $violet);
imagettftext($og, 40, 0, 64, 372, $wht, $FONT_BOLD, 'Автосервис в Минске');
imagettftext($og, 28, 0, 64, 432, $gray, $FONT_REG, 'Китайские авто · Электромобили · Все марки');
imagettftext($og, 24, 0, 64, 560, $gray, $FONT_REG, 'Диагностика · ТО · Ремонт ходовой · Чип-тюнинг · Гарантия');

imagejpeg($og, "$IMG/og.jpg", 86);
echo "og.jpg written (" . filesize("$IMG/og.jpg") . " bytes)\n";
imagedestroy($og);

imagedestroy($src);
imagedestroy($white);
echo "done\n";
