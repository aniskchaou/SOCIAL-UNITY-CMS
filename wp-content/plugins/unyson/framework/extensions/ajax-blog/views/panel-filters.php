<?php
$show_reactions  = fw_akg( 'panel-filters/reactions', $panel_components, 'yes' );
$show_categories = fw_akg( 'panel-filters/categories', $panel_components, 'yes' );
$show_order      = fw_akg( 'panel-filters/order', $panel_components, 'yes' );
$show_order_by   = fw_akg( 'panel-filters/order-by', $panel_components, 'yes' );
$show_search     = fw_akg( 'panel-filters/search', $panel_components, 'yes' );

if($show_reactions !== 'yes' && $show_categories !== 'yes' && $show_order !== 'yes' && $show_order_by !== 'yes' && $show_search !== 'yes'){
    return;
}
?>

<div id="<?php echo $panel_id; ?>" class="ui-block responsive-flex1200">
    <div class="ui-block-title">
        <?php if ( $enableReactions && $show_reactions === 'yes' ) { ?>
            <ul class="filter-icons">
                <?php
                foreach ( $availableReactions as $reaction ) {
                    $type = $reaction[ 'ico' ];
                    ?>
                    <li>
                        <a href="#" data-type="<?php echo $type; ?>">
                            <img src="<?php echo "{$reactions_img_path}/{$type}.png"; ?>" alt="icon">
                        </a>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>

        <?php if ( $show_categories === 'yes' ) { ?>
            <div class="w-select">
                <div class="title"><?php esc_html_e('Filter By', 'crum-ext-ajax-blog'); ?>:</div>
                <fieldset class="form-group">
                    <select class="selectpicker form-control category">
                        <option value="" data-url="<?php echo esc_attr( $page_for_posts_url ); ?>"><?php esc_html_e('All Categories', 'crum-ext-ajax-blog'); ?></option>
                        <?php echo $categories; ?>
                    </select>
                </fieldset>
            </div>
        <?php } ?>

        <?php if ( $show_order === 'yes' ) { ?>
            <div class="w-select">
                <fieldset class="form-group">
                    <select class="selectpicker form-control order">
                        <?php foreach ( $order_options as $key => $value ) { ?>
                            <option value="<?php echo esc_attr( $key ) ?>" <?php selected( $key, $order ); ?>><?php echo esc_html( $value ); ?></option>
                        <?php } ?>
                    </select>
                </fieldset>
            </div>
        <?php } ?>

        <?php if ( $show_order_by === 'yes' ) { ?>
            <div class="w-select">
                <fieldset class="form-group">
                    <select class="selectpicker form-control order-by">
                        <?php foreach ( $order_by_options as $key => $value ) { ?>
                            <option value="<?php echo esc_attr( $key ) ?>" <?php selected( $key, $order_by ); ?>><?php echo esc_html( $value ); ?></option>
                        <?php } ?>
                    </select>
                </fieldset>
            </div>
        <?php } ?>

        <a href="#" class="btn reset-btn btn-primary btn-md-2"><?php esc_html_e('All Categories', 'crum-ext-ajax-blog'); ?></a>

        <?php if ( $show_search === 'yes' ) { ?>
            <form class="w-search">
                <div class="form-group with-button">
                    <input class="form-control" type="text" placeholder="<?php esc_attr_e('Search Blog Posts...', 'crum-ext-ajax-blog'); ?>">
                    <button class="search-btn">
                        <i class="olympus-icon-Magnifying-Glass-Icon"></i>
                    </button>
                </div>
            </form>
        <?php } ?>
    </div>
</div>