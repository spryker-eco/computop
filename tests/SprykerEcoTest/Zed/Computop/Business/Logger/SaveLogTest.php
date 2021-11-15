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
    /**
     * @var string
     */
    public const METHOD_VALUE = 'METHOD';

    /**
     * @var string
     */
    public const PAY_ID_VALUE = 'PAY_ID_VALUE';

    /**
     * @var string
     */
    public const X_ID_VALUE = 'X_ID_VALUE';

    /**
     * @var string
     */
    public const M_ID_VALUE = 'M_ID_VALUE';

    /**
     * @var string
     */
    public const TRANS_ID_VALUE = 'TRANS_ID_VALUE';

    /**
     * @var string
     */
    public const STATUS_VALUE = 'OK';

    /**
     * @var string
     */
    public const CODE_VALUE = '00000000';

    /**
     * @var string
     */
    public const DESCRIPTION_VALUE = 'DESCRIPTION_VALUE';

    /**
     * @return void
     */
    public function testSaveLogSuccess()
    {
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $service->logResponseHeader($this->createHeader(), static::METHOD_VALUE);

        /** @var \Orm\Zed\Computop\Persistence\SpyPaymentComputopApiLog $logSavedData */
        $logSavedData = $this->getLogSavedData();

        $this->assertSame(static::TRANS_ID_VALUE, $logSavedData->getTransId());
        $this->assertSame(static::PAY_ID_VALUE, $logSavedData->getPayId());
        $this->assertSame(static::M_ID_VALUE, $logSavedData->getMId());
        $this->assertSame(static::X_ID_VALUE, $logSavedData->getXId());
        $this->assertEquals(static::CODE_VALUE, $logSavedData->getCode());
        $this->assertSame(static::DESCRIPTION_VALUE, $logSavedData->getDescription());
        $this->assertSame(static::STATUS_VALUE, $logSavedData->getStatus());
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer
     */
    protected function createHeader()
    {
        $header = new ComputopApiResponseHeaderTransfer();
        $header
            ->setTransId(static::TRANS_ID_VALUE)
            ->setPayId(static::PAY_ID_VALUE)
            ->setMId(static::M_ID_VALUE)
            ->setXId(static::X_ID_VALUE)
            ->setCode(static::CODE_VALUE)
            ->setDescription(static::DESCRIPTION_VALUE)
            ->setStatus(static::STATUS_VALUE);

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
     * @return \SprykerEco\Zed\Computop\Business\ComputopBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createFactory(): ComputopBusinessFactory
    {
        $builder = $this->getMockBuilder(ComputopBusinessFactory::class);
        $builder->setMethods(
            [
                'getQueryContainer',
            ],
        );

        $stub = $builder->getMock();
        $stub->method('getQueryContainer')
            ->willReturn(new ComputopQueryContainer());

        return $stub;
    }
}
