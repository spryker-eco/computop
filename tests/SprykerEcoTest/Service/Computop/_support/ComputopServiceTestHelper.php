<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Service\Computop;

use Codeception\TestCase\Test;
use SprykerEco\Service\Computop\ComputopConfig;
use SprykerEco\Service\Computop\Model\Converter\ComputopConverter;
use SprykerEco\Service\Computop\Model\Mapper\ComputopMapper;

class ComputopServiceTestHelper extends Test
{
    const PASSWORD = 'password';

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createComputopConfigMock()
    {
        $configMock = $this->createPartialMock(
            ComputopConfig::class,
            ['getHmacPassword']
        );

        $configMock->method('getHmacPassword')
            ->willReturn(self::PASSWORD);

        return $configMock;
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\Mapper\ComputopMapperInterface
     */
    public function createMapper()
    {
        return new ComputopMapper($this->createComputopConfigMock());
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\Converter\ComputopConverterInterface
     */
    public function createConverter()
    {
        return new ComputopConverter($this->createComputopConfigMock());
    }
}
