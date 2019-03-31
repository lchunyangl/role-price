<?php

Route::group([
    'middleware' => 'web',
    'namespace' => 'Chunyang\RolePrice\Controllers'
], function () {
    Route::resource('hd_role', 'HdRoleController');
    Route::get('goods_role_price/create/{goods_id}', 'GoodsRolePriceController@create')->name('goods_role_price.create');
    Route::post('goods_role_price/store/{goods_id}', 'GoodsRolePriceController@store')->name('goods_role_price.store');
    Route::resource('goods_role_price', 'GoodsRolePriceController', ['except' => ['create', 'store']]);
});