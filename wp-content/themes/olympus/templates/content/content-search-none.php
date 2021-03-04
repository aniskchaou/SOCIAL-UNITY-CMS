<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package olympus
 */
?>

<div class="col-md-12">
    <article id="search-fail" class="ui-block">
        <p class="entry-content" itemprop="text">
            <strong><?php esc_html_e( 'Nothing Found', 'olympus' ); ?></strong><br>
            <?php esc_html_e( 'Sorry, no posts matched your criteria. Please try another search', 'olympus' ); ?>
        </p>

        <div class="hr_invisible"></div>

        <section class="search_not_found">
            <p><?php esc_html_e( 'You might want to consider some of our suggestions to get better results:', 'olympus' ); ?></p>
            <ul>
                <li><?php esc_html_e( 'Check your spelling.', 'olympus' ); ?></li>
                <li><?php esc_html_e( 'Try a similar keyword, for example: tablet instead of laptop.', 'olympus' ); ?></li>
                <li><?php esc_html_e( 'Try using more than one keyword.', 'olympus' ); ?></li>
            </ul>
        </section>
    </article>
</div>