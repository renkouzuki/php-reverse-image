<?php
function loadDatabase() {
    $file = 'image_database.json';
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true);
    }
    return [];
}

function saveDatabase($data) {
    $file = 'image_database.json';
    file_put_contents($file, json_encode($data));
}

function calculateHash($imagePath) {
    $image = imagecreatefromstring(file_get_contents($imagePath));
    $resized = imagecreatetruecolor(8, 8);
    imagecopyresampled($resized, $image, 0, 0, 0, 0, 8, 8, imagesx($image), imagesy($image));
    
    $hash = '';
    for ($y = 0; $y < 8; $y++) {
        for ($x = 0; $x < 8; $x++) {
            $rgb = imagecolorat($resized, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            $gray = ($r + $g + $b) / 3;
            $hash .= ($gray < 128) ? '0' : '1';
        }
    }
    
    imagedestroy($image);
    imagedestroy($resized);
    
    return $hash;
}

function calculateSimilarity($hash1, $hash2) {
    $similarity = 0;
    for ($i = 0; $i < strlen($hash1); $i++) {
        if ($hash1[$i] === $hash2[$i]) $similarity++;
    }
    return $similarity / strlen($hash1);
}