<?php

namespace WA\DataStore\Address;

use WA\DataStore\FilterableTransformer;

/**
 * Class AddressTransformer.
 */
class AddressTransformer extends FilterableTransformer
{
    /**
     * @param Address $address
     *
     * @return array
     */
    public function transform(Address $address)
    {
        return [
            'id'            => (int)$address->id,
            'name'          => $address->name,
            'attn'          => $address->attn,
            'phone'         => $address->phone,
            'address'       => $address->address,
            'city'          => $address->city,
            'state'         => $address->state,
            'country'       => $address->country,
            'postalCode'    => $address->postalCode,
            'created_at'    => $address->created_at,
            'updated_at'    => $address->updated_at,
        ];
    }
}
