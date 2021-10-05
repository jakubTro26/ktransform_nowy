<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $full_width
 * @var $full_height
 * @var $content_placement
 * @var $parallax
 * @var $parallax_image
 * @var $css
 * @var $el_id
 * @var $video_bg
 * @var $video_bg_url
 * @var $video_bg_parallax
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Row
 */
$el_class = $el_class_2 = $full_height = $full_width = $content_placement = $parallax = $parallax_image = $css = $el_id = $video_bg = $video_bg_url = $video_bg_parallax = '';
$output = $after_output = $container = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

wp_enqueue_script( 'wpb_composer_front_js' );

$el_class = $this->getExtraClass( $el_class );

$css_classes = array(
	'vc_row',
	'wpb_row', //deprecatedkuba
	'vc_row-fluid',
	$el_class,
	$el_class_2,
	vc_shortcode_custom_css_class( $css ),
);
$wrapper_attributes = array();
// build attributes for wrapper
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $full_width ) ) {
	$wrapper_attributes[] = 'data-vc-full-width="true"';
	$wrapper_attributes[] = 'data-vc-full-width-init="false"';
	if ( 'stretch_row_content' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
	} elseif ( 'stretch_row_content_no_spaces' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
		$css_classes[] = 'vc_row-no-padding';
	}
	$after_output .= '<div class="vc_row-full-width"></div>';
}

if ( ! empty( $full_height ) ) {
	$css_classes[] = ' vc_row-o-full-height';
	if ( ! empty( $content_placement ) ) {
		$css_classes[] = ' vc_row-o-content-' . $content_placement;
	}
}

$has_video_bg = ( ! empty( $video_bg ) && ! empty( $video_bg_url ) && vc_extract_youtube_id( $video_bg_url ) );

if ( $has_video_bg ) {
	$parallax = $video_bg_parallax;
	$parallax_image = $video_bg_url;
	$css_classes[] = ' vc_video-bg-container';
	wp_enqueue_script( 'vc_youtube_iframe_api_js' );
}

