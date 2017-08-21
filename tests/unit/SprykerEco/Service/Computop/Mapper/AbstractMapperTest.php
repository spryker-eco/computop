<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Service\Computop\Mapper;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Service\Computop\Model\Mapper\Computop;

abstract class AbstractMapperTest extends Test
{

    const PAY_ID_VALUE = 'PAY_ID_VALUE';
    const TRANS_ID_VALUE = 'TRANS_ID_VALUE';
    const MERCHANT_ID_VALUE = 'MERCHANT_ID_VALUE';
    const AMOUNT_VALUE = 1;
    const CURRENCY_VALUE = 'USD';

    const STATUS_VALUE = 'OK';
    const CODE_VALUE = '000000';

    const EXPECTED_MAC = 'PAY_ID_VALUE*TRANS_ID_VALUE*MERCHANT_ID_VALUE*1*USD';
    const EXPECTED_MAC_RESPONSE = 'PAY_ID_VALUE*TRANS_ID_VALUE*MERCHANT_ID_VALUE*OK*000000';
    const EXPECTED_PLAINTEXT = 'test_key=test_value';

    /**
     * @return \SprykerEco\Service\Computop\Model\Mapper\Computop
     */
    protected function createMapper()
    {
        return new Computop();
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function createComputopPaymentTransfer()
    {
        $cardPaymentTransfer = new ComputopCreditCardPaymentTransfer();

        $cardPaymentTransfer
            ->setPayId(self::PAY_ID_VALUE)
            ->setTransId(self::TRANS_ID_VALUE)
            ->setMerchantId(self::MERCHANT_ID_VALUE)
            ->setAmount(self::AMOUNT_VALUE)
            ->setCurrency(self::CURRENCY_VALUE);

        return $cardPaymentTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    protected function createComputopResponseHeaderTransfer()
    {
        $compuropHeaderTransfer = new ComputopResponseHeaderTransfer();

        $compuropHeaderTransfer
            ->setPayId(self::PAY_ID_VALUE)
            ->setTransId(self::TRANS_ID_VALUE)
            ->setMId(self::MERCHANT_ID_VALUE)
            ->setStatus(self::STATUS_VALUE)
            ->setCode(self::CODE_VALUE);

        return $compuropHeaderTransfer;
    }

}
