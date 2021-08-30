<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Plugin\CheckoutPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;
use SprykerEco\Yves\Computop\Form\DataProvider\PayuCeeSingleFormDataProvider;
use SprykerEco\Yves\Computop\Form\PayuCeeSingleSubForm;

/**
 * @method \SprykerEco\Yves\Computop\ComputopFactory getFactory()
 */
class PayuCeeSingleSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns a sub form for `PayU CEE Single` payment method.
     *
     * @api
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createSubForm(): SubFormInterface
    {
        return $this->getFactory()->createPayuCeeSingleSubForm();
    }

    /**
     * {@inheritDoc}
     * - Returns data provider for `PayU CEE Single` payment method form.
     *
     * @api
     *
     * @return \SprykerEco\Yves\Computop\Form\DataProvider\PayuCeeSingleFormDataProvider
     */
    public function createSubFormDataProvider(): PayuCeeSingleFormDataProvider
    {
        return $this->getFactory()->createPayuCeeSingleFormDataProvider();
    }
}
