<?php
// [ux_banner]
function flatsome_ux_banner( $atts, $content = null ){

	extract( $atts = shortcode_atts( array(
		'_id'                => 'banner-' . rand(),
		'visibility'         => '',
		// Layout.
		'hover'              => '',
		'hover_alt'          => '',
		'alt'                => '',
		'class'              => '',
		'sticky'             => '',
		'height'             => '',
		'height__sm'         => '',
		'height__md'         => '',
		'container_width'    => '',
		'mob_height'         => '', // Deprecated.
		'tablet_height'      => '', // Deprecated.
		// Background.
		'bg'                 => '',
		'parallax'           => '',
		'parallax_style'     => '',
		'slide_effect'       => '',
		'bg_size'            => 'large',
		'bg_color'           => '',
		'bg_overlay'         => '',
		'bg_overlay__sm'     => '',
		'bg_overlay__md'     => '',
		'bg_pos'             => '',
		'effect'             => '',
		// Shape divider.
		'divider_top'            => '',
		'divider_top_height'     => '150px',
		'divider_top_height__sm' => null,
		'divider_top_height__md' => null,
		'divider_top_width'      => '100',
		'divider_top_width__sm'  => null,
		'divider_top_width__md'  => null,
		'divider_top_fill'       => '',
		'divider_top_flip'       => 'false',
		'divider_top_to_front'   => 'false',
		'divider'                => '',
		'divider_height'         => '150px',
		'divider_height__sm'     => null,
		'divider_height__md'     => null,
		'divider_width'          => '100',
		'divider_width__sm'      => null,
		'divider_width__md'      => null,
		'divider_fill'           => '',
		'divider_flip'           => 'false',
		'divider_to_front'       => 'false',
		// Video.
		'video_mp4'          => '',
		'video_ogg'          => '',
		'video_webm'         => '',
		'video_sound'        => 'false',
		'video_loop'         => 'true',
		'youtube'            => '',
		'video_visibility'   => 'hide-for-medium',
		// Border Control.
		'border'             => '',
		'border_color'       => '',
		'border_margin'      => '',
		'border_radius'      => '',
		'border_style'       => '',
		'border_hover'       => '',
		// Deprecated (This is added to Text Box shortcode).
		'animation'          => 'fadeIn',
		'animate'            => '',
		'loading'            => '',
		'animated'           => '',
		'animation_duration' => '',
		'text_width'         => '60%',
		'text_align'         => 'center',
		'text_color'         => 'light',
		'text_pos'           => 'center',
		'parallax_text'      => '',
		'text_bg'            => '',
		'padding'            => '',
		// Link.
		'link'               => '',
		'target'             => '',
		'rel'                => '',
	), $atts ) );

   // Stop if visibility is hidden.
   if($visibility == 'hidden') return;

   ob_start();

	$classes   = array( 'has-hover' );
	$link_atts = array(
		'target' => $target,
		'rel'    => array( $rel ),
	);

   // Custom Class.
   if($class) $classes[] = $class;

   if($animate) {$animation = $animate;}
   if($animated) {$animation = $animated;}

   /* Hover Class */
   if($hover) $classes[] = 'bg-'.$hover;
   if($hover_alt) $classes[] = 'bg-'.$hover_alt;

   /* Has video */
   if($video_mp4 || $video_webm || $video_ogg) { $classes[] = 'has-video'; }

   /* Sticky */
   if($sticky) $classes[] = 'sticky-section';

   /* Banner Effects */
   if($effect) wp_enqueue_style( 'flatsome-effects');

    /* Old bg fallback */
    $atts['bg_color'] = $bg_color;
    if(strpos($bg,'#') !== false){
      $atts['bg_color'] = $bg;
      $bg = false;
    }

    /* Mute if video_sound is 0 (should stay to support old versions have checkbox option for video sound) */
    if ( $video_sound == '0' ) $video_sound = 'false';

    if($bg_overlay && strpos($bg_overlay,'#') !== false){
      $atts['bg_overlay'] = flatsome_hex2rgba($bg_overlay,'0.15');
    }

   /* Full height banner */
   if(strpos($height, '100%') !== false) {
     $classes[] = 'is-full-height';
   }

   /* Slide Effects */
   if($slide_effect) $classes[] = 'has-slide-effect slide-'.$slide_effect;

   /* Visibility */
   if($visibility) $classes[] = $visibility;

   /* Links */
   $start_link = "";
   $end_link = "";

   if($link) {$start_link = '<a class="fill" href="' . esc_url( $link ) . '"' . flatsome_parse_target_rel( $link_atts ) . '>'; $end_link = '</a>';};

   /* Parallax  */
   if($parallax){
      $classes[] = 'has-parallax';
      $parallax = 'data-parallax="-' . esc_attr( $parallax ) . '" data-parallax-container=".banner" data-parallax-background';
   }

   $classes = implode(" ", $classes);

  ?>

  <div class="banner <?php echo esc_attr( $classes ); ?>" id="<?php echo esc_attr( $_id ); ?>">
     <?php if($loading) echo '<div class="loading-spin dark centered"></div>'; ?>
     <div class="banner-inner fill">
        <div class="banner-bg fill" <?php echo $parallax; ?>>
			<?php if ( ! empty( $atts['bg'] ) ) echo wp_get_attachment_image( $atts['bg'], $atts['bg_size'], false, [ 'class' => 'bg' ] ); ?>
            <?php require( __DIR__ . '/commons/video.php' ) ;?>
            <?php if($bg_overlay) echo '<div class="overlay"></div>' ?>
            <?php require( __DIR__ . '/commons/border.php' ) ;?>
            <?php if($effect) echo '<div class="effect-' . esc_attr( $effect ) . ' bg-effect fill no-click"></div>'; ?>
        </div>
		<?php require __DIR__ . '/commons/shape-divider.php'; ?>
        <div class="banner-layers <?php if($container_width !== 'full-width') echo 'container'; ?>">
            <?php echo $start_link; ?><div class="fill banner-link"></div><?php echo $end_link; ?>
            <?php
            // Get Layers
            if (!get_theme_mod('flatsome_fallback', 1) || (has_shortcode( $content, 'text_box' ) || has_shortcode( $content, 'ux_hotspot' ) || has_shortcode( $content, 'ux_image' ))) {
              echo do_shortcode( $content );
            } else {
              $x = '50'; $y = '50';
              if($text_pos !== 'center'){
                $values = explode(' ', $text_pos);
                if($values[0] == 'left' || $values[1] == 'left'){$x = '10';}
                if($values[0] == 'right' || $values[1] == 'right'){$x = '90';}
                if($values[0] == 'far-left' || $values[1] == 'far-left'){$x = '0';}
                if($values[0] == 'far-right' || $values[1] == 'far-right'){$x = '100';}
                if($values[0] == 'top' || $values[1] == 'top'){$y = '10';}
                if($values[0] == 'bottom' || $values[1] == 'bottom'){$y = '90';}
              }
              if($text_bg && !$padding) $padding = '30px 30px 30px 30px';
              $depth = '';
              if($text_bg) $depth = '1';
              echo do_shortcode( '[text_box text_align="'.$text_align.'" parallax="'.$parallax_text.'" animate="'.$animation.'" depth="'.$depth.'" padding="'.$padding.'" bg="'.$text_bg.'" text_color="'.$text_color.'" width="'.intval($text_width).'" width__sm="60%" position_y="'.$y.'" position_x="'.$x.'"]'.$content.'[/text_box]' );
            } ?>
        </div>
      </div>

      <?php
       // Add invisible image if height is not set.
      if(!$height) { ?>
        <div class="height-fix is-invisible"><?php if($bg) echo flatsome_get_image($bg, $bg_size, $alt, true); ?></div>
      <?php } ?>
      <?php
        // Get custom CSS
        $args = array(
          'height' => array(
            'selector' => '',
            'property' => 'padding-top',
          ),
          'bg_overlay' => array(
            'selector' => '.overlay',
            'property' => 'background-color',
          ),
          'bg_color' => array(
            'selector' => '',
            'property' => 'background-color',
          ),
          'bg_pos' => array(
            'selector' => '.bg',
            'property' => 'object-position',
          ),
        );

	  if ( $divider_top ) {
		  $args = array_merge( $args, array(
			  'divider_top_height' => array(
				  'selector' => '.ux-shape-divider--top svg',
				  'property' => 'height',
			  ),
			  'divider_top_width'  => array(
				  'selector' => '.ux-shape-divider--top svg',
				  'property' => '--divider-top-width',
				  'unit'     => '%',
			  ),
			  'divider_top_fill'   => array(
				  'selector' => '.ux-shape-divider--top .ux-shape-fill',
				  'property' => 'fill',
			  ),
		  ) );
	  }

	  if ( $divider ) {
		  $args = array_merge( $args, array(
			  'divider_height' => array(
				  'selector' => '.ux-shape-divider--bottom svg',
				  'property' => 'height',
			  ),
			  'divider_width'  => array(
				  'selector' => '.ux-shape-divider--bottom svg',
				  'property' => '--divider-width',
				  'unit'     => '%',
			  ),
			  'divider_fill'   => array(
				  'selector' => '.ux-shape-divider--bottom .ux-shape-fill',
				  'property' => 'fill',
			  ),
		  ) );
	  }
        echo ux_builder_element_style_tag($_id, $args, $atts);
      ?>
  </div>

<?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('ux_banner', 'flatsome_ux_banner');
