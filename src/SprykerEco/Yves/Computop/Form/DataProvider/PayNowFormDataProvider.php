<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Yves\Computop\Form\PayNowSubForm;

class PayNowFormDataProvider extends AbstractFormDataProvider
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $quoteTransfer->setPayment($paymentTransfer);
        }

        if (!$this->isValidPayment($quoteTransfer)) {
            $paymentTransfer = $quoteTransfer->getPayment();


            $computopTransfer = $this->mapper->createComputopPaymentTransfer($quoteTransfer);
            $paymentTransfer->setComputopPayNow($computopTransfer);
            $quoteTransfer->setPayment($paymentTransfer);
            $this->quoteClient->setQuote($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        return [
            PayNowSubForm::OPTION_CREDIT_CARD_BRAND_CHOICES => $this->getBrandChoices(),
            PayNowSubForm::OPTION_CREDIT_CARD_EXPIRES_MONTH_CHOICES => $this->getMonthChoices(),
            PayNowSubForm::OPTION_CREDIT_CARD_EXPIRES_YEAR_CHOICES => $this->getYearChoices(),
        ];
    }

    /**
     * @return array
     */
    protected function getBrandChoices()
    {
        return [
            'VISA' => 'Visa',
            'MasterCard' => 'Master Card',
            'AMEX' => 'American Express',
            'DINERS' => 'Diners Club',
            'JCB' => 'JCB',
            'CBN' => 'CBN',
            'SWITCH' => 'Switch',
            'SOLO' => 'Solo',
        ];
    }

    /**
     * @return array
     */
    protected function getMonthChoices()
    {
        return [
            '01' => '01',
            '02' => '02',
            '03' => '03',
            '04' => '04',
            '05' => '05',
            '06' => '06',
            '07' => '07',
            '08' => '08',
            '09' => '09',
            '10' => '10',
            '11' => '11',
            '12' => '12',
        ];
    }

    /**
     * @return array
     */
    protected function getYearChoices()
    {
        $currentYear = date('Y');

        return [
            $currentYear => $currentYear,
            ++$currentYear => $currentYear,
            ++$currentYear => $currentYear,
            ++$currentYear => $currentYear,
            ++$currentYear => $currentYear,
            ++$currentYear => $currentYear,
            ++$currentYear => $currentYear,
            ++$currentYear => $currentYear,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function getComputopPayment(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getPayment()->getComputopPayNow();
    }
}