if ( ! empty( $parallax ) ) {
	wp_enqueue_script( 'vc_jquery_skrollr_js' );
	$wrapper_attributes[] = 'data-vc-parallax="1.5"'; // parallax speed
	$css_classes[] = 'vc_general vc_parallax vc_parallax-' . $parallax;
	if ( false !== strpos( $parallax, 'fade' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fade';
		$wrapper_attributes[] = 'data-vc-parallax-o-fade="on"';
	} elseif ( false !== strpos( $parallax, 'fixed' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fixed';
	}
}

if ( ! empty( $parallax_image ) ) {
	if ( $has_video_bg ) {
		$parallax_image_src = $parallax_image;
	} else {
		$parallax_image_id = preg_replace( '/[^\d]/', '', $parallax_image );
		$parallax_image_src = wp_get_attachment_image_src( $parallax_image_id, 'full' );
		if ( ! empty( $parallax_image_src[0] ) ) {
			$parallax_image_src = $parallax_image_src[0];
		}
	}
	$wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( $parallax_image_src ) . '"';
}
if ( ! $parallax && $has_video_bg ) {
	$wrapper_attributes[] = 'data-vc-video-bg="' . esc_attr( $video_bg_url ) . '"';
}
$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';

if(! empty($container)):
	if('container' === $container):
		$output .= '<div class="container">';
	endif;
endif;




if(strpos($output,'vc_custom_1524500212394') ){

	?>


<div id="schedule" class="vc_row wpb_row vc_row-fluid vc_custom_1524500212394">
	<div class="div1">
		<div class="wpb_column vc_column_container vc_col-sm-12">
			<div class="vc_column-inner ">
				<div class="wpb_wrapper">
					<div class="jx-ievent-section-title-1 jx-ievent-dark">
						<div class="jx-ievent-pre-title jx-ievent-short-border">
							<div class="jx-ievent-title-border left">
							</div>
							<div class="jx-ievent-title-icon"><i class="line-icon vc_li vc_li-calendar"></i>
							</div>
							<div class="jx-ievent-title-border right">
							</div> 
						</div>
						<div class="jx-ievent-title jx-ievent-uppercase">AGENDA
						</div>
						<div class="jx-ievent-subtitle">
							<p>Dzień pełen inspiracji! Szeroka tematyka konferencji pozwoli na uzyskanie wiedzy „w pigułce”
							</p>
						</div>
						<!-- Section Title -->
					</div>
					<div class="shortcode_tab_e jx-ievent-white-tab jx-ievent-arrow-tab">			
						<div id="ParentTab" style="display: block; width: 100%; margin: 0px;">
							<ul class="resp-tabs-list parenttab_1">
								<li class="resp-tab-item full-width parenttab_1 resp-tab-active" aria-controls="parenttab_1_tab_item-0" role="tab">
									<div class="jx-ievent-tab-date jx-ievent-uppercase">Kongres Cyfrowa Transformacja
									</div>
									<div class="jx-ievent-tab-day jx-ievent-uppercase">10/12/2020
									</div>
								</li>
							</ul>
							<div class="resp-tabs-container parenttab_1">
								<h2 class="resp-accordion parenttab_1 resp-tab-active" role="tab" aria-controls="parenttab_1_tab_item-0" style="background: none;">
									<span class="resp-arrow">
									</span>
									<div class="jx-ievent-tab-date jx-ievent-uppercase">Kongres Cyfrowa Transformacja
									</div>
									<div class="jx-ievent-tab-day jx-ievent-uppercase">10/12/2020
									</div>
								
									<div class="jx-ievent-tab-date jx-ievent-uppercase">Kongres Cyfrowa Transformacja
									</div>
									<div class="jx-ievent-tab-day jx-ievent-uppercase">10/12/2020
									</div>
								</h2>
								<h2 class="resp-accordion parenttab_1" role="tab" aria-controls="parenttab_1_tab_item-1">
									<span class="resp-arrow"> 
									</span>
									<div class="jx-ievent-tab-title">Agendaa
									</div>
								</h2>
								<div class="resp-tab-content parenttab_1 resp-tab-content-active" aria-labelledby="parenttab_1_tab_item-0" style="display:block">
									<div id="ChildTab-1" style="display: block; width: 100%; margin: 0px;">
										<ul class="resp-tabs-list jx-ievent-subtab childtab_1 ">
											<li class="resp-tab-item childtab_1 resp-tab-active" aria-controls="childtab_1_tab_item-0" role="tab">
												<div class="jx-ievent-tab-title">Agenda
												</div>
											</li>
										</ul><!-- EOF Child Tab Head -->					
										<div class="resp-tabs-container jx-ievent-event-schedule childtab_1">
											<h2 class="resp-accordion childtab_1 resp-tab-active" role="tab" aria-controls="childtab_1_tab_item-0" style="background: none;">
												<span class="resp-arrow">
												</span>
												<div class="jx-ievent-tab-title">Agenda
												</div>
											
												<div class="jx-ievent-tab-title">Agenda
												</div>
											</h2>
											<h2 class="resp-accordion childtab_1" role="tab" aria-controls="childtab_1_tab_item-1"><span class="resp-arrow"></span></h2>
												<div class="resp-tab-content childtab_1 resp-tab-content-active" aria-labelledby="childtab_1_tab_item-0" style="display:block">
													<div data-accordion-group="" class="jx-ievent-accordion-box">
														<div class="item"> 					  
															<div class="left-position">
																<div class="image"><img width="920" height="920" src="https://kongrestransformacji.pl/wp-content/uploads/2021/09/image-72.png"
																 class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" 
																 loading="lazy" srcset="https://kongrestransformacji.pl/wp-content/uploads/2021/09/image-72.png 920w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2021/09/image-72.png 300w,
																 https://kongrestransformacji.pl/wp-content/uploads/2021/09/image-72.png 150w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2021/09/image-72.png 768w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2021/09/image-72.png 400w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2021/09/image-72.png 200w" 
																  sizes="(max-width: 920px) 100vw, 920px">
																</div>
																<!-- Image -->
															</div>
																									<!-- Left item Position -->
										
															<div class="right-position">					
																<div data-accordion="" class="head">
																	<div class="date"><i class="fa fa-clock-o"></i> 
																		<span>9:30 - 10:00
																		</span> Robotyzacja procesów biznesowych - dobre praktyki w kolejnych etapach rozwoju automatyzacji w organizacjach
																	</div>
																	<div class="title" data-control="">Rafał Górski - KPMG
																	</div>                        
																				<!-- Title -->
																	<div data-content2="">
																		<div class="content">
																			<p></p>
																		</div>
																	</div>
																			<!-- Content -->
													
																</div>							
															</div>
														</div>	
														<div class="item"> 					  
															<div class="left-position">
																<div class="image"><img width="920" height="920" src="https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg" 
																class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" loading="lazy" 
																srcset="https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 920w,
																https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 300w, 
																https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 150w,
																https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 768w,
																https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 400w,
																https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 200w" 
																	sizes="(max-width: 920px) 100vw, 920px">
																</div>
																<!-- Image -->
															</div>
																									<!-- Left item Position -->
										
															<div class="right-position">					
																<div data-accordion="" class="head">
																	<div class="date"><i class="fa fa-clock-o"></i> 
																		<span>10:00 - 10:30
																		</span> Wystąpienie zarezerwowane
																	</div>
																	<div class="title" data-control="">
																	</div>                        
																				<!-- Title -->
																	<div data-content2="">
																		<div class="content">
																			<p></p>
																		</div>
																	</div>
																			<!-- Content -->
													
																</div>							
															</div>
														</div>	
														<div class="item"> 					  
															<div class="left-position">
																<div class="image"><img width="920" height="920" src="https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg" 
																class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" loading="lazy" 
																srcset="https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 920w, 
																https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 300w,
																https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 150w, 
																https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 768w,
																https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 400w,
																https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 200w"
																    sizes="(max-width: 920px) 100vw, 920px">
																</div>
																<!-- Image -->
															</div>
																									<!-- Left item Position -->
										
															<div class="right-position">					
																<div data-accordion="" class="head">
																	<div class="date"><i class="fa fa-clock-o"></i> 
																		<span>10:30 - 11:00
																		</span> Wystąpienie Partnera
																	</div>
																	<div class="title" data-control="">
																	</div>                        
																				<!-- Title -->
																	<div data-content2="">
																		<div class="content">
																			<p></p>
																		</div>
																	</div>
																			<!-- Content -->
													
																</div>							
															</div>
														</div>	
														<div class="item"> 					  
															<div class="left-position">
																<div class="image"><img width="920" height="920" 
																src="https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png" 
																class="attachment-post-thumbnail size-post-thumbnail wp-post-image" 
																alt="" loading="lazy" 
																srcset="https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 920w,
																https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 300w,
																https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 150w,
																https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 768w, 
																https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 400w,
																https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 200w" 
																	sizes="(max-width: 920px) 100vw, 920px">
																</div>
																<!-- Image -->
															</div>
																									<!-- Left item Position -->
										
															<div class="right-position">					
																<div data-accordion="" class="head">
																	<div class="date"><i class="fa fa-clock-o"></i> 
																		<span>11:00 - 11:10 
																		</span> Przerwa
																	</div>
																	<div class="title" data-control="">
																	</div>                        
																				<!-- Title -->
																	<div data-content2="">
																		<div class="content">
																			<p></p>
																		</div>
																	</div>
																			<!-- Content -->
													
																</div>							
															</div>
														</div>	
														<div class="item"> 					  
															<div class="left-position">
																<div class="image"><img width="920" height="920" src="https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg"
																 class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" loading="lazy" 
																 srcset="https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 920w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 300w,
																 https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 150w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 768w,
																 https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 400w,
																 https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 200w"
																	 sizes="(max-width: 920px) 100vw, 920px">
																</div>
																<!-- Image f-->
															</div>
																									<!-- Left item Position -->
										
														</div>	
														<div class="item"> 					  
															<div class="left-position">
																<div class="image"><img width="920" height="920" src="https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg"
																 class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" loading="lazy" 
																 srcset="https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 920w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 300w,
																 https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 150w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 768w,
																 https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 400w,
																 https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 200w"
																	 sizes="(max-width: 920px) 100vw, 920px">
																</div>
																<!-- Image -->
															</div>
																									<!-- Left item Position -->
										
															<div class="right-position">					
																<div data-accordion="" class="head">
																	<div class="date"><i class="fa fa-clock-o"></i> 
																		<span>11:10 - 11:40
																		</span> Wystąpienie zarezerwowane
																	</div>
																	<div class="title" data-control="">Magdalena Chudzikiewicz - home.pl
																	</div>                        
																				<!-- Title -->
																	<div data-content2="">
																		<div class="content">
																			<p></p>
																		</div>
																	</div>
																			<!-- Content -->
													
																</div>							
															</div>
														</div>	
														<div class="item"> 					  
															<div class="left-position">
																<div class="image"><img width="920" height="920" src="https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg"
																 class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" loading="lazy" 
																 srcset="https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 920w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 300w,
																 https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 150w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 768w,
																 https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 400w,
																 https://kongrestransformacji.pl/wp-content/uploads/2020/05/logopppp11.jpg 200w"
																	 sizes="(max-width: 920px) 100vw, 920px">
																</div>
																<!-- Image -->
															</div>
																									<!-- Left item Position -->
										
															<div class="right-position">					
																<div data-accordion="" class="head">
																	<div class="date"><i class="fa fa-clock-o"></i> 
																		<span>11:40 - 12:10
																		</span> Wystąpienie Partnera
																	</div>
																	<div class="title" data-control="">
																	</div>                        
																				<!-- Title -->
																	<div data-content2="">
																		<div class="content">
																			<p></p>
																		</div>
																	</div>
																			<!-- Content -->
													
																</div>							
															</div>
														</div>	
														<div class="item"> 					  
															<div class="left-position">
																<div class="image"><img width="920" height="920" src="https://kongrestransformacji.pl/wp-content/uploads/2021/10/image-30.png"
																 class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" loading="lazy" 
																 srcset="https://kongrestransformacji.pl/wp-content/uploads/2021/10/image-30.png 920w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2021/10/image-30.png 300w,
																 https://kongrestransformacji.pl/wp-content/uploads/2021/10/image-30.png 150w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2021/10/image-30.png 768w,
																 https://kongrestransformacji.pl/wp-content/uploads/2021/10/image-30.png 400w,
																 https://kongrestransformacji.pl/wp-content/uploads/2021/10/image-30.png 200w"
																	 sizes="(max-width: 920px) 100vw, 920px">
																</div>
																<!-- Image -->
															</div>
																									<!-- Left item Position -->
										
															<div class="right-position">					
																<div data-accordion="" class="head">
																	<div class="date"><i class="fa fa-clock-o"></i> 
																		<span>12:10 - 12:40 
																		</span> Wystąpienie zarezerwowane
																	</div>
																	<div class="title" data-control="">Lucyna Młodzianowska - BNP Paribas Bank Polska
																	</div>                        
																				<!-- Title -->
																	<div data-content2="">
																		<div class="content">
																			<p></p>
																		</div>
																	</div>
																			<!-- Content -->
													
																</div>							
															</div>
														</div>	
														<div class="item"> 					  
															<div class="left-position">
																<div class="image"><img width="920" height="920" src="https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png"
																 class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" loading="lazy" 
																 srcset="https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 920w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 300w,
																 https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 150w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 768w,
																 https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 400w,
																 https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 200w"
																	 sizes="(max-width: 920px) 100vw, 920px">
																</div>
																<!-- Image -->
															</div>
																									<!-- Left item Position -->
										
															<div class="right-position">					
																<div data-accordion="" class="head">
																	<div class="date"><i class="fa fa-clock-o"></i> 
																		<span>12:40 - 12:50
																		</span> Przerwa
																	</div>
																	<div class="title" data-control="">
																	</div>                        
																				<!-- Title -->
																	<div data-content2="">
																		<div class="content">
																			<p></p>
																		</div>
																	</div>
																			<!-- Content -->
													
																</div>							
															</div>
														</div>	
														<div class="item"> 					  
															<div class="left-position">
																<div class="image"><img width="920" height="920" src="https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png"
																 class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" loading="lazy" 
																 srcset="https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 920w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 300w,
																 https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 150w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 768w,
																 https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 400w,
																 https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 200w"
																	 sizes="(max-width: 920px) 100vw, 920px">
																</div>
																<!-- Image -->
															</div>
																									<!-- Left item Position -->
										
															<div class="right-position">					
																<div data-accordion="" class="head">
																	<div class="date"><i class="fa fa-clock-o"></i> 
																		<span>12:50 - 13:20
																		</span> Wystąpienie zarezerwowane
																	</div>
																	<div class="title" data-control="">Elwira Pyk - Akademia Leona Koźmińskiego
																	</div>                        
																				<!-- Title -->
																	<div data-content2="">
																		<div class="content">
																			<p></p>
																		</div>
																	</div>
																			<!-- Content -->
													
																</div>							
															</div>
														</div>	
														<div class="item"> 					  
															<div class="left-position">
																<div class="image"><img width="920" height="920" src="https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png"
																 class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" loading="lazy" 
																 srcset="https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 920w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 300w,
																 https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 150w, 
																 https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 768w,
																 https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 400w,
																 https://kongrestransformacji.pl/wp-content/uploads/2018/04/hot-coffee1.png 200w"
																	 sizes="(max-width: 920px) 100vw, 920px">
																</div>
																<!-- Image -->
															</div>
																									<!-- Left item Position -->
										
															<div class="right-position">					
																<div data-accordion="" class="head">
																	<div class="date"><i class="fa fa-clock-o"></i> 
																		<span>11:10 - 11:40
																		</span> Wystąpienie zarezerwowane
																	</div>
																	<div class="title" data-control="">Magdalena Chudzikiewicz - home.pl
																	</div>                        
																				<!-- Title -->
																	<div data-content2="">
																		<div class="content">
																			<p></p>
																		</div>
																	</div>
																			<!-- Content -->
													
																</div>							
															</div>
														</div>					
																		<!-- Item # 1 --> 
														<div class="clearfix">
														</div>
													</div>
												</div>
										</div>
									</div>
								</div>
							
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


	<?php

	

}
else{
	$output .= wpb_js_remove_wpautop( $content );
}








if($container):
	if('container' === $container):
		$output .= '</div>';
	endif;
endif;

$output .= '</div>';
$output .= $after_output;

echo $output;
