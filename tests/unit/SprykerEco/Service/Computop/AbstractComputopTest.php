<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Service\Computop;

use Codeception\TestCase\Test;
use SprykerEco\Service\Computop\ComputopConfig;

abstract class AbstractComputopTest extends Test
{

    const PASSWORD = 'password';

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createComputopConfigMock()
    {
        $configMock = $this->createPartialMock(
            ComputopConfig::class,
            ['getHmacPassword']
        );

        $configMock->method('getHmacPassword')
            ->willReturn(self::PASSWORD);

        return $configMock;
    }

}
