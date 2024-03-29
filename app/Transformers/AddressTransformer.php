<?php


namespace App\Transformers;


use App\Models\Address;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract
{
    public function transform(Address $address)
    {
        return [
            'id' => $address->id,
            'name' => $address->name,
            'province' => $address->province,
            'city' => $address->city,
            'county' => $address->county,
//            'city_id' => $address->city_id,
//            'city_name' => city_name($address->city_id),
            'address' => $address->address,
            'phone' => $address->phone,
            'is_default' => $address->is_default,
            'created_at' => $address->created_at,
            'updated_at' => $address->updated_at,
        ];
    }
}
