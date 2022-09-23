<?php

declare(strict_types=1);

namespace App\Form\TypeGuesser;

use Brick\Reflection\ReflectionTools;
use Cron\CronExpression;
use ReflectionClass;
use ReflectionException;
use Setono\CronExpressionBundle\Form\Type\CronExpressionType;
use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Guess\ValueGuess;

final class CronExpressionTypeGuesser implements FormTypeGuesserInterface
{
    public function guessType(string $class, string $property): ?TypeGuess
    {
        if (!class_exists($class)) {
            return null;
        }

        try {
            $reflectionClass = new ReflectionClass($class);
            $reflectionProperty = $reflectionClass->getProperty($property);
        } catch (ReflectionException $e) {
            return null;
        }

        $reflectionTools = new ReflectionTools();
        $propertyTypes = $reflectionTools->getPropertyTypes($reflectionProperty);

        if (in_array(CronExpression::class, $propertyTypes, true)) {
            return new TypeGuess(CronExpressionType::class, [], Guess::VERY_HIGH_CONFIDENCE);
        }

        return null;
    }

    public function guessRequired(string $class, string $property): ?ValueGuess
    {
        return null;
    }

    public function guessMaxLength(string $class, string $property): ?ValueGuess
    {
        return null;
    }

    public function guessPattern(string $class, string $property): ?ValueGuess
    {
        return null;
    }
}
