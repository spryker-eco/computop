<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form;

use Generated\Shared\Transfer\ComputopIdealPaymentTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IdealSubForm extends AbstractSubForm
{
    const PAYMENT_METHOD = 'ideal';

    /**
     * @return string
     */
    public function getName()
    {
        return ComputopConfig::PAYMENT_METHOD_IDEAL;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return ComputopConfig::PAYMENT_METHOD_IDEAL;
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
        $this->addMerchantId($builder, $options);
        $this->addData($builder, $options);
        $this->addLen($builder, $options);

        //TODO: test data - need to remove after test
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
            'data_class' => ComputopIdealPaymentTransfer::class,
        ])->setRequired(self::OPTIONS_FIELD_NAME);
    }
}