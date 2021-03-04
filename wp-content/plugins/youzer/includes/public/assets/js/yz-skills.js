( function( $ ) {

    'use strict';

    $( document ).ready( function() {

        $( document ).on( 'click', '#yz-skill-button' , function( e ) {

            var current_wg_nbr = $( '.yz-wg-item[data-wg=skills]' ).length + 1;

            if ( current_wg_nbr > yz_maximum_skills  )  {
				// Show Error Message
                $.yz_DialogMsg( 'error', Yz_Skills.items_nbr + yz_maximum_skills );
                return false;
            }

            e.preventDefault();

            var skills_title = $.ukai_form_input( {
                    input_desc      : Yz_Skills.skill_desc_title,
                    cell            : yz_skill_nextCell,
                    option_item     : 'title',
                    options_name    : 'youzer_skills',
                    label_title     : Yz_Skills.bar_title,
                    input_type      : 'text',
                    inner_option    : true
                }),

                skills_color = $.ukai_form_input( {
                    option_item     : 'barcolor',
                    input_desc      : Yz_Skills.skill_desc_color,
                    cell            : yz_skill_nextCell,
                    options_name    : 'youzer_skills',
                    label_title     : Yz_Skills.bar_color,
                    input_type      : 'color',
                    inner_option    : true
                }),

                skills_percent = $.ukai_form_input( {
                    option_item     : 'barpercent',
                    input_desc      : Yz_Skills.skill_desc_percent,
                    cell            : yz_skill_nextCell,
                    options_name    : 'youzer_skills',
                    label_title     : Yz_Skills.bar_percent,
                    input_type      : 'number',
                    input_min       : '1',
                    input_max       : '100',
                    inner_option    : true
                });

            // Add Skill
            $( '<li class="yz-wg-item" data-wg="skills">'+
                skills_title + skills_percent + skills_color
                + '<a class="yz-delete-item"></a></li>'
            ).hide().prependTo( '.yz-wg-skills-options' ).fadeIn( 400 );

            // increase ID number.
            yz_skill_nextCell++;

            // CallBack ColorPicker
            $( '.yz-picker-input' ).wpColorPicker();

            // Check Account Items List
            $.yz_CheckList();

        });

        // ColorPicker
        $( '.yz-picker-input' ).wpColorPicker();

        /**
         * Remove Items.
         */
        $( document ).on( 'click', '.yz-delete-item', function( e ) {
            
            $( this ).parent().fadeOut( function() {

                // Remove Item
                $( this ).remove();

                // Check Widget Items
                $.yz_CheckList();
            
            });

        });

        /**
         * # Check Account Items
         */
        $.yz_CheckList = function() {

            // Check Skills List.
            if ( $( '.yz-wg-skills-options li' )[0] ) {
                $( '.yz-no-skills' ).remove();
            } else if ( ! $( '.yz-no-skills' )[0] ) {
                $( '.yz-wg-skills-options' ).append(
                    '<p class="yz-no-content yz-no-skills">' + Yz_Skills.no_items + '</p>'
                );
            }

        }

        // Check Account Items List.
        $.yz_CheckList();        

    });

})( jQuery );