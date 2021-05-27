<?php
//phpcs:ignoreFile

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Dependency;

class ComputopToStoreBridge implements ComputopToStoreInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct($store)
    {
        $this->store = $store;
    }

    /**
     * @return string
     */
    public function getCurrencyIsoCode()
    {
        return $this->store->getCurrencyIsoCode();
    }

    /**
     * @return string
     */
    public function getCurrentLanguage()
    {
        return $this->store->getCurrentLanguage();
    }
}
