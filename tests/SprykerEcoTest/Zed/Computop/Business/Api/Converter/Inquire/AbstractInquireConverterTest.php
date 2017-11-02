<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business\Api\Converter\Inquire;

use SprykerEco\Zed\Computop\Business\Api\Converter\InquireConverter;
use SprykerEcoTest\Zed\Computop\Business\Api\Converter\AbstractConverterTest;

abstract class AbstractInquireConverterTest extends AbstractConverterTest
{
    /**
     * @return array
     */
    abstract protected function getDecryptedArray();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\InquireConverter
     */
    protected function createConverter()
    {
        $computopServiceMock = $this->helper->createComputopServiceMock($this->getDecryptedArray());
        $configMock = $this->helper->createComputopConfigMock();

        $converter = new InquireConverter($computopServiceMock, $configMock);

        return $converter;
    }
}
