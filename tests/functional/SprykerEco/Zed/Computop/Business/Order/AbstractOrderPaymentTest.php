<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business;

use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;

abstract class AbstractOrderPaymentTest extends AbstractFactoryOrderPaymentTest
{

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | ComputopBusinessFactory
     */
    protected function createFactory()
    {
        $builder = $this->getMockBuilder(ComputopBusinessFactory::class);
        $builder->setMethods(
            [
                'getQueryContainer',
            ]
        );

        $stub = $builder->getMock();
        $stub->method('getQueryContainer')
            ->willReturn(new ComputopQueryContainer());

        return $stub;
    }

}
