<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form;

use Generated\Shared\Transfer\ComputopSofortPaymentTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SofortSubForm extends AbstractSubForm
{

    const PAYMENT_METHOD = 'sofort';

    /**
     * @return string
     */
    public function getName()
    {
        return ComputopConstants::PAYMENT_METHOD_SOFORT;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return ComputopConstants::PAYMENT_METHOD_SOFORT;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return ComputopConstants::PROVIDER_NAME . '/' . self::PAYMENT_METHOD;
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
            'data_class' => ComputopSofortPaymentTransfer::class,
        ])->setRequired(self::OPTIONS_FIELD_NAME);
    }

}
