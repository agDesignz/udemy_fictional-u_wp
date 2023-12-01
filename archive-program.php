<?php get_header();
pageBanner([
'title' => 'All Programs',
'subtitle' => "There is something for everyone. Have a look around."
]);
?>


    <!-- DISPLAYING BLOG POSTS: LESSON 22 -->
    <div class="container container--narrow page-section">

      <ul class="link-list min-list">


      <?php
        while(have_posts()) {
          the_post(); ?>
          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

        <?php }

              // PAGINATION LINKS: LESSON 23
          echo paginate_links();
      ?>
</ul>

    </div>
<?php get_footer(); ?>
