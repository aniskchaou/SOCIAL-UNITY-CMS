<?php
$total   = $query->max_num_pages;
$current = $page ? $page : 1;

if ( $current >= $total ) {
    return false;
}
?>
<a href="<?php echo str_replace( '%#%', ++$current, $paginateBase ); ?>" class="btn btn-control btn-more">
    <i class="olympus-icon-three-dots-icon"></i>
</a>