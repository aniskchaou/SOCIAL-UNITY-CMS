<?php

if ( !defined( 'FW' ) ) {
    return;
}

/**
 * Generate html markup for social networks share buttons.
 *
 * @param $style string Style of social links
 */
function crumina_single_post_share_btns( $style = 'rounded' ) {
    wp_enqueue_script( 'sharer' );
    $ext = fw_ext( 'post-share' );
    $custom_elements = $ext::get_option_final('single_post_elements/customize', 'no');
    $social_links = $ext::get_option('share_buttons_options', 
    array(
        'facebook' => true,
        'twitter' => true,
        'pocket' => true,
        'whatsapp' => true,
        'linkedin' => true 
    ), 'customizer');
    if($custom_elements == 'yes'){
        $social_links = $ext::get_option_final('single_post_elements/yes/single_post_elements_popup/share_buttons_options', array());
    }
    
    $btn_class = 'rounded' === $style ? 'btn btn-control has-i' : 'btn social-item';

    if(array_key_exists('facebook', $social_links)){
    ?>
    <button class="<?php echo esc_attr( $btn_class ) ?> bg-facebook sharer" data-sharer="facebook"
            data-url="<?php the_permalink(); ?>"
			data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>"
	>
		<svg class="crumina-icon" viewBox="0 0 512 512"><path d="M288 176v-64c0-17.664 14.336-32 32-32h32V0h-64c-53.024 0-96 42.976-96 96v80h-64v80h64v256h96V256h64l32-80h-96z"/></svg>
    </button>
    <?php
    }
    if(array_key_exists('twitter', $social_links)){
    ?>

    <button class="<?php echo esc_attr( $btn_class ) ?> bg-twitter sharer" data-sharer="twitter"
            data-url="<?php the_permalink(); ?>"
			data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>"
	>
		<svg class="crumina-icon" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="1.414"><path d="M16 3.038c-.59.26-1.22.437-1.885.517.677-.407 1.198-1.05 1.443-1.816-.634.37-1.337.64-2.085.79-.598-.64-1.45-1.04-2.396-1.04-1.812 0-3.282 1.47-3.282 3.28 0 .26.03.51.085.75-2.728-.13-5.147-1.44-6.766-3.42C.83 2.58.67 3.14.67 3.75c0 1.14.58 2.143 1.46 2.732-.538-.017-1.045-.165-1.487-.41v.04c0 1.59 1.13 2.918 2.633 3.22-.276.074-.566.114-.865.114-.21 0-.41-.02-.61-.058.42 1.304 1.63 2.253 3.07 2.28-1.12.88-2.54 1.404-4.07 1.404-.26 0-.52-.015-.78-.045 1.46.93 3.18 1.474 5.04 1.474 6.04 0 9.34-5 9.34-9.33 0-.14 0-.28-.01-.42.64-.46 1.2-1.04 1.64-1.7z" fill-rule="nonzero"/></svg>
    </button>
    <?php
    }
    if(array_key_exists('pocket', $social_links)){
    ?>
    <button class="<?php echo esc_attr( $btn_class ) ?> bg-google sharer" data-sharer="pocket"
			data-url="<?php the_permalink(); ?>"
			data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>"
	>
		<svg class="crumina-icon" viewBox="0 0 512 512"><path d="M480 32H32C14.368 32 0 46.368 0 64v176c0 132.352 107.648 240 240 240h32c132.352 0 240-107.648 240-240V64c0-17.632-14.336-32-32-32zm-73.376 182.624l-128 128C272.384 348.864 264.192 352 256 352s-16.384-3.136-22.624-9.376l-128-128c-12.512-12.512-12.512-32.736 0-45.248s32.736-12.512 45.248 0L256 274.752l105.376-105.376c12.512-12.512 32.736-12.512 45.248 0s12.512 32.736 0 45.248z"/></svg>
    </button>
    <?php
    }
    if(array_key_exists('whatsapp', $social_links)){
    ?>
    <button class="<?php echo esc_attr( $btn_class ) ?> bg-green sharer" data-sharer="whatsapp"
			data-url="<?php the_permalink(); ?>"
			data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>"
	>
		<svg class="crumina-icon" height="682pt" viewBox="-23 -21 682 682.66669" width="682pt" xmlns="http://www.w3.org/2000/svg"><path d="m544.386719 93.007812c-59.875-59.945312-139.503907-92.9726558-224.335938-93.007812-174.804687 0-317.070312 142.261719-317.140625 317.113281-.023437 55.894531 14.578125 110.457031 42.332032 158.550781l-44.992188 164.335938 168.121094-44.101562c46.324218 25.269531 98.476562 38.585937 151.550781 38.601562h.132813c174.785156 0 317.066406-142.273438 317.132812-317.132812.035156-84.742188-32.921875-164.417969-92.800781-224.359376zm-224.335938 487.933594h-.109375c-47.296875-.019531-93.683594-12.730468-134.160156-36.742187l-9.621094-5.714844-99.765625 26.171875 26.628907-97.269531-6.269532-9.972657c-26.386718-41.96875-40.320312-90.476562-40.296875-140.28125.054688-145.332031 118.304688-263.570312 263.699219-263.570312 70.40625.023438 136.589844 27.476562 186.355469 77.300781s77.15625 116.050781 77.132812 186.484375c-.0625 145.34375-118.304687 263.59375-263.59375 263.59375zm144.585938-197.417968c-7.921875-3.96875-46.882813-23.132813-54.148438-25.78125-7.257812-2.644532-12.546875-3.960938-17.824219 3.96875-5.285156 7.929687-20.46875 25.78125-25.09375 31.066406-4.625 5.289062-9.242187 5.953125-17.167968 1.984375-7.925782-3.964844-33.457032-12.335938-63.726563-39.332031-23.554687-21.011719-39.457031-46.960938-44.082031-54.890626-4.617188-7.9375-.039062-11.8125 3.476562-16.171874 8.578126-10.652344 17.167969-21.820313 19.808594-27.105469 2.644532-5.289063 1.320313-9.917969-.664062-13.882813-1.976563-3.964844-17.824219-42.96875-24.425782-58.839844-6.4375-15.445312-12.964843-13.359374-17.832031-13.601562-4.617187-.230469-9.902343-.277344-15.1875-.277344-5.28125 0-13.867187 1.980469-21.132812 9.917969-7.261719 7.933594-27.730469 27.101563-27.730469 66.105469s28.394531 76.683594 32.355469 81.972656c3.960937 5.289062 55.878906 85.328125 135.367187 119.648438 18.90625 8.171874 33.664063 13.042968 45.175782 16.695312 18.984374 6.03125 36.253906 5.179688 49.910156 3.140625 15.226562-2.277344 46.878906-19.171875 53.488281-37.679687 6.601563-18.511719 6.601563-34.375 4.617187-37.683594-1.976562-3.304688-7.261718-5.285156-15.183593-9.253906zm0 0" fill-rule="evenodd"/></svg>
    </button>
    <?php
    }
    if(array_key_exists('linkedin', $social_links)){
    ?>
    <button class="<?php echo esc_attr( $btn_class ) ?> bg-linkedin sharer" data-sharer="linkedin"
                data-url="<?php the_permalink( get_the_ID() );?>"
                data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>"
    >
			<svg class="crumina-icon" id="Bold" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m23.994 24v-.001h.006v-8.802c0-4.306-.927-7.623-5.961-7.623-2.42 0-4.044 1.328-4.707 2.587h-.07v-2.185h-4.773v16.023h4.97v-7.934c0-2.089.396-4.109 2.983-4.109 2.549 0 2.587 2.384 2.587 4.243v7.801z"/><path d="m.396 7.977h4.976v16.023h-4.976z"/><path d="m2.882 0c-1.591 0-2.882 1.291-2.882 2.882s1.291 2.909 2.882 2.909 2.882-1.318 2.882-2.909c-.001-1.591-1.292-2.882-2.882-2.882z"/></svg>
    </button>
    <?php
    }
    if(array_key_exists('telegram', $social_links)){
    ?>
    <button class="<?php echo esc_attr( $btn_class ) ?> bg-telegram sharer" data-sharer="telegram"
                data-url="<?php the_permalink( get_the_ID() );?>"
                data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>"
    >
            <svg class="crumina-icon" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m8.75 17.612v4.638c0 .324.208.611.516.713.077.025.156.037.234.037.234 0 .46-.11.604-.306l2.713-3.692z"/><path d="m23.685.139c-.23-.163-.532-.185-.782-.054l-22.5 11.75c-.266.139-.423.423-.401.722.023.3.222.556.505.653l6.255 2.138 13.321-11.39-10.308 12.419 10.483 3.583c.078.026.16.04.242.04.136 0 .271-.037.39-.109.19-.116.319-.311.352-.53l2.75-18.5c.041-.28-.077-.558-.307-.722z"/></svg>
    </button>
    <?php
    }
    if(array_key_exists('blogger', $social_links)){
    ?>
    <button class="<?php echo esc_attr( $btn_class ) ?> bg-blogger sharer" data-sharer="blogger"
                data-url="<?php the_permalink( get_the_ID() );?>"
                data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>"
    >
    <svg class="crumina-icon" fill="#fff" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><path d="M498.944,198.08c-10.816-4.608-57.184,0.512-70.048-11.104c-9.088-8.384-9.664-23.552-13.216-43.776
                c-5.952-33.888-8.416-41.568-14.592-54.912C378.592,40.736,324.704,0,275.744,0H162.24C72.96,0,0,72.864,0,161.824v188.672
                C0,439.296,72.96,512,162.24,512h186.464c89.28,0,161.76-72.704,162.272-161.504L512,219.84C512,219.84,512,203.68,498.944,198.08
                z M164.288,132.256h89.984c17.152,0,31.072,13.856,31.072,30.784c0,16.96-13.92,30.944-31.072,30.944h-89.984
                c-17.152,0-31.072-14.016-31.072-30.944C133.216,146.08,147.136,132.256,164.288,132.256z M347.168,378.912h-182.88
                c-17.152,0-31.072-14.016-31.072-30.784c0-16.928,13.92-30.784,31.072-30.784h182.88c16.992,0,30.912,13.856,30.912,30.784
                C378.08,364.896,364.16,378.912,347.168,378.912z"/></g></g></svg>
    </button>
    <?php
    }
    if(array_key_exists('reddit', $social_links)){
    ?>
    <button class="<?php echo esc_attr( $btn_class ) ?> bg-reddit sharer" data-sharer="reddit"
                data-url="<?php the_permalink( get_the_ID() );?>"
                data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>"
    >
    <svg class="crumina-icon" fill="#fff" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m21.325 9.308c-.758 0-1.425.319-1.916.816-1.805-1.268-4.239-2.084-6.936-2.171l1.401-6.406 4.461 1.016c0 1.108.89 2.013 1.982 2.013 1.113 0 2.008-.929 2.008-2.038s-.889-2.038-2.007-2.038c-.779 0-1.451.477-1.786 1.129l-4.927-1.108c-.248-.067-.491.113-.557.365l-1.538 7.062c-2.676.113-5.084.928-6.895 2.197-.491-.518-1.184-.837-1.942-.837-2.812 0-3.733 3.829-1.158 5.138-.091.405-.132.837-.132 1.268 0 4.301 4.775 7.786 10.638 7.786 5.888 0 10.663-3.485 10.663-7.786 0-.431-.045-.883-.156-1.289 2.523-1.314 1.594-5.115-1.203-5.117zm-15.724 5.41c0-1.129.89-2.038 2.008-2.038 1.092 0 1.983.903 1.983 2.038 0 1.109-.89 2.013-1.983 2.013-1.113.005-2.008-.904-2.008-2.013zm10.839 4.798c-1.841 1.868-7.036 1.868-8.878 0-.203-.18-.203-.498 0-.703.177-.18.491-.18.668 0 1.406 1.463 6.07 1.488 7.537 0 .177-.18.491-.18.668 0 .207.206.207.524.005.703zm-.041-2.781c-1.092 0-1.982-.903-1.982-2.011 0-1.129.89-2.038 1.982-2.038 1.113 0 2.008.903 2.008 2.038-.005 1.103-.895 2.011-2.008 2.011z"/></svg>
    </button>
    <?php
    }
    if(array_key_exists('viber', $social_links)){
    ?>
    <button class="<?php echo esc_attr( $btn_class ) ?> bg-viber sharer" data-sharer="viber"
                data-url="<?php the_permalink( get_the_ID() );?>"
                data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>"
    >
    <svg class="crumina-icon" fill="#fff" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 322 322" style="enable-background:new 0 0 322 322;" xml:space="preserve">
    <g id="XMLID_7_">
        <path id="XMLID_8_" d="M275.445,135.123c0.387-45.398-38.279-87.016-86.192-92.771c-0.953-0.113-1.991-0.285-3.09-0.467
            c-2.372-0.393-4.825-0.797-7.3-0.797c-9.82,0-12.445,6.898-13.136,11.012c-0.672,4-0.031,7.359,1.902,9.988
            c3.252,4.422,8.974,5.207,13.57,5.836c1.347,0.186,2.618,0.359,3.682,0.598c43.048,9.619,57.543,24.742,64.627,67.424
            c0.173,1.043,0.251,2.328,0.334,3.691c0.309,5.102,0.953,15.717,12.365,15.717h0.001c0.95,0,1.971-0.082,3.034-0.244
            c10.627-1.615,10.294-11.318,10.134-15.98c-0.045-1.313-0.088-2.555,0.023-3.381C275.429,135.541,275.444,135.332,275.445,135.123z
            "/>
        <path id="XMLID_9_" d="M176.077,25.688c1.275,0.092,2.482,0.18,3.487,0.334c70.689,10.871,103.198,44.363,112.207,115.605
            c0.153,1.211,0.177,2.688,0.202,4.252c0.09,5.566,0.275,17.145,12.71,17.385l0.386,0.004c3.9,0,7.002-1.176,9.221-3.498
            c3.871-4.049,3.601-10.064,3.383-14.898c-0.053-1.186-0.104-2.303-0.091-3.281C318.481,68.729,255.411,2.658,182.614,0.201
            c-0.302-0.01-0.59,0.006-0.881,0.047c-0.143,0.021-0.408,0.047-0.862,0.047c-0.726,0-1.619-0.063-2.566-0.127
            C177.16,0.09,175.862,0,174.546,0c-11.593,0-13.797,8.24-14.079,13.152C159.817,24.504,170.799,25.303,176.077,25.688z"/>
        <path id="XMLID_10_" d="M288.36,233.703c-1.503-1.148-3.057-2.336-4.512-3.508c-7.718-6.211-15.929-11.936-23.87-17.473
            c-1.648-1.148-3.296-2.297-4.938-3.449c-10.172-7.145-19.317-10.617-27.957-10.617c-11.637,0-21.783,6.43-30.157,19.109
            c-3.71,5.621-8.211,8.354-13.758,8.354c-3.28,0-7.007-0.936-11.076-2.783c-32.833-14.889-56.278-37.717-69.685-67.85
            c-6.481-14.564-4.38-24.084,7.026-31.832c6.477-4.396,18.533-12.58,17.679-28.252c-0.967-17.797-40.235-71.346-56.78-77.428
            c-7.005-2.576-14.365-2.6-21.915-0.06c-19.02,6.394-32.669,17.623-39.475,32.471C2.365,64.732,2.662,81.578,9.801,99.102
            c20.638,50.666,49.654,94.84,86.245,131.293c35.816,35.684,79.837,64.914,130.839,86.875c4.597,1.978,9.419,3.057,12.94,3.844
            c1.2,0.27,2.236,0.5,2.991,0.707c0.415,0.113,0.843,0.174,1.272,0.178l0.403,0.002c0.001,0,0,0,0.002,0
            c23.988,0,52.791-21.92,61.637-46.91C313.88,253.209,299.73,242.393,288.36,233.703z"/>
        <path id="XMLID_11_" d="M186.687,83.564c-4.107,0.104-12.654,0.316-15.653,9.021c-1.403,4.068-1.235,7.6,0.5,10.498
            c2.546,4.252,7.424,5.555,11.861,6.27c16.091,2.582,24.355,11.48,26.008,28c0.768,7.703,5.955,13.082,12.615,13.082h0.001
            c0.492,0,0.995-0.029,1.496-0.09c8.01-0.953,11.893-6.838,11.542-17.49c0.128-11.117-5.69-23.738-15.585-33.791
            C209.543,88.98,197.574,83.301,186.687,83.564z"/>
    </g>
    </svg>
    </button>
    <?php
    }
    if(array_key_exists('pinterest', $social_links)){
    ?>
    <button class="<?php echo esc_attr( $btn_class ) ?> bg-pinterest sharer" data-sharer="pinterest"
                data-url="<?php the_permalink( get_the_ID() );?>"
                data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>"
    >
    <svg class="crumina-icon" fill="#fff" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m12.326 0c-6.579.001-10.076 4.216-10.076 8.812 0 2.131 1.191 4.79 3.098 5.633.544.245.472-.054.94-1.844.037-.149.018-.278-.102-.417-2.726-3.153-.532-9.635 5.751-9.635 9.093 0 7.394 12.582 1.582 12.582-1.498 0-2.614-1.176-2.261-2.631.428-1.733 1.266-3.596 1.266-4.845 0-3.148-4.69-2.681-4.69 1.49 0 1.289.456 2.159.456 2.159s-1.509 6.096-1.789 7.235c-.474 1.928.064 5.049.111 5.318.029.148.195.195.288.073.149-.195 1.973-2.797 2.484-4.678.186-.685.949-3.465.949-3.465.503.908 1.953 1.668 3.498 1.668 4.596 0 7.918-4.04 7.918-9.053-.016-4.806-4.129-8.402-9.423-8.402z"/></svg>
    </button>
    <?php
    }
    if(array_key_exists('tumblr', $social_links)){
    ?>
    <button class="<?php echo esc_attr( $btn_class ) ?> bg-tumblr sharer" data-sharer="tumblr"
                data-url="<?php the_permalink( get_the_ID() );?>"
                data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>"
    >
    <svg class="crumina-icon" fill="#fff" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m19 22.594-1.175-3.425c-.458.214-1.327.399-1.968.419h-.091c-1.863 0-2.228-1.37-2.244-2.371v-7.47h4.901v-3.633h-4.883v-6.114h-3.575c-.059 0-.162.051-.176.179-.202 1.873-1.098 5.156-4.789 6.469v3.099h2.456v7.842c0 2.655 1.97 6.411 7.148 6.411l-.011-.002h.181c1.786-.03 3.783-.768 4.226-1.404z"/></svg>
    </button>
    <?php
    }
    if(array_key_exists('xing', $social_links)){
    ?>
    <button class="<?php echo esc_attr( $btn_class ) ?> bg-xing sharer" data-sharer="xing"
                data-url="<?php the_permalink( get_the_ID() );?>"
                data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>"
    >
    <svg class="crumina-icon" fill="#fff" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
    <g>
        <g>
            <polygon points="496,0 376.384,0 198.688,311.264 313.184,512 432.8,512 318.304,311.264 		"/>
        </g>
    </g>
    <g>
        <g>
            <polygon points="149.216,96 36.448,96 101.696,210.912 16,352 128.768,352 214.464,210.912 		"/>
        </g>
    </g>
    </svg>
    </button>
    <?php
    }
    if(array_key_exists('myspace', $social_links)){
    ?>
    <button class="<?php echo esc_attr( $btn_class ) ?> bg-myspace sharer" data-sharer="myspace"
                data-url="<?php the_permalink( get_the_ID() );?>"
                data-title="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>"
    >
    <svg class="crumina-icon" fill="#fff" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
    <g>
        <g>
            <path d="M400,224c-31.36,0-59.648,13.024-80,33.856V256c0-52.928-43.072-96-96-96c-31.136,0-58.56,15.136-76.128,38.176
                C133.76,175.36,108.736,160,80,160c-44.096,0-80,35.904-80,80v112h128v64h160v96h224V336C512,274.24,461.76,224,400,224z"/>
        </g>
    </g>
    <g>
        <g>
            <circle cx="400" cy="96" r="96"/>
        </g>
    </g>
    <g>
        <g>
            <circle cx="224" cy="80" r="64"/>
        </g>
    </g>
    <g>
        <g>
            <circle cx="80" cy="96" r="48"/>
        </g>
    </g>
    </svg>
    </button>
    <?php
    }
}

