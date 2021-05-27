<?php
//phpcs:ignoreFile

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Dependency;

interface ComputopToStoreInterface
{
    /**
     * @return string
     */
    public function getCurrencyIsoCode();

    /**
     * @return string
     */
    public function getCurrentLanguage();
}
