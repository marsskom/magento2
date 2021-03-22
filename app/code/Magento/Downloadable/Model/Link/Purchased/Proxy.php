<?php
/**
 * Class PurchasedProxy
 *
 * @category  Magento
 * @package   Magento\Downloadable
 * @author    Andrii Prakapas <marsskom@gmail.com>
 * @copyright 2021
 */

namespace Magento\Downloadable\Model\Link\Purchased;

use Magento\Downloadable\Model\Link\Purchased;
use Magento\Downloadable\Model\Link\PurchasedFactory;
use Magento\Downloadable\Model\ResourceModel\Link\Purchased\Item\CollectionFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\ObjectManager\NoninterceptableInterface;
use Magento\Framework\Registry;
use Magento\Sales\Model\Order;

/**
 * Purchased proxy
 */
class Proxy extends Purchased implements NoninterceptableInterface
{
    /**
     * @var Purchased
     */
    private $purchasedLinks;
    /**
     * @var PurchasedFactory
     */
    protected $purchasedFactory;
    /**
     * @var CollectionFactory
     */
    protected $itemsCollectionFactory;
    /**
     * @var Order
     */
    protected $order;
    /**
     * @var DataObject
     */
    protected $orderItem;

    /**
     * Proxy constructor.
     *
     * @param Context               $context
     * @param Registry              $registry
     * @param PurchasedFactory      $purchasedFactory
     * @param CollectionFactory     $itemsCollectionFactory
     * @param Order|null            $order
     * @param DataObject|null       $orderItem
     * @param AbstractResource|null $resource
     * @param AbstractDb|null       $resourceCollection
     * @param array                 $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        PurchasedFactory $purchasedFactory,
        CollectionFactory $itemsCollectionFactory,
        Order $order = null,
        DataObject $orderItem = null,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->purchasedFactory = $purchasedFactory;
        $this->itemsCollectionFactory = $itemsCollectionFactory;
        $this->order = $order;
        $this->orderItem = $orderItem;
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    /**
     * @param DataObject $orderItem
     */
    public function setOrderItem(DataObject $orderItem): void
    {
        $this->orderItem = $orderItem;
    }

    /**
     * @return Purchased
     */
    protected function getPurchased(): Purchased
    {
        if (null === $this->purchasedLinks) {
            $this->purchasedLinks = $this->purchasedFactory->create()->load($this->order->getId(), 'order_id');

            $purchasedItems = $this->itemsCollectionFactory->create()->addFieldToFilter(
                'order_item_id',
                $this->orderItem->getId()
            );

            $this->purchasedLinks->setPurchasedItems($purchasedItems);
        }

        return $this->purchasedLinks;
    }

    /**
     * @return string
     */
    public function getLinkSectionTitle()
    {
        return $this->getPurchased()->getLinkSectionTitle();
    }
}
