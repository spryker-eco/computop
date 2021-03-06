<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form;

use Generated\Shared\Transfer\ComputopPayPalPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayPalSubForm extends AbstractSubForm
{
    public const PAYMENT_METHOD = 'paypal';

    /**
     * @return string
     */
    public function getName()
    {
        return PaymentTransfer::COMPUTOP_PAY_PAL;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return PaymentTransfer::COMPUTOP_PAY_PAL;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return ComputopConfig::PROVIDER_NAME . '/' . self::PAYMENT_METHOD;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComputopPayPalPaymentTransfer::class,
        ])->setRequired(self::OPTIONS_FIELD_NAME);
    }
}
