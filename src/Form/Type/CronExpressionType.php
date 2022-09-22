<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Form\DataTransformer\CronExpressionToPartsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

final class CronExpressionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addViewTransformer(new CronExpressionToPartsTransformer())
            ->add('minutes', ChoiceType::class, [
                'choices' => array_merge(['*' => '*'], range(0, 59)),
                'multiple' => true,
                'required' => false,
            ])
            ->add('hours', ChoiceType::class, [
                'choices' => array_merge(['*' => '*'], range(0, 23)),
                'multiple' => true,
                'required' => false,
            ])
            ->add('days', ChoiceType::class, [
                'choices' => array_merge(['*' => '*'], $this->oneIndexedRange(31)),
                'multiple' => true,
                'required' => false,
            ])
            ->add('months', ChoiceType::class, [
                'choices' => array_merge(['*' => '*'], $this->oneIndexedRange(12)),
                'multiple' => true,
                'required' => false,
            ])
            ->add('weekdays', ChoiceType::class, [
                'choices' => array_merge(['*' => '*'], $this->oneIndexedRange(7)),
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    /**
     * Will create an array where the first key is 1
     * oneIndexedRange(3) will return [1 => 1, 2 => 2, 3 => 3].
     */
    private function oneIndexedRange(int $end): array
    {
        $arr = range(0, $end);
        unset($arr[0]);

        return $arr;
    }
}
