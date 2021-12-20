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
     * - Requires QuoteTransfer::payment to be set.
     * - Requires PaymentTransfer::computopSofort to be set.
     * - Requires ComputopSofortPaymentTransfer::sofortInitResponse to be set.
     * - Saves Sofort Response to DB.
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
     * - Requires QuoteTransfer::payment to be set.
     * - Requires PaymentTransfer::computopIdeal to be set.
     * - Requires ComputopIdealPaymentTransfer::idealInitResponse to be set.
     * - Saves IDeal Response to DB.
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
     * - Requires QuoteTransfer::payment to be set.
     * - Requires PaymentTransfer::computopCreditCard to be set.
     * - Requires ComputopCreditCardPaymentTransfer::creditCardInitResponse to be set.
     * - Saves CreditCard Response to DB.
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
     * - Requires QuoteTransfer::payment to be set.
     * - Requires PaymentTransfer::computopPayNow to be set.
     * - Requires ComputopPayNowPaymentTransfer::payNowInitResponse to be set.
     * - Saves PayNow Response to DB.
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
     * - Requires QuoteTransfer::payment to be set.
     * - Requires PaymentTransfer::computopPayPal to be set.
     * - Requires ComputopPayPalPaymentTransfer::payPalInitResponse to be set.
     * - Saves PayPal Response to DB.
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
     * - Requires QuoteTransfer::payment to be set.
     * - Requires PaymentTransfer::computopPayPalExpress to be set.
     * - Requires ComputopPayPalExpressTransfer::payPalExpressInitResponse to be set.
     * - Requires ComputopPayuCeeSingleInitResponseTransfer::header to be set.
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
     * - Requires QuoteTransfer::payment to be set.
     * - Requires PaymentTransfer::computopPayPalExpress to be set.
     * - Requires ComputopPayPalExpressTransfer::payPalExpressCompleteResponse to be set.
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
     * - Requires QuoteTransfer::payment to be set.
     * - Requires PaymentTransfer::computopDirectDebit to be set.
     * - Requires ComputopDirectDebitPaymentTransfer::directDebitInitResponse to be set.
     * - Saves DirectDebit Response to DB.
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
     * - Requires QuoteTransfer::payment to be set.
     * - Requires PaymentTransfer::computopEasyCredit to be set.
     * - Requires ComputopEasyCreditPaymentTransfer::easyCreditInitResponse to be set.
     * - Saves EasyCredit Response to DB.
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
     * - Requires QuoteTransfer::payment to be set.
     * - Requires PaymentTransfer::computopPaydirekt to be set.
     * - Requires ComputopPaydirektPaymentTransfer::computopPaydirekt to be set.
     * - Saves Paydirekt Response to DB.
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
     * - Requires QuoteTransfer::payment to be set.
     * - Requires PaymentTransfer::computopPayuCeeSingle to be set.
     * - Requires ComputopPayuCeeSingleInitResponseTransfer::payuCeeSingleInitResponse to be set.
     * - Requires ComputopPayuCeeSingleInitResponseTransfer::header to be set.
     * - Requires ComputopApiResponseHeaderTransfer::transId to be set.
     * - Requires ComputopApiResponseHeaderTransfer::payId to be set.
     * - Requires ComputopApiResponseHeaderTransfer::xId to be set.
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
