<?php

$files = [
    "storage/app/ports/2024-2 UNLOCODE CodeListPart1.csv",
    "storage/app/ports/2024-2 UNLOCODE CodeListPart2.csv",
    "storage/app/ports/2024-2 UNLOCODE CodeListPart3.csv",
];

$output = "storage/app/ports/code-list.csv";

$out = fopen($output, "w");

$first = true;

foreach ($files as $file) {

    $in = fopen($file, "r");

    $line = 0;

    while (($row = fgetcsv($in)) !== false) {

        if (!$first && $line == 0) {
            $line++;
            continue;
        }

        fputcsv($out, $row);

        $line++;
    }

    fclose($in);

    $first = false;
}

fclose($out);

echo "Selesai membuat code-list.csv";