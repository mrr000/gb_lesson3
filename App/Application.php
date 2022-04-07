<?php

namespace App;

use App\Math\DigitExpressionUnit;
use App\Math\ExpressionUnit;
use App\Math\IFunctionExpressionUnit;
use App\Math\IMathSequence;
use App\Math\MathSequenceCalculator;
use App\Math\RPNSequencer;
use App\Utils\{IReader, IWriter, Stack};

/**
 * Программа переводит строку математического выражения из инфиксной записи
 * в обратную польскую нотацию
 * и считает резуьтат
 */
class Application
{
    /**
     * @param IReader $reader
     * @param IWriter $writer
     */
    public function __construct(private IReader $reader,
                                private IWriter $writer)
    {
    }

    private function writeHint(): void
    {
        $this->writer->writeLine('Пример выражения для расчёта:');
        $this->writer->writeLine('2 / (3 + 2) * 5');
    }

    /**
     * main
     */
    public function main(): void
    {
        try {
            $input = $this->readInput();
            $mathSequence = $this->convertToMathSequence($input);

            $this->writer->writeLine($mathSequence->toString());

            $result = $this->calculateSequence($mathSequence);

            $this->writer->writeLine($result);
        } catch (\ValueError $throwable) {
            $this->writer->writeLine($throwable->getMessage());
            $this->writeHint();
        } catch (\DivisionByZeroError $exception) {
            $this->writer->writeLine('Выражение содержит деление на ноль');
        } catch (\Throwable $throwable) {
            $this->writer->writeLine($throwable->getMessage());
        }
    }

    private function readInput(): string
    {
        $readString = $this->reader->readString();
        if ('' === ($string = $this->removeSpaces($readString))) {
            throw new \ValueError('Вы не ввели выражение для расчёта');
        }
        return $string;
    }

    private function removeSpaces(string $string): string
    {
        return str_replace([' ', ',', PHP_EOL], ['', '.', ''] ,$string);
    }

    /**
     * @throws \Exception
     */
    private function convertToMathSequence(string $string): IMathSequence
    {
        return new RPNSequencer($string);
    }

    /**
     * @throws \Exception
     */
    private function calculateSequence(IMathSequence $sequence): float
    {
        $calculator = new MathSequenceCalculator();

        $sequenceStack = $sequence->getStack()->reverse();

        return $calculator->stackCalculate($sequenceStack);
    }
}