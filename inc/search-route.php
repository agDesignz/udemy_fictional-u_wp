<?php

add_action('rest_api_init', 'universityRegisterSearch');


  function universityRegisterSearch() {
    register_rest_route('university/v1', 'search', [
      'methods' => WP_REST_SERVER::READABLE,
      'callback' => 'universitySearchResults',
    ]);
  }

function universitySearchResults($data) {
  $mainQuery = new WP_Query([
    'post_type' => ['post','page', 'professor', 'program', 'campus', 'event'],
    's' => sanitize_text_field($data['term'])
  ]);

  $results = [
    'generalInfo' => [],
    'professors' => [],
    'programs' => [],
    'events' => [],
    'campuses' => [],
  ];

  while($mainQuery->have_posts()) {
    $mainQuery->the_post();

    $postType = get_post_type();

    if ($postType == 'post' OR $postType == 'page') {
      array_push($results['generalInfo'], [
        'title' => get_the_title(),
        'link' => get_the_permalink(),
        'postType' => get_post_type(),
        'author' => get_the_author()
      ]);
    }

    if ($postType == 'professor') {
      array_push($results['professors'], [
        'title' => get_the_title(),
        'link' => get_the_permalink(),
      ]);
    }

    if ($postType == 'program') {
      array_push($results['programs'], [
        'title' => get_the_title(),
        'link' => get_the_permalink(),
      ]);
    }

    if ($postType == 'event') {
      array_push($results['events'], [
        'title' => get_the_title(),
        'link' => get_the_permalink(),
      ]);
    }

    if ($postType == 'campus') {
      array_push($results['campuses'], [
        'title' => get_the_title(),
        'link' => get_the_permalink(),
      ]);
    }
  }

  return $results;

}
