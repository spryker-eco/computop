<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business;

use Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\ComputopFacade;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Business
 * @group SaveOrderPaymentTest
 */
class SaveOrderPaymentTest extends AbstractOrderPaymentTest
{

    /**
     * @return void
     */
    public function testSaveOrderPaymentSuccess()
    {
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $service->saveOrderPayment($this->createQuoteTransfer(), $this->createCheckoutResponse());

        $computopSavedData = $this->getComputopSavedData();

        $this->assertSame(self::CLIENT_IP_VALUE, $computopSavedData->getClientIp());
        $this->assertSame(self::PAY_ID_VALUE, $computopSavedData->getPayId());
        $this->assertSame(self::TRANS_ID_VALUE, $computopSavedData->getTransId());
        $this->assertSame(ComputopConstants::PAYMENT_METHOD_CREDIT_CARD, $computopSavedData->getPaymentMethod());
        $this->assertSame($this->orderEntity->getIdSalesOrder(), $computopSavedData->getFkSalesOrder());
        $this->assertSame($this->orderEntity->getOrderReference(), $computopSavedData->getReference());
    }

    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    protected function getComputopSavedData()
    {
        $query = new SpyPaymentComputopQuery();

        return $query->find()->getFirst();
    }

}
