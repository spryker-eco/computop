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
        return new CreditCardFormDataProvider($this->createComputopService(), $this->createApplication());
    }

    /**
     * @return \SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopServiceInterface
     */
    public function createComputopService()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::COMPUTOP_SERVICE);
    }

    /**
     * @return \Silex\Application
     */
    public function createApplication()
    {
        return $this->getProvidedDependency(ComputopDependencyProvider::PLUGIN_APPLICATION);
    }

}
