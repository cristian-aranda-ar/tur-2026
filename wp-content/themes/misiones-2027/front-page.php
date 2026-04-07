<?php
/**
 * Front Page Template — Home
 */
get_header();
?>

<?php get_template_part( 'template-parts/home/hero' ); ?>

<!-- Floating search bar -->
<div class="search-bar-wrap">
  <form class="search-bar" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input type="hidden" name="post_type" value="registro-unico">
    <span class="search-bar__icon" aria-hidden="true">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
      </svg>
    </span>
    <input
      type="search"
      name="s"
      class="search-bar__input"
      placeholder="<?php esc_attr_e( 'Buscar destinos, experiencias…', 'misiones-2027' ); ?>"
      value="<?php echo esc_attr( get_search_query() ); ?>"
      autocomplete="off"
    >
    <button type="submit" class="search-bar__btn"><?php esc_html_e( 'Buscar', 'misiones-2027' ); ?></button>
  </form>
</div>

<?php get_template_part( 'template-parts/home/quick-actions' ); ?>
<?php get_template_part( 'template-parts/home/destinos' ); ?>
<?php get_template_part( 'template-parts/home/experience-profiles' ); ?>
<?php get_template_part( 'template-parts/home/events' ); ?>
<?php get_template_part( 'template-parts/home/info-center' ); ?>
<?php get_template_part( 'template-parts/home/news' ); ?>
<?php get_template_part( 'template-parts/home/instagram-feed' ); ?>
<?php get_template_part( 'template-parts/home/map' ); ?>

<?php get_footer(); ?>
