<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form;

use Generated\Shared\Transfer\ComputopEasyCreditPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EasyCreditSubForm extends AbstractSubForm
{
    const PAYMENT_METHOD = 'easy_credit';

    /**
     * @return string
     */
    public function getName()
    {
        return PaymentTransfer::COMPUTOP_EASY_CREDIT;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return PaymentTransfer::COMPUTOP_EASY_CREDIT;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return ComputopConfig::PROVIDER_NAME . '/' . self::PAYMENT_METHOD;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addLink($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComputopEasyCreditPaymentTransfer::class,
        ])->setRequired(self::OPTIONS_FIELD_NAME);
    }
}
