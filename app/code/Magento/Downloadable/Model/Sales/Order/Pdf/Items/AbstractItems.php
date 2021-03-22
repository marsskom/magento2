<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Downloadable\Model\Sales\Order\Pdf\Items;

use Magento\Framework\DataObject;
use Magento\Sales\Model\Order;

/**
 * Order Downloadable Pdf Items renderer
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class AbstractItems extends \Magento\Sales\Model\Order\Pdf\Items\AbstractItems
{
    /**
     * Downloadable links purchased model
     *
     * @var \Magento\Downloadable\Model\Link\Purchased\Proxy
     */
    protected $_purchasedLinks = null;
    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param \Magento\Framework\Model\Context                             $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Tax\Helper\Data                                     $taxData
     * @param \Magento\Framework\Filesystem                                $filesystem
     * @param \Magento\Framework\Filter\FilterManager                      $filterManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface           $scopeConfig
     * @param \Magento\Downloadable\Model\Link\Purchased\Proxy\Factory     $purchasedProxyFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection
     * @param array                                                        $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Tax\Helper\Data $taxData,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filter\FilterManager $filterManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Downloadable\Model\Link\Purchased\Proxy\Factory $purchasedProxyFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_purchasedLinks = $purchasedProxyFactory->create();

        parent::__construct(
            $context,
            $registry,
            $taxData,
            $filesystem,
            $filterManager,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * @param Order $order
     *
     * @return AbstractItems
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setOrder(Order $order)
    {
        $this->_purchasedLinks->setOrder($order);

        return parent::setOrder($order);
    }

    /**
     * @param DataObject $item
     *
     * @return AbstractItems
     */
    public function setItem(DataObject $item)
    {
        $this->_purchasedLinks->setOrderItem($item);

        return parent::setItem($item);
    }

    /**
     * Return Purchased link for order item
     *
     * @return \Magento\Downloadable\Model\Link\Purchased
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLinks()
    {
        return $this->_purchasedLinks->getPurchased();
    }

    /**
     * Return Links Section Title for order item
     *
     * @return string
     */
    public function getLinksTitle()
    {
        $linksTitle = $this->_purchasedLinks->getLinkSectionTitle() ?? null;
        if ($linksTitle) {
            return $linksTitle;
        }

        return $this->_scopeConfig->getValue(
            \Magento\Downloadable\Model\Link::XML_PATH_LINKS_TITLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
