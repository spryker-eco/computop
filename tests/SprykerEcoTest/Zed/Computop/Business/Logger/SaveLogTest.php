<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business\Logger;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopApiLogQuery;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\Business\ComputopFacade;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;
use SprykerEcoTest\Zed\Computop\Business\AbstractSetUpTest;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Business
 * @group SaveLogTest
 */
class SaveLogTest extends AbstractSetUpTest
{
    public const METHOD_VALUE = 'METHOD';
    public const PAY_ID_VALUE = 'PAY_ID_VALUE';
    public const X_ID_VALUE = 'X_ID_VALUE';
    public const M_ID_VALUE = 'M_ID_VALUE';
    public const TRANS_ID_VALUE = 'TRANS_ID_VALUE';
    public const STATUS_VALUE = 'OK';
    public const CODE_VALUE = '00000000';
    public const DESCRIPTION_VALUE = 'DESCRIPTION_VALUE';

    /**
     * @return void
     */
    public function testSaveLogSuccess()
    {
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $service->logResponseHeader($this->createHeader(), self::METHOD_VALUE);

        /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputopApiLog $logSavedData */
        $logSavedData = $this->getLogSavedData();

        $this->assertSame(self::TRANS_ID_VALUE, $logSavedData->getTransId());
        $this->assertSame(self::PAY_ID_VALUE, $logSavedData->getPayId());
        $this->assertSame(self::M_ID_VALUE, $logSavedData->getMId());
        $this->assertSame(self::X_ID_VALUE, $logSavedData->getXId());
        $this->assertEquals(self::CODE_VALUE, $logSavedData->getCode());
        $this->assertSame(self::DESCRIPTION_VALUE, $logSavedData->getDescription());
        $this->assertSame(self::STATUS_VALUE, $logSavedData->getStatus());
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer
     */
    protected function createHeader()
    {
        $header = new ComputopApiResponseHeaderTransfer();
        $header
            ->setTransId(self::TRANS_ID_VALUE)
            ->setPayId(self::PAY_ID_VALUE)
            ->setMId(self::M_ID_VALUE)
            ->setXId(self::X_ID_VALUE)
            ->setCode(self::CODE_VALUE)
            ->setDescription(self::DESCRIPTION_VALUE)
            ->setStatus(self::STATUS_VALUE);

        return $header;
    }

    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopApiLogQuery
     */
    protected function getLogSavedData()
    {
        $query = new SpyPaymentComputopApiLogQuery();

        return $query->find()->getLast();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Zed\Computop\Business\ComputopBusinessFactory
     */
    protected function createFactory(): ComputopBusinessFactory
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