/**
 * Generate html markup for social networks share buttons.
 *
 * @param $post_ID integer Post ID from loop
 */
function crumina_blog_post_share_btns( $post_ID ) {
    wp_enqueue_script( 'sharer' );

    $ext = fw_ext( 'post-share' );
    $custom_elements = $ext::get_option_final('single_post_elements/customize', 'no');
    $social_links = $ext::get_option('share_buttons_options', 
    array(
        'facebook' => true,
        'twitter' => true,
        'pocket' => true,
        'whatsapp' => true,
        'linkedin' => true 
    ), 'customizer');
    if($custom_elements == 'yes'){
        $social_links = $ext::get_option_final('single_post_elements/yes/single_post_elements_popup/share_buttons_options');
    }

    if ( empty( $post_ID ) ) {
        $post_ID = get_the_ID();
    }
    ?>
    <span class="post-add-icon inline-items more">
        <?php echo olympus_svg_icon( 'olymp-share-icon' ); ?>
        <span><?php esc_html_e( 'Share', 'crum-ext-post-share' ); ?></span>
        <ul class="social_share_icons more-dropdown">
            <?php
            if(array_key_exists('twitter', $social_links)){
            ?>
            <li>
                <button class="social__item sharer" data-sharer="twitter"
                        data-title="<?php echo esc_attr( get_the_title( $post_ID ) ); ?>"
                        data-url="<?php the_permalink( $post_ID ); ?>">
                    <svg class="crumina-icon" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="1.414"><path d="M16 3.038c-.59.26-1.22.437-1.885.517.677-.407 1.198-1.05 1.443-1.816-.634.37-1.337.64-2.085.79-.598-.64-1.45-1.04-2.396-1.04-1.812 0-3.282 1.47-3.282 3.28 0 .26.03.51.085.75-2.728-.13-5.147-1.44-6.766-3.42C.83 2.58.67 3.14.67 3.75c0 1.14.58 2.143 1.46 2.732-.538-.017-1.045-.165-1.487-.41v.04c0 1.59 1.13 2.918 2.633 3.22-.276.074-.566.114-.865.114-.21 0-.41-.02-.61-.058.42 1.304 1.63 2.253 3.07 2.28-1.12.88-2.54 1.404-4.07 1.404-.26 0-.52-.015-.78-.045 1.46.93 3.18 1.474 5.04 1.474 6.04 0 9.34-5 9.34-9.33 0-.14 0-.28-.01-.42.64-.46 1.2-1.04 1.64-1.7z" fill-rule="nonzero"/></svg>
                </button>
            </li>
            <?php
            }
            if(array_key_exists('facebook', $social_links)){
            ?>
            <li>
                <button class="social__item sharer" data-sharer="facebook"
                        data-title="<?php echo esc_attr( get_the_title( $post_ID ) ); ?>"
                        data-url="<?php the_permalink( $post_ID ); ?>"
                        data-image="<?php echo esc_attr( get_the_post_thumbnail_url( $post_ID, 'large' ) ); ?>">
                    <svg class="crumina-icon" viewBox="0 0 512 512"><path d="M288 176v-64c0-17.664 14.336-32 32-32h32V0h-64c-53.024 0-96 42.976-96 96v80h-64v80h64v256h96V256h64l32-80h-96z"/></svg>
                </button>
            </li>
            <?php
            }
            if(array_key_exists('pocket', $social_links)){
            ?>
             <li>
                <button class="social__item sharer" data-sharer="pocket"
						data-title="<?php echo esc_attr( get_the_title( $post_ID ) ); ?>"
						data-url="<?php the_permalink( $post_ID ); ?>">
                    <svg class="crumina-icon" viewBox="0 0 512 512"><path d="M480 32H32C14.368 32 0 46.368 0 64v176c0 132.352 107.648 240 240 240h32c132.352 0 240-107.648 240-240V64c0-17.632-14.336-32-32-32zm-73.376 182.624l-128 128C272.384 348.864 264.192 352 256 352s-16.384-3.136-22.624-9.376l-128-128c-12.512-12.512-12.512-32.736 0-45.248s32.736-12.512 45.248 0L256 274.752l105.376-105.376c12.512-12.512 32.736-12.512 45.248 0s12.512 32.736 0 45.248z"/></svg>
                </button>
            </li>
            <?php
            }
            if(array_key_exists('linkedin', $social_links)){
            ?>
            <li>
                <button class="social__item sharer" data-sharer="linkedin"
                        data-title="<?php echo esc_attr( get_the_title( $post_ID ) ); ?>"
                        data-url="<?php the_permalink( $post_ID ); ?>"
                        data-image="<?php echo esc_attr( get_the_post_thumbnail_url( $post_ID, 'large' ) ); ?>">
                    <svg class="crumina-icon" id="Bold" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m23.994 24v-.001h.006v-8.802c0-4.306-.927-7.623-5.961-7.623-2.42 0-4.044 1.328-4.707 2.587h-.07v-2.185h-4.773v16.023h4.97v-7.934c0-2.089.396-4.109 2.983-4.109 2.549 0 2.587 2.384 2.587 4.243v7.801z"/><path d="m.396 7.977h4.976v16.023h-4.976z"/><path d="m2.882 0c-1.591 0-2.882 1.291-2.882 2.882s1.291 2.909 2.882 2.909 2.882-1.318 2.882-2.909c-.001-1.591-1.292-2.882-2.882-2.882z"/></svg>
                </button>
            </li>
            <?php
            }
            if(array_key_exists('telegram', $social_links)){
            ?>
            <li>
                <button class="social__item sharer" data-sharer="telegram"
                        data-title="<?php echo esc_attr( get_the_title( $post_ID ) ); ?>"
                        data-url="<?php the_permalink( $post_ID ); ?>">
                    <svg class="crumina-icon" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m8.75 17.612v4.638c0 .324.208.611.516.713.077.025.156.037.234.037.234 0 .46-.11.604-.306l2.713-3.692z"/><path d="m23.685.139c-.23-.163-.532-.185-.782-.054l-22.5 11.75c-.266.139-.423.423-.401.722.023.3.222.556.505.653l6.255 2.138 13.321-11.39-10.308 12.419 10.483 3.583c.078.026.16.04.242.04.136 0 .271-.037.39-.109.19-.116.319-.311.352-.53l2.75-18.5c.041-.28-.077-.558-.307-.722z"/></svg>
                </button>
            </li>
            <?php
            }
            if(array_key_exists('blogger', $social_links)){
            ?>
            <li>
                <button class="social__item sharer" data-sharer="blogger"
                        data-title="<?php echo esc_attr( get_the_title( $post_ID ) ); ?>"
                        data-url="<?php the_permalink( $post_ID ); ?>">
                        <svg class="crumina-icon" fill="#fff" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><path d="M498.944,198.08c-10.816-4.608-57.184,0.512-70.048-11.104c-9.088-8.384-9.664-23.552-13.216-43.776
                        c-5.952-33.888-8.416-41.568-14.592-54.912C378.592,40.736,324.704,0,275.744,0H162.24C72.96,0,0,72.864,0,161.824v188.672
                        C0,439.296,72.96,512,162.24,512h186.464c89.28,0,161.76-72.704,162.272-161.504L512,219.84C512,219.84,512,203.68,498.944,198.08
                        z M164.288,132.256h89.984c17.152,0,31.072,13.856,31.072,30.784c0,16.96-13.92,30.944-31.072,30.944h-89.984
                        c-17.152,0-31.072-14.016-31.072-30.944C133.216,146.08,147.136,132.256,164.288,132.256z M347.168,378.912h-182.88
                        c-17.152,0-31.072-14.016-31.072-30.784c0-16.928,13.92-30.784,31.072-30.784h182.88c16.992,0,30.912,13.856,30.912,30.784
                        C378.08,364.896,364.16,378.912,347.168,378.912z"/></g></g></svg>
                </button>
            </li>
            <?php
            }
            if(array_key_exists('reddit', $social_links)){
            ?>
            <li>
                <button class="social__item sharer" data-sharer="reddit"
                        data-title="<?php echo esc_attr( get_the_title( $post_ID ) ); ?>"
                        data-url="<?php the_permalink( $post_ID ); ?>">
                    <svg class="crumina-icon" fill="#fff" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m21.325 9.308c-.758 0-1.425.319-1.916.816-1.805-1.268-4.239-2.084-6.936-2.171l1.401-6.406 4.461 1.016c0 1.108.89 2.013 1.982 2.013 1.113 0 2.008-.929 2.008-2.038s-.889-2.038-2.007-2.038c-.779 0-1.451.477-1.786 1.129l-4.927-1.108c-.248-.067-.491.113-.557.365l-1.538 7.062c-2.676.113-5.084.928-6.895 2.197-.491-.518-1.184-.837-1.942-.837-2.812 0-3.733 3.829-1.158 5.138-.091.405-.132.837-.132 1.268 0 4.301 4.775 7.786 10.638 7.786 5.888 0 10.663-3.485 10.663-7.786 0-.431-.045-.883-.156-1.289 2.523-1.314 1.594-5.115-1.203-5.117zm-15.724 5.41c0-1.129.89-2.038 2.008-2.038 1.092 0 1.983.903 1.983 2.038 0 1.109-.89 2.013-1.983 2.013-1.113.005-2.008-.904-2.008-2.013zm10.839 4.798c-1.841 1.868-7.036 1.868-8.878 0-.203-.18-.203-.498 0-.703.177-.18.491-.18.668 0 1.406 1.463 6.07 1.488 7.537 0 .177-.18.491-.18.668 0 .207.206.207.524.005.703zm-.041-2.781c-1.092 0-1.982-.903-1.982-2.011 0-1.129.89-2.038 1.982-2.038 1.113 0 2.008.903 2.008 2.038-.005 1.103-.895 2.011-2.008 2.011z"/></svg>
                </button>
            </li>
            <?php
            }
            if(array_key_exists('viber', $social_links)){
            ?>
            <li>
                <button class="social__item sharer" data-sharer="viber"
                        data-title="<?php echo esc_attr( get_the_title( $post_ID ) ); ?>"
                        data-url="<?php the_permalink( $post_ID ); ?>">
                    <svg class="crumina-icon" fill="#fff" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 322 322" style="enable-background:new 0 0 322 322;" xml:space="preserve">
                        <g id="XMLID_7_">
                            <path id="XMLID_8_" d="M275.445,135.123c0.387-45.398-38.279-87.016-86.192-92.771c-0.953-0.113-1.991-0.285-3.09-0.467
                                c-2.372-0.393-4.825-0.797-7.3-0.797c-9.82,0-12.445,6.898-13.136,11.012c-0.672,4-0.031,7.359,1.902,9.988
                                c3.252,4.422,8.974,5.207,13.57,5.836c1.347,0.186,2.618,0.359,3.682,0.598c43.048,9.619,57.543,24.742,64.627,67.424
                                c0.173,1.043,0.251,2.328,0.334,3.691c0.309,5.102,0.953,15.717,12.365,15.717h0.001c0.95,0,1.971-0.082,3.034-0.244
                                c10.627-1.615,10.294-11.318,10.134-15.98c-0.045-1.313-0.088-2.555,0.023-3.381C275.429,135.541,275.444,135.332,275.445,135.123z
                                "/>
                            <path id="XMLID_9_" d="M176.077,25.688c1.275,0.092,2.482,0.18,3.487,0.334c70.689,10.871,103.198,44.363,112.207,115.605
                                c0.153,1.211,0.177,2.688,0.202,4.252c0.09,5.566,0.275,17.145,12.71,17.385l0.386,0.004c3.9,0,7.002-1.176,9.221-3.498
                                c3.871-4.049,3.601-10.064,3.383-14.898c-0.053-1.186-0.104-2.303-0.091-3.281C318.481,68.729,255.411,2.658,182.614,0.201
                                c-0.302-0.01-0.59,0.006-0.881,0.047c-0.143,0.021-0.408,0.047-0.862,0.047c-0.726,0-1.619-0.063-2.566-0.127
                                C177.16,0.09,175.862,0,174.546,0c-11.593,0-13.797,8.24-14.079,13.152C159.817,24.504,170.799,25.303,176.077,25.688z"/>
                            <path id="XMLID_10_" d="M288.36,233.703c-1.503-1.148-3.057-2.336-4.512-3.508c-7.718-6.211-15.929-11.936-23.87-17.473
                                c-1.648-1.148-3.296-2.297-4.938-3.449c-10.172-7.145-19.317-10.617-27.957-10.617c-11.637,0-21.783,6.43-30.157,19.109
                                c-3.71,5.621-8.211,8.354-13.758,8.354c-3.28,0-7.007-0.936-11.076-2.783c-32.833-14.889-56.278-37.717-69.685-67.85
                                c-6.481-14.564-4.38-24.084,7.026-31.832c6.477-4.396,18.533-12.58,17.679-28.252c-0.967-17.797-40.235-71.346-56.78-77.428
                                c-7.005-2.576-14.365-2.6-21.915-0.06c-19.02,6.394-32.669,17.623-39.475,32.471C2.365,64.732,2.662,81.578,9.801,99.102
                                c20.638,50.666,49.654,94.84,86.245,131.293c35.816,35.684,79.837,64.914,130.839,86.875c4.597,1.978,9.419,3.057,12.94,3.844
                                c1.2,0.27,2.236,0.5,2.991,0.707c0.415,0.113,0.843,0.174,1.272,0.178l0.403,0.002c0.001,0,0,0,0.002,0
                                c23.988,0,52.791-21.92,61.637-46.91C313.88,253.209,299.73,242.393,288.36,233.703z"/>
                            <path id="XMLID_11_" d="M186.687,83.564c-4.107,0.104-12.654,0.316-15.653,9.021c-1.403,4.068-1.235,7.6,0.5,10.498
                                c2.546,4.252,7.424,5.555,11.861,6.27c16.091,2.582,24.355,11.48,26.008,28c0.768,7.703,5.955,13.082,12.615,13.082h0.001
                                c0.492,0,0.995-0.029,1.496-0.09c8.01-0.953,11.893-6.838,11.542-17.49c0.128-11.117-5.69-23.738-15.585-33.791
                                C209.543,88.98,197.574,83.301,186.687,83.564z"/>
                        </g>
                    </svg>
                </button>
            </li>
            <?php
            }
            if(array_key_exists('pinterest', $social_links)){
            ?>
            <li>
                <button class="social__item sharer" data-sharer="pinterest"
                        data-title="<?php echo esc_attr( get_the_title( $post_ID ) ); ?>"
                        data-url="<?php the_permalink( $post_ID ); ?>">
                    <svg class="crumina-icon" fill="#fff" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m12.326 0c-6.579.001-10.076 4.216-10.076 8.812 0 2.131 1.191 4.79 3.098 5.633.544.245.472-.054.94-1.844.037-.149.018-.278-.102-.417-2.726-3.153-.532-9.635 5.751-9.635 9.093 0 7.394 12.582 1.582 12.582-1.498 0-2.614-1.176-2.261-2.631.428-1.733 1.266-3.596 1.266-4.845 0-3.148-4.69-2.681-4.69 1.49 0 1.289.456 2.159.456 2.159s-1.509 6.096-1.789 7.235c-.474 1.928.064 5.049.111 5.318.029.148.195.195.288.073.149-.195 1.973-2.797 2.484-4.678.186-.685.949-3.465.949-3.465.503.908 1.953 1.668 3.498 1.668 4.596 0 7.918-4.04 7.918-9.053-.016-4.806-4.129-8.402-9.423-8.402z"/></svg>
                </button>
            </li>
            <?php
            }
            if(array_key_exists('tumblr', $social_links)){
            ?>
            <li>
                <button class="social__item sharer" data-sharer="tumblr"
                        data-title="<?php echo esc_attr( get_the_title( $post_ID ) ); ?>"
                        data-url="<?php the_permalink( $post_ID ); ?>">
                    <svg class="crumina-icon" fill="#fff" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m19 22.594-1.175-3.425c-.458.214-1.327.399-1.968.419h-.091c-1.863 0-2.228-1.37-2.244-2.371v-7.47h4.901v-3.633h-4.883v-6.114h-3.575c-.059 0-.162.051-.176.179-.202 1.873-1.098 5.156-4.789 6.469v3.099h2.456v7.842c0 2.655 1.97 6.411 7.148 6.411l-.011-.002h.181c1.786-.03 3.783-.768 4.226-1.404z"/></svg>
                </button>
            </li>
            <?php
            }
            if(array_key_exists('xing', $social_links)){
            ?>
            <li>
                <button class="social__item sharer" data-sharer="xing"
                        data-title="<?php echo esc_attr( get_the_title( $post_ID ) ); ?>"
                        data-url="<?php the_permalink( $post_ID ); ?>">
                        <svg class="crumina-icon" fill="#fff" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                        <g>
                            <g>
                                <polygon points="496,0 376.384,0 198.688,311.264 313.184,512 432.8,512 318.304,311.264 		"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <polygon points="149.216,96 36.448,96 101.696,210.912 16,352 128.768,352 214.464,210.912 		"/>
                            </g>
                        </g>
                        </svg>
                </button>
            </li>
            <?php
            }
            if(array_key_exists('myspace', $social_links)){
            ?>
            <li>
                <button class="social__item sharer" data-sharer="myspace"
                        data-title="<?php echo esc_attr( get_the_title( $post_ID ) ); ?>"
                        data-url="<?php the_permalink( $post_ID ); ?>">
                    <svg class="crumina-icon" fill="#fff" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M400,224c-31.36,0-59.648,13.024-80,33.856V256c0-52.928-43.072-96-96-96c-31.136,0-58.56,15.136-76.128,38.176
                                    C133.76,175.36,108.736,160,80,160c-44.096,0-80,35.904-80,80v112h128v64h160v96h224V336C512,274.24,461.76,224,400,224z"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <circle cx="400" cy="96" r="96"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <circle cx="224" cy="80" r="64"/>
                            </g>
                        </g>
                        <g>
                            <g>
                                <circle cx="80" cy="96" r="48"/>
                            </g>
                        </g>
                    </svg>
                </button>
            </li>
            <?php
            }
            ?>
        </ul>
    </span>
    <?php
}