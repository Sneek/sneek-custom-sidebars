<?php

function sneek_custom_sidebar($default = null, $post_id = null)
{
    $post_id = $post_id ?: get_the_ID();

    $sidebar = Sneek_Custom_Sidebars::get_custom_metabox($post_id);

    if ( $sidebar ) {
        return $sidebar;
    }

    return $default;
}