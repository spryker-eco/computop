<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Config\Config;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapper;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface;

class CreditCardMapper extends AbstractMapper implements CreditCardMapperInterface
{

    /**
     * @var \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface
     */
    protected $computopService;

    /**
     * CreditCardMapper constructor.
     *
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface $computopService
     */
    public function __construct(ComputopToComputopServiceInterface $computopService)
    {
        $this->computopService = $computopService;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return ComputopConstants::PAYMENT_METHOD_CREDIT_CARD;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function buildRequest(OrderTransfer $orderTransfer)
    {
        $computopCreditCardPaymentTransfer = $orderTransfer->getComputopCreditCard();

        $computopCreditCardPaymentTransfer->setMac(
            $this->computopService->computopMacHashHmacValue($computopCreditCardPaymentTransfer)
        );

        $data = $this->getDataAttribute($computopCreditCardPaymentTransfer);
        $len = $this->getLen($computopCreditCardPaymentTransfer);

        $requestData = [
                'MerchantID' => $orderTransfer->getComputopCreditCard()->getMerchantId(),
                'Data' => $data,
                'Len' => $len,
            ];

        return $requestData;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     *
     * @return bool|string
     */
    protected function getDataAttribute(ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer)
    {
        $plaintext = $this->computopService->computopAuthorizationDataEncryptedValue($computopCreditCardPaymentTransfer);
        $len = $this->getLen($computopCreditCardPaymentTransfer);
        $password = Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD);

        return $this->computopService->blowfishEncryptedValue($plaintext, $len, $password);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     *
     * @return int
     */
    protected function getLen(ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer)
    {
        $responseArray = [
            'Data' => 'A23501461AA31D2A7DE3ABDBAB085C69369333A28FB75BB52BB2AEBACD1757B834FEF16E8766EF3BA4996A144A34B922A204D24717E46C37811E19511997B96D7601F807CD57A11171922F5D33B696409B81744C558C11BB60BB7D5B21480B692576055F464E83C62D7808994DF4591673B87E7726C69349D5658C52FE7133C016ED4C1129F4D1F499EEE4BA68D85935FA765B84483ED5E4723DF55F56ABD3075C99D1AD05287DFA16CF9C839691CC843E5151469271DF21',
            'Len' => 178,
        ];
        dump($this->getDecryptedArray($responseArray));
        die;

        return strlen($this->computopService->computopAuthorizationDataEncryptedValue($computopCreditCardPaymentTransfer));
    }

    /**
     * @param array $responseArray
     *
     * @return array
     */
    protected function getDecryptedArray($responseArray)
    {
        $responseDecryptedString = $this->computopService->blowfishDecryptedValue(
            $responseArray['Data'],
            $responseArray['Len'],
            Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD)
        );
        $responseDecrypted = explode('&', $responseDecryptedString);
        foreach ($responseDecrypted as $value) {
            $data = explode('=', $value);
            $responseArray[array_shift($data)] = array_shift($data);
        }

        return $responseArray;
    }

}
