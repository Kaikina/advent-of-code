<?php

$lines = file('input.txt');

$monkeys = [];
foreach ($lines as $line) {
    $monkey = substr($line, 0, 4);
    if (preg_match('/^.*: \d*\s?$/', $line)) {
        $monkeys[$monkey] = preg_replace('/\D/', '', $line);
    } else {
        preg_match('/: (\w*) ([+\-\/*]) (\w*)/', $line, $matches);
        $monkeys[$monkey] = [$matches[1], $matches[2], $matches[3]];
    }
}

/**
 * Solves the number the root monkey yells
 *
 * @param $monkeys
 * @param $monkeyToSolve
 *
 * @return array|float|int|mixed
 */
function solve($monkeys, $monkeyToSolve) {
    // Not solved yet
    if (is_array($monkeys[$monkeyToSolve])) {
        switch($monkeys[$monkeyToSolve][1]) {
            case '/':
                return solve($monkeys, $monkeys[$monkeyToSolve][0]) / solve($monkeys, $monkeys[$monkeyToSolve][2]);
            case '*':
                return solve($monkeys, $monkeys[$monkeyToSolve][0]) * solve($monkeys, $monkeys[$monkeyToSolve][2]);
            case '-':
                return solve($monkeys, $monkeys[$monkeyToSolve][0]) - solve($monkeys, $monkeys[$monkeyToSolve][2]);
            case '+':
                return solve($monkeys, $monkeys[$monkeyToSolve][0]) + solve($monkeys, $monkeys[$monkeyToSolve][2]);
        }
    }
    return $monkeys[$monkeyToSolve];
}