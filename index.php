<?php

$input = file_get_contents('php://stdin');

$lines = explode("\n", trim($input));
$results = [];
$numExpressions = (int)$lines[0];

if ($numExpressions !== count($lines) - 1) {
    exit("Invalid input.");
}

function outputFormatting(
    int $num1,
    int $num2,
    string $operator,
    int $result,
    &$results
): void
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
        extract(['operator' => '+', 'firstValue' => $values[0], 'secondValue' => $values[1]]);
    } elseif (strpos($expression, '-') !== false) {
        $values = explode('-', $expression);
        extract(['operator' => '-', 'firstValue' => $values[0], 'secondValue' => $values[1]]);

    } elseif (strpos($expression, '*') !== false) {
        $values = explode('*', $expression);
        extract(['operator' => '*', 'firstValue' => $values[0], 'secondValue' => $values[1]]);

    }

    switch ($operator) {
        case '+':
            $sum = bcadd($firstValue, $secondValue);
            outputFormatting(
                $firstValue,
                $secondValue,
                $operator,
                $sum,
                $results
            );
            break;
        case '-':
            $difference = bcsub($firstValue, $secondValue);
            outputFormatting(
                $firstValue,
                $secondValue,
                $operator,
                $difference,
                $results
            );
            break;
        case'*':
            $length = max(
                strlen($firstValue),
                strlen($secondValue . $operator)
            );
            $result = bcmul($firstValue, $secondValue);
            $totalLength = strlen($result);

            $multipliers = str_split($secondValue, 1);
            $subResults = [];
            $digitIndex = count($multipliers) - 1;

            for ($j = $digitIndex; $j >= 0; $j--) {
                $multiplication = bcmul($firstValue, $multipliers[$j]);
                $subResults[] = str_pad(
                    $multiplication,
                    $totalLength - ($digitIndex - $j),
                    ' ',
                    STR_PAD_LEFT);
            }

            $results[] = str_pad(
                $firstValue,
                $totalLength,
                ' ',
                STR_PAD_LEFT
            );
            $results[] = str_pad(
                $operator . $secondValue,
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
            break;
        default:
            $result = 'Invalid operator';
            break;
    }
    $results[] = '';
}
foreach ($results as $result) {
    echo $result . PHP_EOL;
}