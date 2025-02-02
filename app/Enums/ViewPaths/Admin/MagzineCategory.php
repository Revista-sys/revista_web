<?php

namespace App\Enums\ViewPaths\Admin;

enum MagzineCategory
{
    const LIST = [
        URI => 'magzineview',
        VIEW => 'admin-views.magzinecategory.magzineview'
    ];
    const ADD = [
        URI => 'add-new',
        VIEW => 'admin-views.magzine.add-new'
    ];
    const UPDATE = [
        URI => 'update/{id}',
        VIEW => 'admin-views.magzinecategory.magzinecategory-edit'
    ];
    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];
    const STATUS = [
        URI => 'status',
        VIEW => ''
    ];
    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];

}
