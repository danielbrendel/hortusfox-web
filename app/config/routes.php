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
    array('/', 'GET', 'index@index'),
    array('/plants/location/{id}', 'GET', 'index@plants_from_location'),
    array('/plants/location/{id}/water', 'ANY', 'index@set_plants_watered'),
    array('/plants/details/{id}', 'GET', 'index@view_plant_details'),
    array('/plants/add', 'POST', 'index@add_plant'),
    array('/plants/details/edit', 'POST', 'index@edit_plant_details'),
    array('/plants/details/edit/link', 'POST', 'index@edit_plant_link'),
    array('/plants/details/edit/photo', 'POST', 'index@edit_plant_details_photo'),
    array('/plants/details/gallery/add', 'POST', 'index@add_plant_gallery_photo'),
    array('/plants/details/gallery/photo/remove', 'POST', 'index@remove_gallery_photo'),
    array('/plants/remove', 'ANY', 'index@remove_plant'),
    array('/profile', 'GET', 'index@view_profile'),
    array('/profile/preferences', 'POST', 'index@edit_preferences'),
    array('/search', 'GET', 'index@view_search'),
    array('/search/perform', 'POST', 'index@perform_search'),
    array('/tasks', 'GET', 'index@view_tasks'),
    array('/tasks/create', 'POST', 'index@create_task'),
    array('/tasks/edit', 'POST', 'index@edit_task'),
    array('/tasks/toggle', 'POST', 'index@toggle_task'),
    array('$404', 'ANY', 'error404@index')
];
