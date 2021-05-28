<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;

/**
 * @method \SprykerEco\Yves\Computop\ComputopFactory getFactory()
 */
class PaydirektSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    /**
     * Specification:
     * - Creates Paydirekt subform.
     *
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createSubForm()
    {
        return $this
            ->getFactory()
            ->createPaydirektForm();
    }

    /**
     * Specification:
     * - Creates Paydirekt subform DataProvider.
     *
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSubFormDataProvider()
    {
        return $this
            ->getFactory()
            ->createPaydirektFormDataProvider();
    }
}
