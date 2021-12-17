<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form;

use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use SprykerEco\Shared\Computop\ComputopConfig;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

abstract class AbstractSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    /**
     * @var string
     */
    public const FIELD_URL = 'url';

    /**
     * @var string
     */
    protected const TEMPLATE_PATH_PATTERN = '%s/%s';

    /**
     * @return string
     */
    abstract protected function getPaymentMethod(): string;

    /**
     * @return string
     */
    public function getProviderName(): string
    {
        return ComputopConfig::PROVIDER_NAME;
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return sprintf(static::TEMPLATE_PATH_PATTERN, $this->getProviderName(), $this->getPaymentMethod());
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
            static::FIELD_URL,
            'hidden',
        );

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createNotBlankConstraint(): Constraint
    {
        return new NotBlank(['groups' => $this->getPropertyPath()]);
    }
}
