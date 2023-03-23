<?php

/**
 * Sort two packets
 *
 * @param array $left packet
 * @param array $right packet
 *
 * @return mixed|string sorted packets or undefined if packets could not be sorted
 */
function sorted2(array $left, array $right) {
    $sorted = 'undefined';
    if (count($left) === 0 && count($right) !== 0) {
        return 'left';
    }
    for ($i = 0; $i < count($left); $i++) {
        if (!isset($right[$i])) {
            return 'right';
        }
        if (is_int($left[$i]) && is_int($right[$i])) {
            if ($left[$i] > $right[$i]) {
                return "right";
            } else if ($left[$i] < $right[$i]) {
                return "left";
            }
            if ($i === count($left) - 1 && count($right) > count($left)) {
                return 'left';
            }
        }
        if (is_int($left[$i]) && is_array($right[$i])) {
            $sorted = sorted2([$left[$i]], $right[$i]);
        }
        if (is_array($left[$i]) && is_int($right[$i])) {
            $sorted = sorted2($left[$i], [$right[$i]]);
        }
        if (is_array($left[$i]) && is_array($right[$i])) {
            $sorted = sorted2($left[$i], $right[$i]);
        }
        if ($sorted !== 'undefined') {
            return $sorted;
        }
    }
    return $sorted;
}

$fileContent = file_get_contents('input.txt');
$fileRows = explode("\n", $fileContent);

$pairs = [];
$stringPairs = [];
$j = 0;
for ($i = 0; $i < count($fileRows); $i++) {
    $pairs[$j++] = [json_decode(trim($fileRows[$i])), json_decode(trim($fileRows[++$i]))];
    $i++;
}

$correctPairs = 0;

// Iterate over pairs
for ($i = 0; $i < count($pairs); $i++) {
    echo "== Pair " . ($i + 1) . ' ==<br>';
    $left = $pairs[$i][0];
    $right = $pairs[$i][1];
    $sorted = sorted2($left, $right);
    if ($sorted === 'left' || $sorted === 'continue') {
        echo 'Inputs are in <b>in the right order</b><br>';
        $correctPairs += $i + 1;
    } else if ($sorted === 'right') {
        echo 'Inputs are <b>not</b> in the right order<br>';
    }
}

echo 'Sum of pairs : ' . $correctPairs;