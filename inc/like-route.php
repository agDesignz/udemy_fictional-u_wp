<?php

add_action('rest_api_init', 'universityLikeRoutes');

function universityLikeRoutes() {
  register_rest_route('university/v1', 'manageLike', [
    'methods' => 'POST',
    'callback' => 'createLike'
  ]);
  // register_rest_route('namespace', 'name for this specific route or url', ['methods' => x,'callback' => y])
  register_rest_route('university/v1', 'manageLike', [
    'methods' => 'DELETE',
    'callback' => 'deleteLike'
  ]);
}

function createLike($data) {
  $professor = sanitize_text_field($data['professorId']);
  wp_insert_post(array(
    'post_type' => 'like',
    'post_status' => 'publish',
    'post_title' => 'Third PHP Test',
    'meta_input' => [
      'liked_professor_id' => $professor
      ]
  ));
}

function deleteLike() {
  return 'Thanks for trying to delete a like';
}
