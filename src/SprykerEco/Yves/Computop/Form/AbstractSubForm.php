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
    const FIELD_TRANS_ID = 'TransID';
    const FIELD_AMOUNT = 'Amount';
    const FIELD_CURRENCY = 'Currency';
    const FIELD_MAC = 'MAC';
    const FIELD_URL_SUCCESS = 'URLSuccess';
    const FIELD_URL_FAILURE = 'URLFailure';
    const FIELD_URL_NOTIFY = 'URLNotify';
    const FIELD_RESPONSE = 'Response';
    const FIELD_USER_DATA = 'UserData';
    const FIELD_CAPTURE = 'Capture';
    const FIELD_REQ_ID = 'ReqID';
    const FIELD_PLAIN = 'Plain';
    const FIELD_CUSTOM = 'Custom';
    const FIELD_EXPIRATION_TIME = 'expirationTime';
    const FIELD_CUSTOM_FIELD = 'CustomField';

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
            'text'
        );
        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addTransId(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_TRANS_ID,
            'text'
        );
        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAmount(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_AMOUNT,
            'text'
        );
        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCurrency(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_CURRENCY,
            'text'
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addMAC(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_MAC,
            'text'
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addUrlSuccess(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_URL_SUCCESS,
            'text'
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addUrlFailure(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_URL_FAILURE,
            'text'
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addUrlNotify(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_URL_NOTIFY,
            'text'
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addResponse(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_RESPONSE,
            'text'
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addUserData(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_USER_DATA,
            'text'
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCapture(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_CAPTURE,
            'text'
        );

        return $this;
    }

}
