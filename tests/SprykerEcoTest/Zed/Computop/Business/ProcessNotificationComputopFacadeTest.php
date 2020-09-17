<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business;

use Codeception\Test\Unit;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopNotificationQuery;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItemQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerEcoTest
 * @group Zed
 * @group Computop
 * @group Business
 * @group ProcessNotificationComputopFacadeTest
 * Add your own group annotations below this line
 */
class ProcessNotificationComputopFacadeTest extends Unit
{
    /**
     * @var \SprykerEcoTest\Zed\Computop\ComputopZedTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCanSaveNotificationAndUpdateOrderItems(): void
    {
        // Arrange
        $this->tester->createPaymentComputop();
        $computopNotificationTransfer = $this->tester->createComputopNotificationTransfer();

        // Act
        $this->tester->getFacade()->processNotification($computopNotificationTransfer);
        $paymentComputopNotificationEntity = SpyPaymentComputopNotificationQuery::create()
            ->filterByTransId($computopNotificationTransfer->getTransId())
            ->filterByPayId($computopNotificationTransfer->getPayId())
            ->filterByXId($computopNotificationTransfer->getXId())
            ->findOne();

        $paymentComputopOrderItemEntities = SpyPaymentComputopOrderItemQuery::create()
            ->useSpyPaymentComputopQuery()
                ->filterByTransId($computopNotificationTransfer->getTransId())
                ->filterByPayId($computopNotificationTransfer->getPayId())
            ->endUse()
            ->find();

        // Assert
        $this->assertNotNull($paymentComputopNotificationEntity, 'Push notification saved into DB.');
        $this->assertNotEmpty($paymentComputopOrderItemEntities, 'Computop order items can be found.');

        foreach ($paymentComputopOrderItemEntities as $paymentComputopOrderItemEntity) {
            $this->assertTrue(
                $paymentComputopOrderItemEntity->getIsPaymentConfirmed(),
                'Push notification updated computop order item `is_payment_confirmed` field.'
            );
        }
    }
}
