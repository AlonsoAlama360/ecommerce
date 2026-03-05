<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\ViewServiceProvider::class,
    App\Infrastructure\User\Providers\UserServiceProvider::class,
    App\Infrastructure\Product\Providers\ProductServiceProvider::class,
    App\Infrastructure\Category\Providers\CategoryServiceProvider::class,
    App\Infrastructure\Supplier\Providers\SupplierServiceProvider::class,
    App\Infrastructure\Review\Providers\ReviewServiceProvider::class,
    App\Infrastructure\Order\Providers\OrderServiceProvider::class,
    App\Infrastructure\Purchase\Providers\PurchaseServiceProvider::class,
    App\Infrastructure\Kardex\Providers\KardexServiceProvider::class,
    App\Infrastructure\Wishlist\Providers\WishlistServiceProvider::class,
    App\Infrastructure\Complaint\Providers\ComplaintServiceProvider::class,
    App\Infrastructure\Contact\Providers\ContactServiceProvider::class,
    App\Infrastructure\Subscriber\Providers\SubscriberServiceProvider::class,
    App\Infrastructure\Catalog\Providers\CatalogServiceProvider::class,
    App\Infrastructure\Role\Providers\RoleServiceProvider::class,
    App\Infrastructure\Setting\Providers\SettingServiceProvider::class,
];
