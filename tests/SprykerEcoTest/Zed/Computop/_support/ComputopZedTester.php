<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop;

use Codeception\Actor;
use Codeception\Scenario;
use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use SprykerEco\Shared\Computop\ComputopConfig;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \SprykerEco\Zed\Computop\Business\ComputopFacadeInterface getFacade($moduleName = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ComputopZedTester extends Actor
{
    use _generated\ComputopZedTesterActions;

    /**
     * @var string
     */
    protected const TEST_STATE_MACHINE_NAME = 'Test01';

    /**
     * @var string
     */
    protected const TRANS_ID_VALUE = 'f7f7f7f7f7f7f7f7f7f7f7f7f7f7f7f7';

    /**
     * @var string
     */
    protected const PAY_ID_VALUE = 'e8e8e8e8e8e8e8e8e8e8e8e8e8e8e8e8';

    /**
     * @var string
     */
    protected const X_ID_VALUE = 'b5b5b5b5b5b5b5b5b5b5b5b5b5b5b5b5';

    /**
     * @var string
     */
    protected const REQ_ID_VALUE = 'a4a4a4a4a4a4a4a4a4a4a4a4a4a4a4a4';

    /**
     * @var string
     */
    protected const CLIENT_IP = '127.0.0.1';

    /**
     * @var string
     */
    protected const TEST_PAYMENT_METHOD = 'computopCreditCard';

    /**
     * @var string
     */
    protected const ORDER_ITEM_STATUS = 'test';

    /**
     * @var string
     */
    protected const NOTIFICATION_CODE = '000000';

    /**
     * @var string
     */
    protected const NOTIFICATION_STATUS = '000000';

    /**
     * @var string
     */
    protected const NOTIFICATION_DESCRIPTION = 'Authentication completed correctly.';

    /**
     * @var string
     */
    protected const NOTIFICATION_TYPE = 'SSL';

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);
        $this->setUpConfig();
    }

    /**
     * Set up config
     *
     * @return void
     */
    public function setUpConfig()
    {
        $this->setConfig('COMPUTOPAPI:MERCHANT_ID', 'COMPUTOP:MERCHANT_ID');
        $this->setConfig('COMPUTOPAPI:HMAC_PASSWORD', 'COMPUTOP:HMAC_PASSWORD');
        $this->setConfig('COMPUTOPAPI:BLOWFISH_PASSWORD', 'COMPUTOP:BLOWFISH_PASSWORD');
        $this->setConfig('COMPUTOP:RESPONSE_MAC_REQUIRED', ['INIT']);
        $this->setConfig('COMPUTOP:PAYMENT_METHODS_WITHOUT_ORDER_CALL', [
            'computopSofort',
            'computopPaydirekt',
            'computopIdeal',
            'computopPayuCeeSingle',
        ]);
        $this->setConfig('COMPUTOP:SOFORT_INIT_ACTION', 'https://www.computop-paygate.com/sofort.aspx');
        $this->setConfig(
            'COMPUTOP:CRIF_GREEN_AVAILABLE_PAYMENT_METHODS',
            [
                ComputopConfig::PAYMENT_METHOD_SOFORT,
                ComputopConfig::PAYMENT_METHOD_PAYDIREKT,
                ComputopConfig::PAYMENT_METHOD_IDEAL,
                ComputopConfig::PAYMENT_METHOD_CREDIT_CARD,
                ComputopConfig::PAYMENT_METHOD_PAY_NOW,
                ComputopConfig::PAYMENT_METHOD_PAY_PAL,
                ComputopConfig::PAYMENT_METHOD_DIRECT_DEBIT,
                ComputopConfig::PAYMENT_METHOD_EASY_CREDIT,
                ComputopConfig::PAYMENT_METHOD_PAYU_CEE_SINGLE,
            ]
        );
        $this->setConfig(
            'COMPUTOP:CRIF_YELLOW_AVAILABLE_PAYMENT_METHODS',
            [
                ComputopConfig::PAYMENT_METHOD_CREDIT_CARD,
                ComputopConfig::PAYMENT_METHOD_PAY_NOW,
                ComputopConfig::PAYMENT_METHOD_PAY_PAL,
            ]
        );
        $this->setConfig(
            'COMPUTOP:CRIF_RED_AVAILABLE_PAYMENT_METHODS',
            [
                ComputopConfig::PAYMENT_METHOD_CREDIT_CARD,
            ]
        );
        $this->setConfig('COMPUTOP:CRIF_ENABLED', true);

        $this->setConfig(
            'ACTIVE_PROCESSES',
            [
                static::TEST_STATE_MACHINE_NAME,
            ]
        );
    }

    /**
     * @return void
     */
    public function createPaymentComputop(): void
    {
        $saveOrderTransfer = $this->haveOrder([], static::TEST_STATE_MACHINE_NAME);

        $spyPaymentComputop = (new SpyPaymentComputop())
            ->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setClientIp(static::CLIENT_IP)
            ->setPaymentMethod(static::TEST_PAYMENT_METHOD)
            ->setReference($saveOrderTransfer->getOrderReference())
            ->setTransId(static::TRANS_ID_VALUE)
            ->setPayId(static::PAY_ID_VALUE)
            ->setXId(static::X_ID_VALUE)
            ->setReqId(static::REQ_ID_VALUE);
        $spyPaymentComputop->save();

        $spyPaymentComputopDetails = (new SpyPaymentComputopDetail())
            ->setIdPaymentComputop($spyPaymentComputop->getIdPaymentComputop());
        $spyPaymentComputopDetails->save();

        foreach ($saveOrderTransfer->getOrderItems() as $itemTransfer) {
            $computopOrderItem = (new SpyPaymentComputopOrderItem())
                ->setFkPaymentComputop($spyPaymentComputop->getIdPaymentComputop())
                ->setFkSalesOrderItem($itemTransfer->getIdSalesOrderItem())
                ->setStatus(static::ORDER_ITEM_STATUS);
            $computopOrderItem->save();
        }
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopNotificationTransfer
     */
    public function createComputopNotificationTransfer(): ComputopNotificationTransfer
    {
        return (new ComputopNotificationTransfer())
            ->setTransId(static::TRANS_ID_VALUE)
            ->setPayId(static::PAY_ID_VALUE)
            ->setXId(static::X_ID_VALUE)
            ->setCode(static::NOTIFICATION_CODE)
            ->setStatus(static::NOTIFICATION_STATUS)
            ->setDescription(static::NOTIFICATION_DESCRIPTION)
            ->setType(static::NOTIFICATION_TYPE)
            ->setIsSuccess(true);
    }
}
