<?php

$input = file_get_contents('php://stdin');

$lines = explode("\n", trim($input));
$results = [];
$numExpressions = (int)$lines[0];

function addToResults(int $num1, int $num2, string $operator, int $result, &$results)
{
    $length = max(strlen($num1), strlen($num2 . $operator));
    $results[] = str_pad(
        $num1,
        $length,
        ' ',
        STR_PAD_LEFT
    );
    $results[] = str_pad(
        $operator . $num2,
        $length,
        ' ',
        STR_PAD_LEFT
    );
    $results[] = str_repeat('-', $length);
    $results[] = str_pad(
        $result,
        $length,
        ' ',
        STR_PAD_LEFT
    );
}

for ($i = 1; $i <= $numExpressions; $i++) {
    if (empty($lines[$i])) {
        continue;
    }

    $expression = $lines[$i];
    if (strpos($expression, '+') !== false) {
        $values = explode('+', $expression);
        $sum = bcadd($values[0], $values[1]);
        addToResults(
            $values[0],
            $values[1],
            '+',
            $sum,
            $results
        );
    } elseif (strpos($expression, '-') !== false) {
        $values = explode('-', $expression);
        $difference = bcsub($values[0], $values[1]);
        addToResults(
            $values[0],
            $values[1],
            '-',
            $difference,
            $results
        );
    } elseif (strpos($expression, '*') !== false) {
        $operator = '*';
        $values = explode($operator, $expression);
        $length = max(
            strlen($values[0]),
            strlen($values[1] . $operator)
        );
        $result = bcmul($values[0], $values[1]);
        $totalLength = strlen($result);

        $multipliers = str_split($values[1], 1);
        $subResults = [];
        $digitIndex = count($multipliers) - 1;

        for ($j = $digitIndex; $j >= 0; $j--) {
            $multiplication = bcmul($values[0], $multipliers[$j]);
            $subResults[] = str_pad(
                $multiplication,
                $totalLength - ($digitIndex - $j),
                ' ',
                STR_PAD_LEFT);
        }

        $results[] = str_pad(
            $values[0],
            $totalLength,
            ' ',
            STR_PAD_LEFT
        );
        $results[] = str_pad(
            $operator . $values[1],
            $totalLength,
            ' ',
            STR_PAD_LEFT
        );
        $results[] = str_pad(
            str_repeat('-', $length),
            $totalLength,
            ' ',
            STR_PAD_LEFT
        );

        $results = array_merge($results, $subResults);

        if (count($subResults) > 1) {
            $results[] = str_repeat('-', $totalLength);
            $results[] = str_pad(
                $result,
                $totalLength,
                ' ',
                STR_PAD_LEFT
            );
        }
    }
    $results[] = '';
}
foreach ($results as $result) {
    echo $result . PHP_EOL;
}