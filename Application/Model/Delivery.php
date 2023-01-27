<?php

namespace IvobaOxid\DeliveryMustFitAll\Application\Model;

use OxidEsales\Eshop\Core\Registry;

class Delivery extends Delivery_parent
{

    const CONFIG_PARAM = 'ivoba_delivery_must_fit_all_deliveries';

    /**
     * override
     * Checks if delivery fits for current basket
     *
     * @param \OxidEsales\Eshop\Application\Model\Basket $oBasket shop basket
     *
     * @return bool
     */
    public function isForBasket($oBasket)
    {
        // amount for conditional check
        $blHasArticles            = $this->hasArticles();
        $blHasCategories          = $this->hasCategories();
        $blUse                    = true;
        $aggregatedDeliveryAmount = 0;
        $blForBasket              = false;
        $articleMap               = [];

        // category & article check
        if ($blHasCategories || $blHasArticles) {
            $blUse = false;

            $aDeliveryArticles   = $this->getArticles();
            $aDeliveryCategories = $this->getCategories();

            foreach ($oBasket->getContents() as $oContent) {
                //V FS#1954 - load delivery for variants from parent article
                $oArticle            = $oContent->getArticle(false);
                $sProductId          = $oArticle->getProductId();
                $sParentId           = $oArticle->getParentId();
                $articleFitsDelivery = false;

                if ($blHasArticles && (in_array($sProductId, $aDeliveryArticles) || ($sParentId && in_array($sParentId,
                                $aDeliveryArticles)))) {
                    $blUse               = true;
                    $articleFitsDelivery = true;
                    $artAmount           = $this->getDeliveryAmount($oContent);
                    if ($this->isDeliveryRuleFitByArticle($artAmount)) {
                        $blForBasket = true;
                        $this->updateItemCount($oContent);
                        $this->increaseProductCount();
                    }
                    if (!$blForBasket) {
                        $aggregatedDeliveryAmount += $artAmount;
                    }
                } elseif ($blHasCategories) {
                    if (isset(self::$_aProductList[$sProductId])) {
                        $oProduct = self::$_aProductList[$sProductId];
                    } else {
                        $oProduct = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
                        $oProduct->setSkipAssign(true);

                        if (!$oProduct->load($sProductId)) {
                            continue;
                        }

                        $oProduct->setId($sProductId);
                        self::$_aProductList[$sProductId] = $oProduct;
                    }

                    foreach ($aDeliveryCategories as $sCatId) {
                        if ($oProduct->inCategory($sCatId)) {
                            $artAmount = $this->getDeliveryAmount($oContent);
                            $blUse     = true;
                            if ($this->isDeliveryRuleFitByArticle($artAmount)) {
                                $blForBasket = true;
                                $this->updateItemCount($oContent);
                                $this->increaseProductCount();
                            }
                            if (!$blForBasket) {
                                $aggregatedDeliveryAmount += $artAmount;
                            }

                            //HR#5650 product might be in multiple rule categories, counting it once is enough
                            break;
                        }
                    }
                }
                $articleMap[$sProductId] = $articleFitsDelivery;
            }
        } else {
            // regular amounts check
            foreach ($oBasket->getContents() as $oContent) {
                $articleFitsDelivery = false;
                $artAmount           = $this->getDeliveryAmount($oContent);
                if ($this->isDeliveryRuleFitByArticle($artAmount)) {
                    $blForBasket         = true;
                    $articleFitsDelivery = true;
                    $this->updateItemCount($oContent);
                    $this->increaseProductCount();
                }
                if (!$blForBasket) {
                    $aggregatedDeliveryAmount += $artAmount;
                }

                $oArticle                              = $oContent->getArticle(false);
                $articleMap[$oArticle->getProductId()] = $articleFitsDelivery;
            }
        }

        //#M1130: Single article in Basket, checked as free shipping, is not buyable (step 3 no payments found)
        if (!$blForBasket && $blUse && ($this->_checkDeliveryAmount($aggregatedDeliveryAmount) || $this->_blFreeShipping)) {
            $blForBasket = true;
        }

        $deliveries = Registry::getConfig()->getConfigParam(self::CONFIG_PARAM);
        if (in_array($this->oxdelivery__oxtitle->rawValue, $deliveries)) {
            if (in_array(false, $articleMap, true) === true) {
                $blForBasket = false;
            }
        }

        return $blForBasket;
    }
}
