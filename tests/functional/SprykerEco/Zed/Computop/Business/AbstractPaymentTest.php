<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business;

use Functional\SprykerEco\Zed\Computop\AbstractSetUpTest;
use GuzzleHttp\Psr7;
use SprykerEco\Service\Computop\ComputopService;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\Api\Adapter\AuthorizeApiAdapter;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceBridge;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;

abstract class AbstractPaymentTest extends AbstractSetUpTest
{

    const DATA_AUTHORIZE_VALUE = 'A23501461AA31D2A7DE3ABDBAB085C698893913332CB67E1A807FD59E1F69EB838219E36BBA905CA9085CECB6F9719E1CDA2CE46F17120C106A58D32E2130F61199DE0A73DA1DD7BFD571C4C5DE099825FB9F264DBFA52BF863B3941BC327A15A99FF527422CE878508ACC02E13D9851024D23B99285545222833BCB861E6471E5712BDA3A493433F07857CD3937652E8610531FC7A8D301559CEED3BC0FA14971922F5D33B6964034477F769880CD30EF3E6BC7B3D2F199DB124B974F304C742DF697E4A3F784EBB63EFF9FA71CC905703A4AA73E1C751D1B4EB02D449C8AFA';
    const LEN_AUTHORIZE_VALUE = 223;

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected $orderEntity;

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | ComputopBusinessFactory
     */
    protected function createFactory()
    {
        $builder = $this->getMockBuilder(ComputopBusinessFactory::class);

        $builder->setMethods(
            [
                'getConfig',
                'getComputopService',
                'createAuthorizeAdapter',
                'getQueryContainer',
            ]
        );

        $stub = $builder->getMock();

        $stub->method('getConfig')
            ->willReturn($this->createConfig());

        $stub->method('getComputopService')
            ->willReturn(new ComputopToComputopServiceBridge(new ComputopService()));

        $stub->method('createAuthorizeAdapter')
            ->willReturn($this->getAuthorizeApiAdapter());

        $stub->method('getQueryContainer')
            ->willReturn(new ComputopQueryContainer());

        return $stub;
    }

    /**
     * @return \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected function createConfig()
    {
        return new ComputopConfig;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getAuthorizeApiAdapter()
    {
        $mock = $this->createPartialMock(AuthorizeApiAdapter::class, ['sendRequest']);

        $mock->method('sendRequest')
            ->willReturn($this->getAuthorizeStream());

        return $mock;
    }

    /**
     * @return Psr7\Stream
     */
    protected function getAuthorizeStream()
    {
        $expectedResponse = http_build_query([
            ComputopConstants::DATA_F_N => self::DATA_AUTHORIZE_VALUE,
            ComputopConstants::LEN_F_N => self::LEN_AUTHORIZE_VALUE,
        ]);
        $stream = Psr7\stream_for($expectedResponse);

        return $stream;
    }

}
