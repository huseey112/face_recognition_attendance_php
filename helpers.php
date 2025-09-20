<?php
// Requires GD extension
function image_ahash_from_file($path) {
    if (!file_exists($path)) return false;
    $img = @imagecreatefromstring(file_get_contents($path));
    if (!$img) return false;
    // convert to grayscale and resize to 8x8
    $w = imagesx($img); $h = imagesy($img);
    $tmp = imagecreatetruecolor(8,8);
    imagecopyresampled($tmp, $img, 0,0,0,0,8,8,$w,$h);
    // compute average
    $sum = 0; $pixels = [];
    for ($y=0;$y<8;$y++){
        for ($x=0;$x<8;$x++){
            $rgb = imagecolorat($tmp,$x,$y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            $gray = (int)(($r+$g+$b)/3);
            $pixels[] = $gray;
            $sum += $gray;
        }
    }
    $avg = $sum / 64.0;
    $hash = '';
    foreach ($pixels as $p) {
        $hash .= ($p >= $avg) ? '1' : '0';
    }
    // convert binary string to hex for compact storage
    $hex = '';
    for ($i = 0; $i < 64; $i += 4) {
        $n = bindec(substr($hash, $i, 4));
        $hex .= dechex($n);
    }
    imagedestroy($img);
    imagedestroy($tmp);
    return $hex;
}

function hamming_distance_hex($h1, $h2) {
    // convert hex to binary strings
    $b1 = hex2bin_str($h1);
    $b2 = hex2bin_str($h2);
    if ($b1 === false || $b2 === false) return PHP_INT_MAX;
    $len = min(strlen($b1), strlen($b2));
    $dist = 0;
    for ($i=0;$i<$len;$i++) if ($b1[$i] !== $b2[$i]) $dist++;
    // if lengths differ, add difference
    $dist += abs(strlen($b1)-strlen($b2));
    return $dist;
}

function hex2bin_str($hex) {
    $bin = '';
    for ($i=0;$i<strlen($hex);$i++) {
        $h = hexdec($hex[$i]);
        $bin .= str_pad(decbin($h),4,'0',STR_PAD_LEFT);
    }
    return $bin;
}
?>