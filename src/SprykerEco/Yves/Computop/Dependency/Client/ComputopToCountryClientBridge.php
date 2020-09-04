<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Computop\Dependency\Client;

use Generated\Shared\Transfer\CountryCollectionTransfer;

class ComputopToCountryClientBridge implements ComputopToCountryClientInterface
{
    /**
     * @var \Spryker\Client\Country\CountryClientInterface
     */
    protected $countryClient;

    /**
     * @param \Spryker\Client\Country\CountryClientInterface $countryClient
     */
    public function __construct($countryClient)
    {
        $this->countryClient = $countryClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CountryCollectionTransfer $countryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function findCountriesByIso2Codes(CountryCollectionTransfer $countryCollectionTransfer): CountryCollectionTransfer
    {
        return $this->countryClient->findCountriesByIso2Codes($countryCollectionTransfer);
    }
}
