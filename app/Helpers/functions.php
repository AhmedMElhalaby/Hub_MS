<?php

if (!function_exists('enum_values')){
    function enum_values($enum)
    {
        return array_column($enum::cases(), 'value');
    }
}
