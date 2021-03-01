<?php

namespace SprykerEco\Yves\Computop\Form;

use Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayuCeeSingleSubForm extends AbstractSubForm
{
    public const PAYMENT_METHOD = 'payu-cee-single';

    /**
     * @return string
     */
    public function getName()
    {
        return PaymentTransfer::COMPUTOP_PAYU_CEE_SINGLE;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return PaymentTransfer::COMPUTOP_PAYU_CEE_SINGLE;
    }

    /**
     * @return string
     */
    protected function getTemplatePath()
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
            'data_class' => ComputopPayuCeeSinglePaymentTransfer::class,
        ])->setRequired(self::OPTIONS_FIELD_NAME);
    }
}
