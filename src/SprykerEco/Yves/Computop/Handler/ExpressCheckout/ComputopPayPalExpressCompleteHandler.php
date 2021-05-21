<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\ExpressCheckout;

use Generated\Shared\Transfer\ComputopPayPalExpressCompleteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Client\Computop\ComputopClientInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\ComputopConfigInterface;
use SprykerEco\Yves\Computop\ComputopFactory;
use SprykerEco\Yves\Computop\Dependency\External\ComputopToGuzzleHttpClientInterface;
use SprykerEco\Yves\Computop\Mapper\Complete\PayPalExpressCompeteMapperInterface;
use Symfony\Component\HttpFoundation\Request;

class ComputopPayPalExpressCompleteHandler implements ComputopPayPalExpressCompleteHandlerInterface
{
    /**
     * @var \SprykerEco\Yves\Computop\ComputopConfigInterface
     */
    private $computopConfig;

    /**
     * @var \SprykerEco\Yves\Computop\Dependency\External\ComputopToGuzzleHttpClientInterface
     */
    protected $httpClient;

    /**
     * @var \SprykerEco\Yves\Computop\Mapper\Complete\PayPalExpressCompeteMapperInterface
     */
    protected $payPalExpressCompleteMapper;

    /**
     * @var \SprykerEco\Client\Computop\ComputopClientInterface
     */
    protected $computopClient;

    /**
     * @param \SprykerEco\Yves\Computop\ComputopConfigInterface $computopConfig
     * @param \SprykerEco\Yves\Computop\Dependency\External\ComputopToGuzzleHttpClientInterface $httpClient
     * @param \SprykerEco\Client\Computop\ComputopClientInterface $computopClient
     * @param \SprykerEco\Yves\Computop\Mapper\Complete\PayPalExpressCompeteMapperInterface $payPalExpressCompleteMapper
     */
    public function __construct(
        ComputopConfigInterface $computopConfig,
        ComputopToGuzzleHttpClientInterface $httpClient,
        ComputopClientInterface $computopClient,
        PayPalExpressCompeteMapperInterface $payPalExpressCompleteMapper
    ) {
        $this->computopConfig = $computopConfig;
        $this->httpClient = $httpClient;
        $this->payPalExpressCompleteMapper = $payPalExpressCompleteMapper;
        $this->computopClient = $computopClient;
    }

    /**
     * @inheritDoc
     */
    public function handle(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $computopPayPalExpressCompleteTransfer =
            $this->payPalExpressCompleteMapper->createComputopPayPalExpressCompleteTransfer($quoteTransfer);

//        $response = $this->httpClient->request(
//            Request::METHOD_POST,
//            $this->computopConfig->getPaypalExpressCompleteActionUrl(),
//            [
//                ComputopApiConfig::MERCHANT_ID => $computopPayPalExpressCompleteTransfer->getMerchantId(),
//                ComputopApiConfig::DATA => $computopPayPalExpressCompleteTransfer->getData(),
//                ComputopApiConfig::LENGTH => $computopPayPalExpressCompleteTransfer->getLen(),
//            ]
//        );

//        $responseBody = $response->getBody()->getContents();

        $responseBody = 'Len=196&Data=A23501461AA31D2A7DE3ABDBAB085C69369333A28FB75BB5079E07AB5FBD2A60F9454A9854728B3AC1287FE1180781233AD3D49BDFC8C5A6622991FD3247ADD1D834C7D5F1C92FB7824F434E797D326E038D7502EF746DD2993A3309380DD89EC1C36306121044873092B8E229FB7968E56E3C3F1B345EAA331E4C91AD1BAA3C6239BBC32AA97E8B4C6DB282C32F06E9577652B091E5D1EF3D39B9220916E6FF5757AC14FEBDE6A59982DA93EB15471CDACD91B8EA2777DA73F1C5E65CBBDAB6484F88E14E9D411A';

        $responseDataArray = [];
        parse_str($responseBody, $responseDataArray);

        $computopPayPalExpressCompleteTransfer =
            $this->payPalExpressCompleteMapper
                ->mapComputopPayPalExpressCompleteResponse($responseDataArray, $computopPayPalExpressCompleteTransfer);

        $quoteTransfer->getPayment()
            ->getComputopPayPalExpress()
            ->setPayPalExpressCompleteResponse($computopPayPalExpressCompleteTransfer->getPayPalExpressCompleteResponse());

        $quoteTransfer = $this->computopClient->savePayPalExpressInitResponse($quoteTransfer);

        return $this->computopClient->savePayPalExpressCompleteResponse($quoteTransfer);
    }
}
