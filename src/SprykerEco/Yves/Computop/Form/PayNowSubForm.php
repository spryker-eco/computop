<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form;

use Generated\Shared\Transfer\ComputopPayNowPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayNowSubForm extends AbstractSubForm
{
    const PAYMENT_METHOD = 'paynow';

    const FIELD_CREDIT_CARD_BRAND = 'credit_card_brand';
    const FIELD_CREDIT_CARD_NUMBER = 'credit_card_number';
    const FIELD_CREDIT_CARD_VERIFICATION_CODE = 'credit_card_verification_code';
    const FIELD_CREDIT_CARD_EXPIRES_MONTH = 'credit_card_expires_month';
    const FIELD_CREDIT_CARD_EXPIRES_YEAR = 'credit_card_expires_year';

    const OPTION_CREDIT_CARD_BRAND_CHOICES = 'brand choices';
    const OPTION_CREDIT_CARD_EXPIRES_MONTH_CHOICES = 'month choices';
    const OPTION_CREDIT_CARD_EXPIRES_YEAR_CHOICES = 'year choices';

    /**
     * @return string
     */
    public function getName()
    {
        return PaymentTransfer::COMPUTOP_PAY_NOW;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return PaymentTransfer::COMPUTOP_PAY_NOW;
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
            'data_class' => ComputopPayNowPaymentTransfer::class,
        ])->setRequired(self::OPTIONS_FIELD_NAME);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCardBrand($builder, $options)
            ->addCardNumber($builder)
            ->addCardExpiresMonth($builder, $options)
            ->addCardExpiresYear($builder, $options)
            ->addCardSecurityCode($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \SprykerEco\Yves\Computop\Form\PayNowSubForm
     */
    protected function addCardBrand(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_CREDIT_CARD_BRAND,
            ChoiceType::class,
            [
                'choices' => array_flip($options[self::OPTIONS_FIELD_NAME][self::OPTION_CREDIT_CARD_BRAND_CHOICES]),
                'label' => 'Credit Card Brand',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'placeholder' => false,
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \SprykerEco\Yves\Computop\Form\PayNowSubForm
     */
    protected function addCardNumber(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_CREDIT_CARD_NUMBER,
            TextType::class,
            [
                'label' => 'Credit Card Number',
                'required' => true,
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \SprykerEco\Yves\Computop\Form\PayNowSubForm
     */
    protected function addCardExpiresMonth(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_CREDIT_CARD_EXPIRES_MONTH,
            ChoiceType::class,
            [
                'label' => 'Expires Month',
                'choices' => array_flip($options[self::OPTIONS_FIELD_NAME][self::OPTION_CREDIT_CARD_EXPIRES_MONTH_CHOICES]),
                'choices_as_values' => true,
                'required' => true,
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \SprykerEco\Yves\Computop\Form\PayNowSubForm
     */
    protected function addCardExpiresYear(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_CREDIT_CARD_EXPIRES_YEAR,
            ChoiceType::class,
            [
                'label' => 'Expires Year',
                'choices' => array_flip($options[self::OPTIONS_FIELD_NAME][self::OPTION_CREDIT_CARD_EXPIRES_YEAR_CHOICES]),
                'choices_as_values' => true,
                'required' => true,
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \SprykerEco\Yves\Computop\Form\PayNowSubForm
     */
    protected function addCardSecurityCode(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_CREDIT_CARD_VERIFICATION_CODE,
            TextType::class,
            [
                'label' => 'CVV',
                'required' => true,
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ]
        );

        return $this;
    }
}
