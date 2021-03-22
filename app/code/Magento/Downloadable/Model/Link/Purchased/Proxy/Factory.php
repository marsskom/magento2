<?php
/**
 * Class Factory
 *
 * @category  Magento
 * @package   Magento\Downloadable
 * @author    Andrii Prakapas <marsskom@gmail.com>
 * @copyright 2021
 */

namespace Magento\Downloadable\Model\Link\Purchased\Proxy;

use Magento\Downloadable\Model\Link\Purchased\Proxy;
use Magento\Framework\ObjectManagerInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;

/**
 * Purchased proxies factory
 */
class Factory
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * Create purchased proxy class
     *
     * @param Order|null $order
     * @param Item|null  $orderItem
     *
     * @return Proxy
     */
    public function create(?Order $order = null, ?Item $orderItem = null): Proxy
    {
        return $this->_objectManager->create(Proxy::class, [
            'order'     => $order,
            'orderItem' => $orderItem,
        ]);
    }
}
