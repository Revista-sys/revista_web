<?php

namespace App\Enums\ViewPaths\Admin;

enum PrimeMember
{
    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.primemember.list'
    ];

    const VIEW = [
        URI => 'view',
        VIEW => 'admin-views.primemember.primemember-view'
    ];

    const UPDATE = [
        URI => 'status-update',
        VIEW => 'admin-views.category.category-edit'
    ];

    const DELETE = [
        URI => 'delete/{id}',
        VIEW => ''
    ];

    const SUBSCRIBER_LIST = [
        URI => 'subscriber-list',
        VIEW => 'admin-views.primemember.subscriber-list'
    ];

    const SUBSCRIBER_EXPORT = [
        URI => 'subscriber-list/export',
        VIEW => ''
    ];

    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];

    const SEARCH = [
        URI => 'primemember-list-search',
        VIEW => ''
    ];

    const SETTINGS = [
        URI => 'primemember-settings',
        VIEW => 'admin-views.primemember.primemember-settings'
    ];

    const LOYALTY_REPORT = [
        URI => 'report',
        VIEW => 'admin-views.primemember.loyalty.report'
    ];

    const LOYALTY_EXPORT = [
        URI => 'export',
        VIEW => ''
    ];
    const ADD = [
        URI => 'add',
        VIEW => ''
    ];

}
