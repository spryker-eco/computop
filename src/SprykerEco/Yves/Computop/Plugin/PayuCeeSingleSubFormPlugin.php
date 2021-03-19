<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;
use SprykerEco\Yves\Computop\Form\DataProvider\PayuCeeSingleFormDataProvider;
use SprykerEco\Yves\Computop\Form\PayuCeeSingleSubForm;

/**
 * @method \SprykerEco\Yves\Computop\ComputopFactory getFactory()
 */
class PayuCeeSingleSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    /**
     * @return \SprykerEco\Yves\Computop\Form\PayuCeeSingleSubForm
     */
    public function createSubForm(): PayuCeeSingleSubForm
    {
        return $this->getFactory()->createPayUCeeSingleForm();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Form\DataProvider\PayuCeeSingleFormDataProvider
     */
    public function createSubFormDataProvider(): PayuCeeSingleFormDataProvider
    {
        return $this->getFactory()->createPayUCeeSingleFormDataProvider();
    }
}
