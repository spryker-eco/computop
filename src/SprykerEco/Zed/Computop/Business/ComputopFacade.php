<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerEco\Zed\Computop\Business\ComputopBusinessFactory getFactory()
 */
class ComputopFacade extends AbstractFacade implements ComputopFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this
            ->getFactory()
            ->createOrderSaver()
            ->saveOrderPayment($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function logResponseHeader(ComputopResponseHeaderTransfer $header, $method)
    {
        $this->getFactory()->createComputopResponseLogger()->log($header, $method);

        return $header;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     * //todo:add tests
     */
    public function postSaveHookExecute(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this
            ->getFactory()
            ->createPostSaveHook()
            ->execute($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function authorizeCommandHandle(array $orderItems, OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createAuthorizeCommandHandler()
            ->handle($orderItems, $orderTransfer);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function cancelCommandHandle(array $orderItems, OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createCancelCommandHandler()
            ->handle($orderItems, $orderTransfer);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function captureCommandHandle(array $orderItems, OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createCaptureCommandHandler()
            ->handle($orderItems, $orderTransfer);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function refundCommandHandle(array $orderItems, OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createRefundCommandHandler()
            ->handle($orderItems, $orderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveSofortInitResponse(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createSofortResponseSaver()->handle(
            $quoteTransfer
        );

        return $quoteTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveIdealInitResponse(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createIdealResponseSaver()->handle(
            $quoteTransfer
        );

        return $quoteTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePaydirektInitResponse(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createPaydirektResponseSaver()->handle(
            $quoteTransfer
        );

        return $quoteTransfer;
    }
}
