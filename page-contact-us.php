<?php
get_header();
pageBanner();

  while(have_posts()) {
    the_post(); ?>

<div class="container container--narrow page-section">
  <div class="generic-content">
    <?php the_content();
    display_contact_form();
    ?>



  </div>
</div>

  <?php }

  get_footer();
?>
