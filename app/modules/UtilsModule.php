<?php

/**
 * This class represents your module
 */
class UtilsModule {
    /**
     * @return array
     */
    public static function GetMonthList()
    {
        return [
            __('app.january'),
            __('app.february'),
            __('app.march'),
            __('app.april'),
            __('app.may'),
            __('app.june'),
            __('app.july'),
            __('app.august'),
            __('app.september'),
            __('app.october'),
            __('app.november'),
            __('app.december'),
        ];
    }
}
