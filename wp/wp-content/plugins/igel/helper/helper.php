<?php
function shorten_excerpt($excerpt, $max = 175)
{
    if (strlen($excerpt) > $max) {
        $excerpt = substr($excerpt, 0, $max - 5);
        while (substr($excerpt, -1) !== ' ') {
            $excerpt = substr($excerpt, 0, -1);
        }
        $excerpt .= '[...]';
    }
    return $excerpt;
}