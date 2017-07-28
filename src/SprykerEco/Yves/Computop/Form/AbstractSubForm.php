<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form;

use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractSubForm extends AbstractSubFormType implements SubFormInterface
{

    const FIELD_MERCHANT_ID = 'MerchantID';
    const FIELD_DATA = 'Data';
    const FIELD_LEN = 'Len';
    const FIELD_URL = 'url';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addMerchantId(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_MERCHANT_ID,
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
            self::FIELD_DATA,
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
            self::FIELD_LEN,
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
