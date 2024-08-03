<?php

$input = "4
12345+67890
324-111
325*4405
1234*4";
//$input = file_get_contents('php://stdin');

$lines = explode("\n", trim($input));
$results = [];
$numExpressions = (int)$lines[0];

function addToResults(int $num1, int $num2, string $operator, int $result, &$results) {
    $length = max(strlen($num1), strlen($num2) + 1);
    $results[] = str_pad($num1, $length, ' ', STR_PAD_LEFT);
    $results[] = str_pad($operator . $num2, $length, ' ', STR_PAD_LEFT);
    $results[] = str_repeat('-', $length);
    $results[] = str_pad($result, $length, ' ', STR_PAD_LEFT);
}

for ($i = 1; $i <= $numExpressions; $i++) {
    if (empty($lines[$i])) {
        continue;
    }

    if (strpos($lines[$i], '+')) {
        $values = explode('+', $lines[$i]);
        $sum = $values[0] + $values[1];

        addToResults($values[0], $values[1], '+', $sum, $results);
    } elseif (strpos($lines[$i], '-')) {
        $values = explode('-', $lines[$i]);
        $difference = $values[0] - $values[1];

        addToResults($values[0], $values[1], '-', $difference, $results);
    } elseif (strpos($lines[$i], '*')) {
        $values = explode('*', $lines[$i]);
        $length = max(strlen($values[0]), strlen($values[1]) +1);
        $result = $values[0] * $values[1];
        $totalLength = strlen((string)$result);

        $multiplier = str_split($values[1], 1);
        $partialResults = [];

        for ($j = count($multiplier) - 1; $j >= 0; $j--) {
            $multiplication = $values[0] * $multiplier[$j];
            $shiftedMultiplication = str_pad((string)$multiplication, $totalLength - (count($multiplier) - 1 - $j), ' ', STR_PAD_LEFT);
            $partialResults[] = $shiftedMultiplication;
        }

        $results[] = str_pad($values[0], $totalLength, ' ', STR_PAD_LEFT);
        $results[] = str_pad('*' . $values[1], $totalLength, ' ', STR_PAD_LEFT);
        $results[] = str_pad(str_repeat('-', $length), $totalLength, ' ', STR_PAD_LEFT);
        $results = array_merge($results, $partialResults);

        if (count($partialResults) > 1) {
            $results[] = str_repeat('-', $totalLength);
            $results[] = str_pad($result, $totalLength, ' ', STR_PAD_LEFT);
        }
    }
    $results[] = '';
}
foreach ($results as $result) {
    echo $result . PHP_EOL;
}