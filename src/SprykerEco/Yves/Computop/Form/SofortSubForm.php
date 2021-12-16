<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form;

use Generated\Shared\Transfer\ComputopSofortPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SofortSubForm extends AbstractSubForm
{
    /**
     * @var string
     */
    public const PAYMENT_METHOD = 'sofort';

    /**
     * @return string
     */
    public function getName(): string
    {
        return PaymentTransfer::COMPUTOP_SOFORT;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return PaymentTransfer::COMPUTOP_SOFORT;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ComputopSofortPaymentTransfer::class,
        ])->setRequired(static::OPTIONS_FIELD_NAME);
    }
}
