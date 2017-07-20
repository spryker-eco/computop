<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerEco\Yves\Computop\Form\CreditCardSubForm;
use SprykerEco\Yves\Computop\Form\DataProvider\CreditCardFormDataProvider;

/**
 */
class ComputopFactory extends AbstractFactory
{

    /**
     * @return \SprykerEco\Yves\Computop\Form\CreditCardSubForm
     */
    public function createCreditCardForm()
    {
        return new CreditCardSubForm();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Form\DataProvider\CreditCardFormDataProvider
     */
    public function createCreditCardFormDataProvider()
    {
        return new CreditCardFormDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteInterface
     */
//    public function getQuoteClient()
//    {
//        return $this->getProvidedDependency(ComputopDependencyProvider::CLIENT_QUOTE);
//    }

}
