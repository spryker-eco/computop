<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form;

use Generated\Shared\Transfer\ComputopIdealPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IdealSubForm extends AbstractSubForm
{
    public const PAYMENT_METHOD = 'ideal';

    /**
     * @return string
     */
    public function getName()
    {
        return PaymentTransfer::COMPUTOP_IDEAL;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return PaymentTransfer::COMPUTOP_IDEAL;
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
            'data_class' => ComputopIdealPaymentTransfer::class,
        ])->setRequired(self::OPTIONS_FIELD_NAME);
    }
}
