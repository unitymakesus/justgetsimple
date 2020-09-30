<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class SinglePost extends Controller
{
    public function relatedPosts()
    {
        $post_id = get_the_ID();
        $terms = get_the_terms($post_id, 'category');

        if (empty($terms)) {
            $terms = [];
        }

        $term_list = wp_list_pluck($terms, 'slug');

        $related_args = [
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'post_status'    => 'publish',
            'post__not_in'   => [$post_id],
            'orderby'        => 'rand',
            'tax_query'      => [
                [
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => $term_list,
                ],
            ],
        ];

        return new \WP_Query($related_args);
    }
}
