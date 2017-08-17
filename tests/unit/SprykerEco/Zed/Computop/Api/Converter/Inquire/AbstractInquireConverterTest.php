<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Zed\Computop\Api\Converter\Inquire;

use SprykerEco\Zed\Computop\Business\Api\Converter\InquireConverter;
use Unit\SprykerEco\Zed\Computop\Api\Converter\AbstractConverterTest;

abstract class AbstractInquireConverterTest extends AbstractConverterTest
{

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\AuthorizeConverter
     */
    protected function createConverter()
    {
        $computopServiceMock = $this->createComputopServiceMock();
        $configMock = $this->createComputopConfigMock();

        $converter = new InquireConverter($computopServiceMock, $configMock);

        return $converter;
    }

}
