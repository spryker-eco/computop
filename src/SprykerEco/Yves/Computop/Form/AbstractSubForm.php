<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form;

use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{

    const FIELD_URL = 'url';

    /**
     * @return string
     */
    public function getProviderName()
    {
        return ComputopConstants::PROVIDER_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addMerchantId(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            ComputopFieldNameConstants::MERCHANT_ID,
            'hidden'
        );
        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addData(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            ComputopFieldNameConstants::DATA,
            'hidden'
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addLen(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            ComputopFieldNameConstants::LENGTH,
            'hidden'
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addLink(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_URL,
            'hidden'
        );

        return $this;
    }

}
