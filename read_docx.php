<?php
function extractDocxText($filename) {
    $zip = new ZipArchive();
    if ($zip->open($filename) === TRUE) {
        $content = $zip->getFromName('word/document.xml');
        $zip->close();
        if ($content !== false) {
            $content = str_replace('</w:p>', "\n", $content);
            $text = strip_tags($content);
            return trim($text);
        }
    }
    return false;
}

$files = glob('docx/*.docx');
foreach ($files as $file) {
    echo "=================================================\n";
    echo "FILE: " . basename($file) . "\n";
    echo "=================================================\n";
    echo extractDocxText($file) . "\n\n";
}
