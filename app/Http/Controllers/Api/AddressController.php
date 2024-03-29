<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\AddressRequest;
use App\Models\Address;
use App\Transformers\AddressTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends BaseController
{
    /**
     * 我的地址列表
     */
    public function index()
    {
        $address = Address::where('user_id', auth('api')->id())->get();
        return $this->response->collection($address, new AddressTransformer());
    }

    /**
     * 添加地址
     */
    public function store(AddressRequest $request)
    {
        try {
            DB::beginTransaction();

            if ($request->is_default == 1) {
                $this->setDefault();
            }

            $request->offsetSet('user_id', auth('api')->id());
            Address::create($request->all());

            DB::commit();
            return $this->response->created();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 地址详情
     */
    public function show(Address $address)
    {
        // 只能查看自己的
        if ($address->user_id != auth('api')->id()) {
            return $this->response->errorBadRequest('只能查看自己的地址');
        }

        return $this->response->item($address, new AddressTransformer());
    }

    /**
     * 更新地址
     */
    public function update(AddressRequest $request, Address $address)
    {
        try {
            DB::beginTransaction();

            if ($request->is_default == 1) {
                $this->setDefault();
            }

            $address->update($request->all());

            DB::commit();
            return $this->response->noContent();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 添加和更新时的默认设置
     */
    public function setDefault()
    {
        // 先把所有的地址都设置为非默认
        $default_address = Address::where('user_id', auth('api')->id())
            ->where('is_default', 1)
            ->first();

        if (!empty($default_address)) {
            $default_address->is_default = 0;
            $default_address->save();
        }
    }

    /**
     * 删除地址
     */
    public function destroy(Address $address)
    {
        $address->delete();
        return $this->response->noContent();
    }

    /**
     * 是否设置为默认地址
     */
    public function defaultAddress(Address $address)
    {
        if ($address->is_default == 1) {
            return $this->response->errorBadRequest('当前地址已经是默认地址, 不能重复设置');
//            return $this->response->noContent();
        }

        try {
            DB::beginTransaction();
            // 先把所有的地址都设置为非默认
            $this->setDefault();

            // 再设置为当前的这个为默认
            $address->is_default = 1;
            $address->save();

            DB::commit();
            return $this->response->noContent();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
