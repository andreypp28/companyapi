<?php

namespace WA\DataStore\ServiceItem;

use WA\DataStore\FilterableTransformer;

/**
 * Class ServiceItemTransformer.
 */
class ServiceItemTransformer extends FilterableTransformer
{
    /**
     * @param ServiceItem $item
     *
     * @return array
     */
    public function transform(ServiceItem $item)
    {
        return [

            'id'            => (int)$item->id,
            'serviceId'     => $item->serviceId,
            'category'          => $item->category,
            'description'   => $item->description,
            'value'         => $item->value,
            'unit'          => $item->unit,
            'cost'          => $item->cost,
            'domain'        => $item->domain,
            'created_at'    => $item->created_at,
            'updated_at'    => $item->updated_at,
        ];
    }

    public function includeOrders(ServiceItem $serviceitem)
    {
        $this->criteria = $this->getRequestCriteria();
        $orders = $this->applyCriteria($serviceitem->orders(), null, true, [
            'orders' => 'orders'
        ]);
        return new ResourceCollection ($orders->get(), new OrderTransformer(), 'orders');
    }
}
