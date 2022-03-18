<?php
// phpcs:ignoreFile

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface ComputopFacadeInterface
{
    /**
     * Specification:
     * - Requires `QuoteTransfer.payment.paymentMethod` to be set for Computop payment.
     * - Saves order payment method data according to quote and checkout response transfer data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer);

    /**
     * Specification:
     * - Requires `QuoteTransfer.totals` to be set when requested method is iDeal, Paydirekt, PayUCeeSingle or Sofort.
     * - Requires `ComputopPaymentTransfer.merchantId` to be set when requested method is iDeal, Paydirekt, PayUCeeSingle or Sofort.
     * - Saves Response header to DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $header
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer
     */
    public function logResponseHeader(ComputopApiResponseHeaderTransfer $header, $method);

    /**
     * Specification:
     * - Requires `CheckoutResponseTransfer.saveOrder` to be set.
     * - Requires `QuoteTransfer.totals` to be set.
     * - Executes post save order hook.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function postSaveHookExecute(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Requires `OrderTransfer.totals` to be set.
     * - Handle Authorize OMS command, make request, save response.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|array
     */
    public function authorizeCommandHandle(array $orderItems, OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Requires `OrderTransfer.totals` to be set.
     * - Handle Cancel OMS command, make request, save response.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function cancelCommandHandle(array $orderItems, OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Requires `OrderTransfer.totals.grandTotal` while first capture to be set.
     * - Requires `OrderTransfer.totals.subtotal` while further captures to be set.
     * - Requires `OrderTransfer.totals.discountTotal` while further captures  to be set.
     * - Handle Capture OMS command, make request, save response.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|array
     */
    public function captureCommandHandle(array $orderItems, OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Requires `OrderTransfer.totals` to be set.
     * - Handle Refund OMS command, make request, save response.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function refundCommandHandle(array $orderItems, OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `QuoteTransfer.payment.computopSofort.sofortInitResponse.header.transId` to be set.
     * - Saves Computop Sofort payment details from response to persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveSofortInitResponse(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Requires `QuoteTransfer.payment.computopIdeal.idealInitResponse.header.transId` to be set.
     * - Saves Computop IDeal payment details from response to persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveIdealInitResponse(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Requires `QuoteTransfer.payment.computopCreditCard.creditCardInitResponse.header.transId` to be set.
     * - Saves Computop CreditCard payment details from response to persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveCreditCardInitResponse(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Requires `QuoteTransfer.payment.computopPayNow.payNowInitResponse.header.transId` to be set.
     * - Saves Computop PayNow payment details from response to persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayNowInitResponse(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Requires `QuoteTransfer.payment.computopPayPal.payPalInitResponse.header.transId` to be set.
     * - Saves Computop PayPal payment details from response to persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayPalInitResponse(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Requires `QuoteTransfer.payment.computopPayPalExpress.payPalExpressInitResponse.header.transId` to be set.
     * - Saves PayPal Express Init Response to DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayPalExpressInitResponse(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Requires `QuoteTransfer.payment.computopPayPalExpress.payPalExpressCompleteResponse.header.transId` to be set.
     * - Saves PayPal Express Complete Response and changes item's OMS status.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayPalExpressCompleteResponse(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Requires `QuoteTransfer.payment.computopDirectDebit.directDebitInitResponse.header.transId` to be set.
     * - Saves Computop DirectDebit payment details from response to persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveDirectDebitInitResponse(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Requires `QuoteTransfer.payment.computopEasyCredit.easyCreditInitResponse.header.transId` to be set.
     * - Saves Computop EasyCredit payment details from response to persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveEasyCreditInitResponse(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Requires `QuoteTransfer.payment.computopPaydirekt.paydirektInitResponse.header.transId` to be set.
     * - Saves Computop Paydirekt payment details from response to persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePaydirektInitResponse(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Requires `QuoteTransfer.payment.computopPayuCeeSingle.payuCeeSingleInitResponse.header.transId` to be set on success.
     * - Saves PayU CEE Single init response to the database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayuCeeSingleInitResponse(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Requires `QuoteTransfer.payment.computopEasyCredit.header` to be set.
     * - Makes Easy Credit Status API call
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function easyCreditStatusApiCall(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Requires `OrderTransfer.totals` to be set.
     * - Handle Authorize OMS command, make request, save response.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|array
     */
    public function easyCreditAuthorizeCommandHandle(array $orderItems, OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `QuoteTransfer.payment.paymentSelection` to be set.
     * - Requires `QuoteTransfer.payment.transId` to be set.
     * - Checks if init call to Computop already performed
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function isComputopPaymentExist(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Perform CRIF risk check API call
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function performCrifApiCall(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Requires `QuoteTransfer.computopCrif.result` to be set.
     * - Requires `PaymentMethodTransfer.methodName` to be set.
     * - Filters available payment methods by gift card black list
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Requires `QuoteTransfer.currency.code` to be set.
     * - Filters available payment methods by currency.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethodsByCurrency(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer): PaymentMethodsTransfer;

    /**
     * Specification:
     * - Requires `ComputopNotificationTransfer.transId` to be set.
     * - Saves push notification into DB.
     * - Updates related computop order items with payment confirmation status.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopNotificationTransfer
     */
    public function processNotification(
        ComputopNotificationTransfer $computopNotificationTransfer
    ): ComputopNotificationTransfer;
}
