<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class PayuCeeSingleMapper extends AbstractMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer): ComputopPayuCeeSinglePaymentTransfer
    {
        /** @var \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);

        $computopPaymentTransfer->setMac(
            $this->computopApiService->generateEncryptedMac($this->createRequestTransfer($computopPaymentTransfer))
        );

        $computopPaymentTransfer->setOrderDesc(
            $this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        if ($quoteTransfer->getItems()->count()) {
            $computopPaymentTransfer->setArticleList($this->getArticleList($quoteTransfer->getItems()));
        }

        if ($quoteTransfer->getCustomer()) {
            $this->setCustomerData($computopPaymentTransfer, $quoteTransfer->getCustomer());
        }

        $computopPaymentTransfer->setCapture(
            $this->getCaptureType(ComputopSharedConfig::PAYMENT_METHOD_PAYU_CEE_SINGLE)
        );

        $computopPaymentTransfer->setLanguage(mb_strtoupper($this->store->getCurrentLanguage()));

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer): ComputopPayuCeeSinglePaymentTransfer
    {
        $computopPaymentTransfer = new ComputopPayuCeeSinglePaymentTransfer();

        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setUrlSuccess(
            $this->router->generate(ComputopRouteProviderPlugin::PAYU_CEE_SINGLE_SUCCESS, [], Router::ABSOLUTE_URL)
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return string
     */
    protected function getArticleList($items): string
    {
        $out = [];
        foreach ($items as $item) {
            $out[] = implode(',', [
                str_replace([',', '+'], ' ', $item->getName()),
                $item->getUnitPrice(),
                $item->getQuantity(),
            ]);
        }

        return implode('+', $out);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return void
     */
    protected function setCustomerData(ComputopPayuCeeSinglePaymentTransfer $paymentTransfer, CustomerTransfer $customer): void
    {
        $paymentTransfer->setFirstName($customer->getFirstName());
        $paymentTransfer->setLastName($customer->getLastName());
        $paymentTransfer->setEmail($customer->getEmail());
        $paymentTransfer->setPhone($customer->getPhone());
    }
}
