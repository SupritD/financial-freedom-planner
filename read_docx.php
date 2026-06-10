<?php

$file = $argv[1];
$out = $argv[2];

if (!file_exists($file)) {
    die("File not found: $file");
}

$zip = new ZipArchive;
if ($zip->open($file) === TRUE) {
    if (($index = $zip->locateName('word/document.xml')) !== false) {
        $data = $zip->getFromIndex($index);
        $zip->close();
        
        // Remove XML tags and print text
        $text = strip_tags($data);
        $text = preg_replace('/\s+/', ' ', $text);
        file_put_contents($out, wordwrap($text, 100) . "\n\n");
    } else {
        echo "Could not find word/document.xml\n";
        $zip->close();
    }
} else {
    echo "Failed to open zip archive\n";
}
