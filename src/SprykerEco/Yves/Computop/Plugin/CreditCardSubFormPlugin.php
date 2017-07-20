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
class CreditCardSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{

    /**
     * @return \SprykerEco\Yves\Computop\Form\CreditCardSubForm
     */
    public function createSubForm()
    {
        return $this
            ->getFactory()
            ->createCreditCardForm();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Form\DataProvider\CreditCardFormDataProvider
     */
    public function createSubFormDataProvider()
    {
        return $this
            ->getFactory()
            ->createCreditCardFormDataProvider();
    }

}
