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
    array('/plants/details/{id}', 'GET', 'index@view_plant_details'),
    array('/plants/add', 'POST', 'index@add_plant'),
    array('/plants/details/edit', 'POST', 'index@edit_plant_details'),
    array('/plants/details/edit/photo', 'POST', 'index@edit_plant_details_photo'),
    array('/plants/details/gallery/add', 'POST', 'index@add_plant_gallery_photo'),
    array('/plants/details/gallery/photo/remove', 'POST', 'index@remove_gallery_photo'),
    array('$404', 'ANY', 'error404@index')
];
