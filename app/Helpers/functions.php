<?php

if (!function_exists('enum_values')){
    function enum_values($enum)
    {
        return array_column($enum::cases(), 'value');
    }
}
if (!function_exists('enum_rules')){
    function enum_rules($enum)
    {
        return  implode(',', enum_values($enum));
    }
}
if (! function_exists('tenant_route')) {
    function tenant_route($name,$parameters = [], $absolute = true) {
        if (!is_array($parameters)) {
            $parameters = [$parameters];
        }
        return route($name, array_merge(['tenant'=>app()->get('tenant')->domain],$parameters?? []), $absolute);
    }
}
