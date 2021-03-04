<?php
/**
 * Template for displaying search forms in olympus
 *
 * @package olympus
 */
?>
<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="w-search full-width">
    <div class="form-group with-button is-empty">
        <input class="form-control" name="s" placeholder="<?php echo esc_attr__( 'Search', 'olympus' ); ?>"  value="<?php echo get_search_query(); ?>" type="search">
        <button>
            <?php echo olympus_icon_font('olympus-icon-Magnifying-Glass-Icon') ?>
        </button>
        <span class="material-input"></span></div>
</form>