<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form;

use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Shared\Computop\ComputopConstants;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractSubForm extends AbstractSubFormType implements SubFormInterface
{

    const FIELD_URL = 'url';
    const FIELD_LENGTH = 'Length';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addMerchantId(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            ComputopConstants::MERCHANT_ID_F_N,
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
            ComputopConstants::DATA_F_N,
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
            self::FIELD_LENGTH,
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
