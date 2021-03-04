<h1 style="font-weight:100; text-align:center;"><?php echo esc_html__( 'No Forms Available', 'crumina' ); ?></h1>
<p style="text-align:center">
    <em>
        <?php
        echo sprintf(
        __( 'No Forms created yet. Please go to the %sForms page and %s.', 'crumina' ), '<br />', '<a href="' . admin_url( 'post-new.php?post_type=fw-form' ) . '" target="_blank">' . esc_html__( 'create a new Form', 'crumina' ) . '</a>'
        );
        ?>
    </em>
</p>