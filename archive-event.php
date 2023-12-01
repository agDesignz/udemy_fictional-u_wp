<?php get_header();
pageBanner([
'title' => 'All Events',
'subtitle' => "See what's going on here."
]);
?>

    <!-- DISPLAYING BLOG POSTS: LESSON 22 -->
    <div class="container container--narrow page-section">
      <?php
        while(have_posts()) {
          the_post();
          get_template_part('template-parts/content-event');
        }

              // PAGINATION LINKS: LESSON 23
          echo paginate_links();
      ?>

      <hr class="section-break">

            <p><a href="<?php echo site_url('/past-events'); ?>">Looking for a recap of past events? Check out our past events archive.</a></p>

    </div>
<?php get_footer(); ?>
