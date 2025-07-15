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
    array('/plants/location/{id}/repot', 'ANY', 'plants@set_plants_repotted'),
    array('/plants/location/{id}/fertilise', 'ANY', 'plants@set_plants_fertilised'),
    array('/plants/location/{id}/notes/save', 'ANY', 'plants@save_location_notes'),
    array('/plants/details/{id}', 'GET', 'plants@view_plant_details'),
    array('/plants/add', 'POST', 'plants@add_plant'),
    array('/plants/details/edit', 'POST', 'plants@edit_plant_details'),
    array('/plants/details/edit/ajax', 'POST', 'plants@edit_plant_details_ajax'),
    array('/plants/details/edit/link', 'POST', 'plants@edit_plant_link'),
    array('/plants/details/edit/photo', 'POST', 'plants@edit_plant_details_photo'),
    array('/plants/details/edit/photo/url', 'POST', 'plants@edit_plant_details_photo_url'),
    array('/plants/details/photo/remove', 'POST', 'plants@edit_remove_preview_photo'),
    array('/plants/details/gallery/add', 'POST', 'plants@add_plant_gallery_photo'),
    array('/plants/details/gallery/add/url', 'POST', 'plants@add_plant_gallery_photo_url'),
    array('/plants/details/gallery/photo/remove', 'POST', 'plants@remove_gallery_photo'),
    array('/plants/details/gallery/photo/label/edit', 'POST', 'plants@edit_gallery_photo_label'),
    array('/plants/details/gallery/photo/setmain', 'POST', 'plants@set_gallery_photo_as_main'),
    array('/plants/details/identify', 'POST', 'plants@identify_plant'),
    array('/plants/attributes/add', 'POST', 'plants@add_custom_attribute'),
    array('/plants/attributes/edit', 'POST', 'plants@edit_custom_attribute'),
    array('/plants/attributes/remove', 'ANY', 'plants@remove_custom_attribute'),
    array('/plants/remove', 'ANY', 'plants@remove_plant'),
    array('/plants/history', 'GET', 'plants@view_history'),
    array('/plants/history/add', 'ANY', 'plants@add_to_history'),
    array('/plants/history/remove', 'ANY', 'plants@remove_from_history'),
    array('/plants/clone', 'POST', 'plants@clone_plant'),
    array('/plants/update/bulk', 'POST', 'plants@perform_bulk_updates'),
    array('/plants/qrcode', 'ANY', 'plants@generate_qr_code'),
    array('/plants/qrcode/bulk', 'POST', 'plants@get_bulk_qr_codes'),
    array('/plants/log/add', 'POST', 'plants@add_plant_log_entry'),
    array('/plants/log/edit', 'POST', 'plants@edit_plant_log_entry'),
    array('/plants/log/remove', 'POST', 'plants@remove_plant_log_entry'),
    array('/plants/log/fetch', 'ANY', 'plants@fetch_plant_log_entries'),
    array('/plants/location/log/add', 'POST', 'plants@add_location_log_entry'),
    array('/plants/location/log/edit', 'POST', 'plants@edit_location_log_entry'),
    array('/plants/location/log/remove', 'POST', 'plants@remove_location_log_entry'),
    array('/plants/location/log/fetch', 'ANY', 'plants@fetch_location_log_entries'),
    array('/plants/gbif/{section}/{identifier}/{information}', 'ANY', 'plants@gbif_query'),

    /** User controller Controller */
    array('/profile', 'GET', 'user@view_profile'),
    array('/profile/preferences', 'POST', 'user@edit_preferences'),
    array('/profile/notes/save', 'POST', 'user@save_notes'),
    array('/profile/sharelog/fetch', 'ANY', 'user@fetch_share_log'),

    /** Search controller Controller */
    array('/search', 'GET', 'search@view_search'),
    array('/search/perform', 'POST', 'search@perform_search'),

    /** Tasks Controller */
    array('/tasks', 'GET', 'tasks@view_tasks'),
    array('/tasks/create', 'POST', 'tasks@create_task'),
    array('/tasks/edit', 'POST', 'tasks@edit_task'),
    array('/tasks/toggle', 'POST', 'tasks@toggle_task'),
    array('/tasks/remove', 'ANY', 'tasks@remove_task'),

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
    array('/inventory/qrcode', 'ANY', 'inventory@generate_qr_code'),
    array('/inventory/qrcode/bulk', 'POST', 'inventory@get_bulk_qr_codes'),
    array('/inventory/export', 'POST', 'inventory@export_items'),

    /** Calendar Controller */
    array('/calendar', 'GET', 'calendar@view_calendar'),
    array('/calendar/query', 'POST', 'calendar@query_items'),
    array('/calendar/add', 'POST', 'calendar@add_item'),
    array('/calendar/edit', 'POST', 'calendar@edit_item'),
    array('/calendar/remove', 'ANY', 'calendar@remove_item'),

    /** Weather controller */
    array('/weather', 'GET', 'weather@view_forecast'),

    /** Chat Controller */
    array('/chat', 'GET', 'chat@view_chat'),
    array('/chat/add', 'POST', 'chat@add_chat_message'),
    array('/chat/query', 'GET', 'chat@query_chat_messages'),
    array('/chat/typing', 'ANY', 'chat@get_chat_typing_status'),
    array('/chat/typing/update', 'ANY', 'chat@update_chat_typing'),
    array('/chat/user/online', 'ANY', 'chat@get_online_users'),
    array('/chat/messages/count', 'ANY', 'chat@get_message_count'),
    array('/chat/system/message/latest', 'ANY', 'chat@get_latest_system_message'),

    /** Admin Controller */
    array('/admin', 'GET', 'admin@index'),
    array('/admin/environment/save', 'POST', 'admin@save_environment'),
    array('/admin/environment/boolean/toggle', 'ANY', 'admin@toggle_boolean_value'),
    array('/admin/user/create', 'POST', 'admin@create_user'),
    array('/admin/user/update', 'POST', 'admin@update_user'),
    array('/admin/user/remove', 'ANY', 'admin@remove_user'),
    array('/admin/location/add', 'POST', 'admin@add_location'),
    array('/admin/location/update', 'POST', 'admin@update_location'),
    array('/admin/location/photo', 'POST', 'admin@set_location_photo'),
    array('/admin/location/remove', 'ANY', 'admin@remove_location'),
    array('/admin/auth/proxy/save', 'POST', 'admin@save_proxy_auth_settings'),
    array('/admin/attribute/schema/add', 'POST', 'admin@add_attribute_schema'),
    array('/admin/attribute/schema/edit', 'POST', 'admin@edit_attribute_schema'),
    array('/admin/attribute/schema/remove', 'ANY', 'admin@remove_attribute_schema'),
    array('/admin/attribute/update', 'ANY', 'admin@update_attribute'),
    array('/admin/attributes/bulkcmd/add', 'POST', 'admin@add_bulk_cmd'),
    array('/admin/attributes/bulkcmd/edit', 'POST', 'admin@edit_bulk_cmd'),
    array('/admin/attributes/bulkcmd/remove', 'ANY', 'admin@remove_bulk_cmd'),
    array('/admin/calendar/class/add', 'POST', 'admin@add_calendar_class'),
    array('/admin/calendar/class/edit', 'POST', 'admin@edit_calendar_class'),
    array('/admin/calendar/class/remove', 'POST', 'admin@remove_calendar_class'),
    array('/admin/media/logo', 'POST', 'admin@upload_media_logo'),
    array('/admin/media/banner', 'POST', 'admin@upload_media_banner'),
    array('/admin/media/background', 'POST', 'admin@upload_media_background'),
    array('/admin/media/overlay/alpha', 'POST', 'admin@save_overlay_alpha'),
    array('/admin/media/sound/message', 'POST', 'admin@upload_media_sound_message'),
    array('/admin/mail/save', 'POST', 'admin@save_mail_settings'),
    array('/admin/themes/import', 'POST', 'admin@import_theme'),
    array('/admin/themes/remove', 'POST', 'admin@remove_theme'),
    array('/admin/backup/cronjob/save', 'POST', 'admin@save_backup_cronjob_settings'),
    array('/admin/cronjob/token', 'POST', 'admin@generate_cronjob_token'),
    array('/admin/weather/save', 'POST', 'admin@save_weather_data'),
    array('/admin/api/add', 'ANY', 'admin@add_api_key'),
    array('/admin/api/{token}/remove', 'ANY', 'admin@remove_api_key'),
    array('/admin/api/{id}/toggle', 'ANY', 'admin@toggle_api_key'),
    array('/admin/cache/clear', 'ANY', 'admin@clear_cache'),

    /** Cronjob Controller */
    array('/cronjob/tasks/overdue', 'GET', 'cronjobs@overdue_tasks'),
    array('/cronjob/tasks/tomorrow', 'GET', 'cronjobs@tomorrow_tasks'),
    array('/cronjob/tasks/recurring', 'GET', 'cronjobs@recurring_tasks'),
    array('/cronjob/calendar/reminder', 'GET', 'cronjobs@calendar_reminder'),
    array('/cronjob/backup/auto', 'GET', 'cronjobs@auto_backup'),

    /** Share Controller */
    array('/share/photo/post', 'POST', 'share@share_photo'),
    array('/share/photo/remove', 'ANY', 'share@remove_photo'),

    /** API Controller */
    array('/api/plants/get', 'ANY', 'api@get_plant'),
    array('/api/plants/add', 'ANY', 'api@add_plant'),
    array('/api/plants/update', 'ANY', 'api@update_plant'),
    array('/api/plants/remove', 'ANY', 'api@remove_plant'),
    array('/api/plants/list', 'ANY', 'api@get_plant_list'),
    array('/api/plants/search', 'ANY', 'api@search_plants'),
    array('/api/plants/attributes/add', 'ANY', 'api@add_attribute'),
    array('/api/plants/attributes/edit', 'ANY', 'api@edit_attribute'),
    array('/api/plants/attributes/remove', 'ANY', 'api@remove_attribute'),
    array('/api/plants/photo/update', 'ANY', 'api@update_plant_photo'),
    array('/api/plants/gallery/add', 'ANY', 'api@add_plant_gallery_photo'),
    array('/api/plants/gallery/edit', 'ANY', 'api@edit_plant_gallery_photo'),
    array('/api/plants/gallery/remove', 'ANY', 'api@remove_plant_gallery_photo'),
    array('/api/plants/log/add', 'ANY', 'api@add_plant_log_entry'),
    array('/api/plants/log/edit', 'ANY', 'api@edit_plant_log_entry'),
    array('/api/plants/log/remove', 'ANY', 'api@remove_plant_log_entry'),
    array('/api/plants/log/fetch', 'ANY', 'api@fetch_plant_log_entries'),
    array('/api/locations/list', 'ANY', 'api@fetch_location_list'),
    array('/api/locations/info', 'ANY', 'api@fetch_location_info'),
    array('/api/tasks/fetch', 'ANY', 'api@fetch_tasks_list'),
    array('/api/tasks/add', 'ANY', 'api@add_task'),
    array('/api/tasks/edit', 'ANY', 'api@edit_task'),
    array('/api/tasks/remove', 'ANY', 'api@remove_task'),
    array('/api/inventory/fetch', 'ANY', 'api@fetch_inventory'),
    array('/api/inventory/add', 'ANY', 'api@add_inventory_item'),
    array('/api/inventory/edit', 'ANY', 'api@edit_inventory_item'),
    array('/api/inventory/amount/inc', 'ANY', 'api@inc_inventory_item'),
    array('/api/inventory/amount/dec', 'ANY', 'api@dec_inventory_item'),
    array('/api/inventory/remove', 'ANY', 'api@remove_inventory_item'),
    array('/api/calendar/fetch', 'ANY', 'api@fetch_calendar_entries'),
    array('/api/calendar/add', 'ANY', 'api@add_calendar_entry'),
    array('/api/calendar/edit', 'ANY', 'api@edit_calendar_entry'),
    array('/api/calendar/remove', 'ANY', 'api@remove_calendar_entry'),
    array('/api/chat/fetch', 'ANY', 'api@fetch_chat_messages'),
    array('/api/chat/message/add', 'ANY', 'api@add_chat_message'),
    array('/api/backup/export', 'ANY', 'api@export_backup'),
    array('/api/backup/import', 'POST', 'api@import_backup'),

    /** Backup Controller */
    array('/export/start', 'POST', 'backup@export'),
    array('/import/start', 'POST', 'backup@import'),

    /** Error Controller */
    array('$404', 'ANY', 'error404@index')
];
