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
use SprykerEco\Zed\Computop\Business\Api\Adapter\CaptureApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Adapter\InquireApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Adapter\RefundApiAdapter;
use SprykerEco\Zed\Computop\Business\Api\Adapter\ReverseApiAdapter;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceBridge;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;

abstract class AbstractPaymentTest extends AbstractSetUpTest
{

    const DATA_AUTHORIZE_VALUE = 'A23501461AA31D2A7DE3ABDBAB085C698893913332CB67E1A807FD59E1F69EB838219E36BBA905CA9085CECB6F9719E1CDA2CE46F17120C106A58D32E2130F61199DE0A73DA1DD7BFD571C4C5DE099825FB9F264DBFA52BF863B3941BC327A15A99FF527422CE878508ACC02E13D9851024D23B99285545222833BCB861E6471E5712BDA3A493433F07857CD3937652E8610531FC7A8D301559CEED3BC0FA14971922F5D33B6964034477F769880CD30EF3E6BC7B3D2F199DB124B974F304C742DF697E4A3F784EBB63EFF9FA71CC905703A4AA73E1C751D1B4EB02D449C8AFA';
    const LEN_AUTHORIZE_VALUE = 223;
    const DATA_AUTHORIZE_ERROR_VALUE = 'A23501461AA31D2A7DE3ABDBAB085C698893913332CB67E1A807FD59E1F69EB838219E36BBA905CA9085CECB6F9719E1CDA2CE46F17120C100C99FAF666B2BA7D6C614E0A24B649DAAAAF114266FEB5F863D6C96C545DEBB7E6DF0BAC2D5C360A99FF527422CE878508ACC02E13D9851024D23B99285545222833BCB861E6471D79F977307C3756B94039F33083D047B41FC940CFA47257DF5D1B483172925FF80331238C6F545F137732661E07C0C786D99FE159B3366048F04F08BE4BC0C5B';
    const LEN_AUTHORIZE_ERROR_VALUE = 192;

    const DATA_INQUIRE_VALUE = 'A23501461AA31D2A7DE3ABDBAB085C6938916FD8E29AB866F1A20FC03A17576AFCA8C5B645E5D191F781B4240908B5312427DBF4B95A90E6622991FD3247ADD1E6814FAC6B202DBF6B1AD8C1A64D8CF39D0C511883F14B9FDFE0B5F4DFBE6FECC1C36306121044872989FF3F065FE3FCF9A82F950BE564E97B0DD3E834F33D28ADD81D7485794BAFA4067217FCD5FBBE532F86D88BFCCF78B75C2C2C8876C11749CA85AFB166C2A3A06B38551659693F99591F5BB556954A5D1CC745E54980F7';
    const LEN_INQUIRE_VALUE = 189;

    const DATA_CAPTURE_VALUE = 'A23501461AA31D2A7DE3ABDBAB085C69D64A63583A9918823EE25F984198B24416307CC9F716E339447048AAFA6DAEAF1FE56931868EC6658FEC353615B935F22C66BC6A762E02F1D9D1692A94044C37B12C35E04B693BF8FC482FC0DB76A8EEF64BBDA30D301B0E20EBAD824394932159A025D5FAD81F3059E75755A9D2DF460D45A61E2D011772F07857CD3937652E8610531FC7A8D301559CEED3BC0FA14971922F5D33B6964034477F769880CD30EF3E6BC7B3D2F199DB124B974F304C742DF697E4A3F784EBB63EFF9FA71CC905703A4AA73E1C751D1B4EB02D449C8AFA';
    const LEN_CAPTURE_VALUE = 223;

    const DATA_REVERSE_VALUE = 'A23501461AA31D2A7DE3ABDBAB085C69079995CF003AAE802B1F6ED88960C444DA38BF186609D77937F48C789D95C374240BD049073027CAD356AF0EDD2D2D1EC5BFE71E5D72BF0C6896F77DDB488245DCDC858B4A88BB77F7E15FA3471C7A616BC07B6B5EEC3121AE739182FEA6B87DE18DA776D8C306BC7CC1110200055E956C7A8664D870F3ABF07857CD3937652E8610531FC7A8D301559CEED3BC0FA14971922F5D33B6964034477F769880CD30EF3E6BC7B3D2F199DB124B974F304C742DF697E4A3F784EBB63EFF9FA71CC905703A4AA73E1C751D1B4EB02D449C8AFA';
    const LEN_REVERSE_VALUE = 223;

    const DATA_REFUND_VALUE = 'A23501461AA31D2A7DE3ABDBAB085C69BA9E56B96CDF8D7E1D11289D7BBB9EA91C89BCEE9A870EF5E79EDC8EB013A02B04F268FFE54CAF2AEC985BF06992E8FBCFF6DB02BE9227731AD59EF6B3D24C1BB6285960A37E7FFA69ED6D8D2D4972BC03BA1611F8C8797832EFA0E00DCE63BA84275716D47B5431B25F09A5BA1C42A8EAFE2D3B67FD5C7BF07857CD3937652E8610531FC7A8D301559CEED3BC0FA14971922F5D33B6964034477F769880CD30EF3E6BC7B3D2F199DB124B974F304C742DF697E4A3F784EBB63EFF9FA71CC905703A4AA73E1C751D1B4EB02D449C8AFA';
    const LEN_REFUND_VALUE = 223;

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
                'createInquireAdapter',
                'createCaptureAdapter',
                'createRefundAdapter',
                'createReverseAdapter',
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

        $stub->method('createInquireAdapter')
            ->willReturn($this->getInquireApiAdapter());

        $stub->method('createCaptureAdapter')
            ->willReturn($this->getCaptureApiAdapter());

        $stub->method('createRefundAdapter')
            ->willReturn($this->getRefundApiAdapter());

        $stub->method('createReverseAdapter')
            ->willReturn($this->getReverseApiAdapter());

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
            ->willReturn($this->getStream(self::DATA_AUTHORIZE_VALUE, self::LEN_AUTHORIZE_VALUE));

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getInquireApiAdapter()
    {
        $mock = $this->createPartialMock(InquireApiAdapter::class, ['sendRequest']);

        $mock->method('sendRequest')
            ->willReturn($this->getStream(self::DATA_INQUIRE_VALUE, self::LEN_INQUIRE_VALUE));

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCaptureApiAdapter()
    {
        $mock = $this->createPartialMock(CaptureApiAdapter::class, ['sendRequest']);

        $mock->method('sendRequest')
            ->willReturn($this->getStream(self::DATA_CAPTURE_VALUE, self::LEN_CAPTURE_VALUE));

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getRefundApiAdapter()
    {
        $mock = $this->createPartialMock(RefundApiAdapter::class, ['sendRequest']);

        $mock->method('sendRequest')
            ->willReturn($this->getStream(self::DATA_REFUND_VALUE, self::LEN_REFUND_VALUE));

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getReverseApiAdapter()
    {
        $mock = $this->createPartialMock(ReverseApiAdapter::class, ['sendRequest']);

        $mock->method('sendRequest')
            ->willReturn($this->getStream(self::DATA_REVERSE_VALUE, self::LEN_REVERSE_VALUE));

        return $mock;
    }

    /**
     * @param string $data
     * @param string $len
     *
     * @return Psr7\Stream
     */
    protected function getStream($data, $len)
    {
        $expectedResponse = http_build_query([
            ComputopConstants::DATA_F_N => $data,
            ComputopConstants::LEN_F_N => $len,
        ]);
        $stream = Psr7\stream_for($expectedResponse);

        return $stream;
    }

}
