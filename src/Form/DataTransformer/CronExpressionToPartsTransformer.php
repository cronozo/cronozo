<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use Cron\CronExpression;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

final class CronExpressionToPartsTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): array
    {
        if (null === $value) {
            return [
                'minutes' => ['*'],
                'hours' => ['*'],
                'days' => ['*'],
                'months' => ['*'],
                'weekdays' => ['*'],
            ];
        }

        if (!$value instanceof CronExpression) {
            throw new TransformationFailedException('Expected an instance of ' . CronExpression::class);
        }

        return [
            'minutes' => $this->convertCronString((string) $value->getExpression((string) CronExpression::MINUTE)),
            'hours' => $this->convertCronString((string) $value->getExpression((string) CronExpression::HOUR)),
            'days' => $this->convertCronString((string) $value->getExpression((string) CronExpression::DAY)),
            'months' => $this->convertCronString((string) $value->getExpression((string) CronExpression::MONTH)),
            'weekdays' => $this->convertCronString((string) $value->getExpression((string) CronExpression::WEEKDAY)),
        ];
    }

    public function reverseTransform(mixed $value): CronExpression
    {
        $cronExpression = new CronExpression('* * * * *');

        if (null === $value) {
            return $cronExpression;
        }

        if (!is_array($value)) {
            throw new TransformationFailedException('Expected an instance of array');
        }

        $cronExpression
            ->setPart(CronExpression::MINUTE, $this->convertCronParts($value['minutes']))
            ->setPart(CronExpression::HOUR, $this->convertCronParts($value['hours']))
            ->setPart(CronExpression::DAY, $this->convertCronParts($value['days']))
            ->setPart(CronExpression::MONTH, $this->convertCronParts($value['months']))
            ->setPart(CronExpression::WEEKDAY, $this->convertCronParts($value['weekdays']))
        ;

        return $cronExpression;
    }

    private function convertCronParts(array $cronArray): string
    {
        $cronString = implode(',', $cronArray);

        return '' !== $cronString ? $cronString : '*';
    }

    private function convertCronString(string $cronString): array
    {
        if ('*' === $cronString) {
            return [];
        }

        return explode(',', $cronString);
    }
}
