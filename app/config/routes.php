<?php

/*
    Asatru PHP - routes configuration file

    Add here all your needed routes.

    Schema:
        [<url>, <method>, controller_file@controller_method]
    Example:
        [/my/route, get, mycontroller@index]
        [/my/route/with/{param1}/and/{param2}, get, mycontroller@another]
    Explanation:
        Will call index() in app\controller\mycontroller.php if request is 'get'
        Every route with $ prefix is a special route
*/

return [
    /** Index Controller */
    array('/', 'GET', 'index@index'),
    array('/auth', 'GET', 'index@auth'),
    array('/login', 'POST', 'index@login'),
    array('/logout', 'ANY', 'index@logout'),
    array('/password/restore', 'POST', 'index@restore_password'),
    array('/password/reset', 'GET', 'index@view_reset_password'),
    array('/password/reset', 'POST', 'index@reset_password'),

    /** Plants Controller */
    array('/plants/location/{id}', 'GET', 'plants@plants_from_location'),
    array('/plants/location/{id}/water', 'ANY', 'plants@set_plants_watered'),
    array('/plants/details/{id}', 'GET', 'plants@view_plant_details'),
    array('/plants/add', 'POST', 'plants@add_plant'),
    array('/plants/details/edit', 'POST', 'plants@edit_plant_details'),
    array('/plants/details/edit/link', 'POST', 'plants@edit_plant_link'),
    array('/plants/details/edit/photo', 'POST', 'plants@edit_plant_details_photo'),
    array('/plants/details/gallery/add', 'POST', 'plants@add_plant_gallery_photo'),
    array('/plants/details/gallery/photo/remove', 'POST', 'plants@remove_gallery_photo'),
    array('/plants/remove', 'ANY', 'plants@remove_plant'),
    array('/plants/history', 'GET', 'plants@view_history'),
    array('/plants/history/add', 'ANY', 'plants@add_to_history'),
    array('/plants/history/remove', 'ANY', 'plants@remove_from_history'),

    /** User controller Controller */
    array('/profile', 'GET', 'user@view_profile'),
    array('/profile/preferences', 'POST', 'user@edit_preferences'),

    /** Search controller Controller */
    array('/search', 'GET', 'search@view_search'),
    array('/search/perform', 'POST', 'search@perform_search'),

    /** Tasks Controller */
    array('/tasks', 'GET', 'tasks@view_tasks'),
    array('/tasks/create', 'POST', 'tasks@create_task'),
    array('/tasks/edit', 'POST', 'tasks@edit_task'),
    array('/tasks/toggle', 'POST', 'tasks@toggle_task'),

    /** Inventory Controller */
    array('/inventory', 'GET', 'inventory@view_inventory'),
    array('/inventory/add', 'POST', 'inventory@add_inventory_item'),
    array('/inventory/edit', 'POST', 'inventory@edit_inventory_item'),
    array('/inventory/amount/increment', 'ANY', 'inventory@inc_inventory_item'),
    array('/inventory/amount/decrement', 'ANY', 'inventory@dec_inventory_item'),
    array('/inventory/remove', 'ANY', 'inventory@remove_inventory_item'),
    array('/inventory/group/add', 'POST', 'inventory@add_inventory_group_item'),
    array('/inventory/group/edit', 'POST', 'inventory@edit_inventory_group_item'),
    array('/inventory/group/remove', 'ANY', 'inventory@remove_inventory_group_item'),

    /** Chat Controller */
    array('/chat', 'GET', 'chat@view_chat'),
    array('/chat/add', 'POST', 'chat@add_chat_message'),
    array('/chat/query', 'GET', 'chat@query_chat_messages'),
    array('/chat/typing', 'ANY', 'chat@get_chat_typing_status'),
    array('/chat/typing/update', 'ANY', 'chat@update_chat_typing'),
    array('/chat/user/online', 'ANY', 'chat@get_online_users'),

    /** Admin Controller */
    array('/admin', 'GET', 'admin@index'),
    array('/admin/environment/save', 'POST', 'admin@save_environment'),
    array('/admin/user/create', 'POST', 'admin@create_user'),
    array('/admin/user/update', 'POST', 'admin@update_user'),
    array('/admin/user/remove', 'ANY', 'admin@remove_user'),
    array('/admin/location/add', 'POST', 'admin@add_location'),
    array('/admin/location/update', 'POST', 'admin@update_location'),
    array('/admin/location/remove', 'ANY', 'admin@remove_location'),
    array('/admin/media/logo', 'POST', 'admin@upload_media_logo'),
    array('/admin/media/background', 'POST', 'admin@upload_media_background'),
    array('/admin/media/overlay/alpha', 'POST', 'admin@save_overlay_alpha'),
    array('/admin/mail/save', 'POST', 'admin@save_mail_settings'),

    /** Cronjob Controller */
    array('/cronjob/tasks/overdue', 'GET', 'cronjobs@overdue_tasks'),
    array('/cronjob/tasks/tomorrow', 'GET', 'cronjobs@tomorrow_tasks'),

    /** Error Controller */
    array('$404', 'ANY', 'error404@index')
];
