<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form;

use Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayuCeeSingleSubForm extends AbstractSubForm
{
    protected const PAYMENT_METHOD = 'payu-cee-single';

    /**
     * @return string
     */
    public function getName(): string
    {
        return PaymentTransfer::COMPUTOP_PAYU_CEE_SINGLE;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return PaymentTransfer::COMPUTOP_PAYU_CEE_SINGLE;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ComputopPayuCeeSinglePaymentTransfer::class,
        ])->setRequired(static::OPTIONS_FIELD_NAME);
    }

    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return sprintf('%s/%s', ComputopConfig::PROVIDER_NAME, static::PAYMENT_METHOD);
    }
}
