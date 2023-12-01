<?php
get_header();


  while(have_posts()) {
    the_post();
    pageBanner();
    ?>




<div class="container container--narrow page-section">


  <div class="generic-content">
    <div class="row group">
      <div class="one-third">
        <?php the_post_thumbnail(); ?>
      </div>
      <div class="two-thirds">
        <?php
          $likeCount = new WP_Query([
            'post_type' => 'like',
            'meta_query' => [
              [
                'key' => 'liked_professor_id',
                'compare' => '=',
                'value' => get_the_ID()
              ]
            ]
          ]);

          $existStatus = 'no';

          $existQuery = new WP_Query([
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => [
              [
                'key' => 'liked_professor_id',
                'compare' => '=',
                'value' => get_the_ID()
              ]
            ]
          ]);

          if ($existQuery->found_posts) {
            $existStatus = 'yes';
          }
        ?>
        <span class="like-box" data-professor="<?php the_ID(); ?>" data-exists="<?php echo $existStatus; ?>">
          <i class="fa fa-heart-o" aria-hidden="true"></i>
          <i class="fa fa-heart" aria-hidden="true"></i>
          <span class="like-count"><?php echo $likeCount->found_posts; ?></span>
        </span>
        <?php the_content(); ?>
      </div>
    </div>

  </div>
</div>

  <?php }

  get_footer();
?>
