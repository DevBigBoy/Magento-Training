<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Training\ExtensionAttributesExample\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class OrderPoNumber
 * Resource model for Order PO Number
 */
class OrderPoNumber extends AbstractDb
{
    /**
     * Table name
     */
    const string MAIN_TABLE = 'acme_ponumber_sales_order';

    /**
     * Primary Key
     */
    const string ID_FIELD_NAME = 'id';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }

    public function loadByOrderId(
        \Training\ExtensionAttributesExample\Model\OrderPoNumber $object,
        int $orderId
    ) {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable())
            ->where('order_id = ?', $orderId);

        $data = $connection->fetchRow($select);
        if ($data) {
            $object->setData($data);
        }

        $this->unserializeFields($object);
        $this->_afterLoad($object);

        return $this;
    }

    public function deleteByOrderId(int $orderId): int
    {
        $connection = $this->getConnection();
        return $connection->delete(
            $this->getMainTable(),
            ['order_id = ?' => $orderId]
        );
    }
}
