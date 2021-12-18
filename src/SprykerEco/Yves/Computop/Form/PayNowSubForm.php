<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form;

use Generated\Shared\Transfer\ComputopPayNowPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayNowSubForm extends AbstractSubForm
{
    /**
     * @var string
     */
    public const PAYMENT_METHOD = 'paynow';

    /**
     * @return string
     */
    public function getName(): string
    {
        return PaymentTransfer::COMPUTOP_PAY_NOW;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return PaymentTransfer::COMPUTOP_PAY_NOW;
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return ComputopConfig::PROVIDER_NAME . '/' . static::PAYMENT_METHOD;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ComputopPayNowPaymentTransfer::class,
        ])->setRequired(static::OPTIONS_FIELD_NAME);
    }
}
