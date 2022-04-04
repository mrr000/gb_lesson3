<?php

define('SIGN_CHANGE', '~');

/**
 * Для тестирования в онлайн редакторе пригодится
 * @return string
 */
function getExampleValue(): string
{
    return '2 / (3 + 2) * 5 * -1';
}

function readCLIInput(array $cliArguments): ?string
{
    return $cliArguments[1] ?? null;
}

function writeLine(string $string): void
{
    echo $string . PHP_EOL;
}

function writeCLIHint(): void
{
    writeLine('Не задана строка-выражение');
    $fileName = __FILE__;
    writeLine('example:');
    writeLine("php {$fileName} '2 / (3 + 2) * 5'");
}

function createStack(): ?array
{
    return [null, null];
}

/**
 * замена array_push($stack, $value)
 * @param array|null $stack
 * @param string $value
 */
function pushToStack(?array &$stack, string $value): void
{
    $newHead = [
        $value,
        $stack
    ];
    $stack = $newHead;
}

/**
 * замена array_pop($stack)
 * @param array|null $stack
 * @return string|null
 */
function popFromStack(?array &$stack): ?string
{
    list($value, $head) = $stack;
    $stack = $head;
    return $value;
}

/**
 * Реализация алгоритма сортировочной станции
 * @param string $input
 * @return string
 * @throws Exception
 */
function sortStation(string $input): string
{
    $priority = [
        SIGN_CHANGE => 1,
        '+' => 1,
        '-' => 1,
        '*' => 2,
        '/' => 2,
        '(' => 0,
        ')' => 0,
    ];
    $operators = ['+', '-', '*', '/'];
    $stack = createStack();
    $resultString = [];
    $len = strlen($input);
    for ($i = 0; $i < $len; $i++) {
        $char = $input[$i];
        $nextChar = $input[$i + 1] ?? null;

        if (is_numeric($char)) {
            // Если символ является числом или постфиксной функцией, добавляем его к выходной строке.
            $resultString[] = $char;
        } elseif (in_array($char, ['('])) {
            // Если символ является открывающей скобкой, помещаем его в стек.
            pushToStack($stack, $char);
        } elseif (in_array($char, [')'])) {
            // Если символ является закрывающей скобкой:
            // До тех пор, пока верхним элементом стека не станет открывающая скобка,
            // выталкиваем элементы из стека в выходную строку.
            // При этом открывающая скобка удаляется из стека, но в выходную строку не добавляется.
            // Если стек закончился раньше, чем мы встретили открывающую скобку, это означает, что в выражении либо неверно поставлен разделитель,
            // либо не согласованы скобки.
            while ('(' !== ($popped = popFromStack($stack))) {
                if (null === $popped) {
                    throw new Exception('Скобки не согласованы');
                }
                $resultString[] = $popped;
            }
        } elseif (in_array($char, $operators)) {
            $popped = popFromStack($stack);
            if (null !== $popped) {
                if ($priority[$popped] >= $priority[$char]) {
                    // todo: продолжать?
                    $resultString[] = $popped;
                } else {
                    pushToStack($stack, $popped);
                }
            }

            pushToStack($stack, $char);

            if (in_array($nextChar, $operators)) {
                // унарные + и -
                if (in_array($nextChar, ['-', '+'])) {
                    // меняем знак выражения, если отрицание
                    if ($nextChar === '-') {
                        pushToStack($stack, SIGN_CHANGE);
                    }
                    // игнорируем $nextChar
                    $i++;
                } else {
                    throw new Exception("Необрабатываемые входные данные: $char $nextChar");
                }
            }
        } else {
            throw new Exception("Необрабатываемые входные данные: $char");
        }
    }

    while (null !== ($popped = popFromStack($stack))) {
        $resultString[] = $popped;
    }

    return implode($resultString);
}

/**
 * @param string $input
 * @return float|null
 * @throws Exception
 */
function calculate(string $input): ?float
{
    $stack = createStack();
    $len = strlen($input);
    for ($i = 0; $i < $len; $i++) {
        $char = $input[$i];
        if (is_numeric($char)) {
            pushToStack($stack, $char);
        } elseif ($char === SIGN_CHANGE) {
            $first = popFromStack($stack);
            $resultString = $first * -1;

            pushToStack($stack, $resultString);
        } elseif ($char === '+') {
            $second = popFromStack($stack);
            $first = popFromStack($stack);
            $resultString = $first + $second;

            pushToStack($stack, $resultString);
        } elseif ($char === '-') {
            $second = popFromStack($stack);
            $first = popFromStack($stack);
            $resultString = $first - $second;

            pushToStack($stack, $resultString);
        } elseif ($char === '*') {
            $second = popFromStack($stack);
            $first = popFromStack($stack);
            $resultString = $first * $second;

            pushToStack($stack, $resultString);
        } elseif ($char === '/') {
            $second = popFromStack($stack);
            $first = popFromStack($stack);
            $resultString = $first / $second;

            pushToStack($stack, $resultString);
        } else {
            throw new Exception('Необрабатываемые входные данные: ' . $char);
        }
    }

    return popFromStack($stack);
}

function clearInput(string $inputString): string
{
    return str_replace(' ', '', $inputString);
}

try {
    if (!isset($_SERVER['SERVER_ADDR'])) {
        $inputString = readCLIInput($argv);
    } else {
        $inputString = getExampleValue();
        writeLine("Запущено не из консоли, берём тестовое значение: $inputString");
    }

    if ($inputString === null) {
        writeCLIHint();
        return 1;
    }

    $inputString = clearInput($inputString);
    $sortResult = sortStation($inputString);
    writeLine($sortResult);
    $calculateResult = calculate($sortResult);
    writeLine($calculateResult);

    return 0;
} catch (DivisionByZeroError $exception) {
    writeLine('Выражение содержит деление на ноль');
} catch (Exception $exception) {
    writeLine($exception->getMessage());
}
