<?php
/**
 * The template for displaying detail page of wonder.
 *
 * @version 1.0.0
 * @package akd
 */

get_header();

$wonder_id              = get_the_ID();
$wonder_name            = get_the_title( $wonder_id );
$feature_image          = get_the_post_thumbnail_url( $wonder_id );
$wonder_slider_images   = get_field( 'wonder_slider_images', $wonder_id );
$wonder_popup_gallery   = get_field( 'wonder_popup_gallery', $wonder_id );
$no_image_thumbnail     = get_field( 'default_post_thumbnail_image', 'option' );
$wonder_faqs            = get_field( 'faqs_wonders', $wonder_id );
$sources                = get_field( 'sources', $wonder_id );
$wonder_characteristics = get_field( 'wonder_characteristics', $species_id );
$sidebar_image          = ! empty( get_field( 'sidebar_image' ) ) ? get_field( 'sidebar_image' ) : '';
$no_image_thumbnail     = get_field( 'default_post_thumbnail_image', 'option' );

// For related wonders.
$child_term_ids = $child_term_names = [];
$terms          = get_the_terms( $wonder_id, 'wonder-categories' );
foreach ( $terms as $term ) {
	if ( $term->parent == 0 ) {
		$parent_term_id   = $term->term_id;
		$parent_term_name = $term->name;
	} else {
		$child_term_ids[]   = $term->term_id;
		$child_term_names[] = $term->name;
		$parent_term_id     = $term->parent;
		$parent_term        = get_term_by( 'id', $parent_term_id, 'wonder-categories' );
		$parent_term_name   = $parent_term->name;
	}
}

// Get posts from parent categories.
$parent_query = new WP_Query(
	[
		'post_type'      => 'wonders',
		'tax_query'      => [
			[
				'taxonomy'         => 'wonder-categories',
				'field'            => 'id',
				'terms'            => $parent_term_id,
				'include_children' => false,
			],
		],
		'post__not_in'   => [ $wonder_id ],
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'fields'         => 'ids',
	]
);

// Get posts from child categories.
$child_query = new WP_Query(
	[
		'post_type'      => 'wonders',
		'tax_query'      => [
			[
				'taxonomy' => 'wonder-categories',
				'field'    => 'id',
				'terms'    => $child_term_ids,
			],
		],
		'post__not_in'   => array_merge( [ $wonder_id ], $parent_query->posts ),
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'fields'         => 'ids',
	]
);

$related_wonders = array_merge( $parent_query->posts, $child_query->posts );
?>
<main>
	<div class="animaldetail-container">
			<!-- <div class="banner-inner-page green">
			</div> -->
		<div class="animal-detail-banner-slider-wrapper">
			<div class="banner-inner-title"><?php the_title(); ?></div>
			<?php
			if ( ! empty( $wonder_slider_images ) ) {
				?>
					<div class="animal-detail-banner-slider sldier">
						<?php
						foreach ( $wonder_slider_images as $image ) {
							?>
								<a class="animal-detail-banner-slider-item image-popup-vertical-fit" href="<?php echo esc_url( $image ); ?>">
									<img class="img-cover" src="<?php echo esc_url( $image ); ?>" alt="animal-gallery">
								</a>
							<?php
						}
						?>
					</div>
				<?php
			} else {
				?>
					<div class="animal-detail-banner-slider sldier">
						<?php
						if ( ! empty( $feature_image ) ) {
							?>
								<div class="animal-detail-banner-slider-item modal-popup">
									<img class="img-cover" src="<?php echo esc_url( $feature_image ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $animal_id ) ); ?>">
								</div>
							<?php
						} else {
							?>
								<div class="animal-detail-banner-slider-item">
									<img class="img-cover" src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="thumbnail-image">
								</div>
							<?php
						}
						?>
					</div>
				<?php
			}
			?>
		</div>

		<?php echo do_shortcode( '[ads_section_layout layout="horizontal-banner"]' ); ?>

		<div class="container">
			<div class="about-animal-detail-wrapper">
				<div class="about-animal-detail">
					<h2 class="title-curve black"><?php echo esc_html( 'About ' . $wonder_name ); ?></h2>
					<div class="animal-content">
						<?php the_content(); ?>
					</div>
				</div>
				<div class="about-animal-detail-sidebar">
					<?php
					if ( ! empty( $sidebar_image ) ) {
						?>
							<img src="<?php echo esc_url( $sidebar_image ); ?>" alt="">
						<?php
					}
					?>
				</div>
			</div>
		</div>

		<?php
		if ( ! empty( $wonder_characteristics ) ) {
			?>
				<div class="species-characteristics-detail">
					<div class="container">
						<div class="species-characteristics-left">
							<section class="tabs-wrapper">
								<div class="tabs-container">
									<div class="tabs-block">
										<div class="tabs">
											<?php
											$tab_index = 1;
											foreach ( $wonder_characteristics as $detail ) {
												$characteristic        = $detail['characteristic'];
												$characteristic_detail = $detail['characteristic_detail'];
												$image                 = $detail['image'];
												$tab_check             = $tab_index == 1 ? 'checked="checked"' : '';
												$tab_class             = empty( $image ) ? 'full-tab' : '';
												?>
													<input type="radio" name="tabs" id="tab<?php echo esc_attr( $tab_index ); ?>" <?php echo esc_attr( $tab_check ); ?>/>
													<label for="tab<?php echo esc_attr( $tab_index ); ?>"><?php echo esc_html( $characteristic ); ?></label>
													<div class="tab">
														
														<?php
														if ( ! empty( $image ) ) {
															?>
																<div class="species-characteristic-tab-right">
																	<div class="jagged modal-popup" style="background-image: url(<?php echo esc_url( $image ); ?>)"> </div>
																	<!-- <img class="img-cover modal-popup" src="<?php echo esc_url( $image ); ?>" alt="wonder-image"> -->
																</div>
															<?php
														}
														?>
														<div class="species-characteristic-tab-left <?php echo esc_attr( $tab_class ); ?>">
															<h2 class="title-curve black-light"><?php echo esc_html( $characteristic ); ?></h2>
															<p><?php echo wp_kses_post( $characteristic_detail ); ?></p>
														</div>
													</div>
												<?php
												$tab_index++;
											}
											?>
										</div>
									</div>
								</div>
							</section>
						</div>
						<div class="species-characteristics-right">
							<div class="add-banner vertical">
								<img class="img-cover" src="<?php echo esc_url( AKD_THEME_URI . '/dist/assets/images/addbanner.png' ); ?>" alt="advertisement banner">
								<img class="img-cover" src="<?php echo esc_url( AKD_THEME_URI . '/dist/assets/images/addbanner.png' ); ?>" alt="advertisement banner">
							</div>
						</div>
					</div>
				</div>
			<?php
		}
		?>

		<?php
		// Wonder Gallery.
		if ( ! empty( $wonder_popup_gallery ) ) {
			$gallery_class = count( $wonder_popup_gallery ) == 3 ? 'picture-gallery' : '';
			?>
				<div class="see-all-picture-wrapper <?php echo esc_attr( $gallery_class ); ?> ">
					<div class="container">
						<h2 class="title-curve white"><?php echo esc_html( $species_name . ' Pictures' ); ?></h2>
						<div class="see-all-picture-item-wrapper img-gallery-magnific">
							<?php
							foreach ( $wonder_popup_gallery as $key => $image ) {
								?>
									<div class="magnific-img">
										<a class="image-popup-vertical-fit" href="<?php echo esc_url( $image ); ?>" title="">
											<div class="see-all-picture-item">
												<div class="jagged brown" style="background-image: url(<?php echo esc_url( $image ); ?>)"> </div>
											</div>
										</a>
									</div>
									<?php
							}
							?>
						</div>
						<div class="btn-wrapper">
							<a href="#" class="btn btn-white see-all-pictures"><?php echo esc_html( 'See All Pictures' ); ?></a>
						</div>
					</div>
				</div>
			<?php
		}
		?>

		<?php
		// Wonder FAQ's.
		if ( ! empty( $wonder_faqs ) ) {
			?>
				<div class="faq-wrapper">
					<div class="container">
						<h2 class="title-curve brown"><?php echo esc_html( 'FAQ’s' ); ?></h2>
						<div class="faqs">
							<?php
							$faq_index = 1;
							foreach ( $wonder_faqs as $faq ) {
								$question = $faq['question'];
								$answer   = $faq['answer'];
								$active   = $faq_index == 1 ? 'active' : '';
								?>
									<div class="faq <?php echo esc_attr( $active ); ?>">
										<div class="header">
											<h3><?php echo esc_html( $faq_index . '. ' . $question ); ?></h3>
											<div class="arrow"></div>
										</div>
										<div class="content">
											<p><?php echo wp_kses_post( $answer ); ?></p>
										</div>
									</div>
								<?php
								$faq_index++;
							}
							?>
						</div>
					</div>
				</div>
			<?php
		}

		// Related Continent Level Wonders.
		if ( $parent_query->posts ) {
			?>
				<div class="explore-the-diversity">
					<div class="container">
						<h2 class="title-curve black"><?php echo esc_html( '7 Natural Wonders of ' . $parent_term_name ); ?></h2>
						<div class="explore-the-diversity-card-wrapper">
							<?php
							foreach ( $parent_query->posts as $related_wonder_id ) {
								// $related_wonders->the_post();
								// $related_wonder_id    = get_the_ID();
								$related_wonder_name  = get_the_title( $related_wonder_id );
								$related_wonder_image = get_the_post_thumbnail_url( $related_wonder_id, 'large' );
								$related_wonder_url   = get_the_permalink( $related_wonder_id );

								empty( $related_wonder_image ) ? $related_wonder_image = $no_image_thumbnail : $related_wonder_image = $related_wonder_image;
								?>
									<a href="<?php echo esc_url( $related_wonder_url ); ?>" class="explore-the-diversity-card-item">
										<div class="explore-the-diversity-card-img-wrapper">
											<img class="img-cover" src="<?php echo esc_url( $related_wonder_image ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $related_wonder_id ) ); ?>">
										</div>
										<h5 class="explore-the-diversity-card-content white"><?php echo esc_html( $related_wonder_name ); ?></h5>
									</a>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			<?php
		}

		// Related Country Level Wonders.
		if ( $child_query->posts ) {
			?>
				<div class="explore-the-diversity">
					<div class="container">
						<h2 class="title-curve black"><?php echo esc_html( 'Wonders of ' . implode( ', ', $child_term_names ) ); ?></h2>
						<div class="explore-the-diversity-card-wrapper">
							<?php
							foreach ( $child_query->posts as $related_wonder_id ) {
								$related_wonder_name  = get_the_title( $related_wonder_id );
								$related_wonder_image = get_the_post_thumbnail_url( $related_wonder_id, 'large' );
								$related_wonder_url   = get_the_permalink( $related_wonder_id );

								empty( $related_wonder_image ) ? $related_wonder_image = $no_image_thumbnail : $related_wonder_image = $related_wonder_image;
								?>
									<a href="<?php echo esc_url( $related_wonder_url ); ?>" class="explore-the-diversity-card-item">
										<div class="explore-the-diversity-card-img-wrapper">
											<img class="img-cover" src="<?php echo esc_url( $related_wonder_image ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $related_wonder_id ) ); ?>">
										</div>
										<h5 class="explore-the-diversity-card-content white"><?php echo esc_html( $related_wonder_name ); ?></h5>
									</a>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			<?php
		}

		// Content sources.
		if ( ! empty( $sources ) ) {
			?>
				<div class="source-wrapper">
					<div class="container">
						<h6><?php echo esc_html( 'Sources' ); ?></h6>
						<ul>
							<?php
							foreach ( $sources as $detail ) {
								?>
									<li><?php echo esc_html( $detail['detail'] ); ?></li>
								<?php
							}
							?>
						</ul>
					</div>
				</div>
			<?php
		}

		// Gallery popup.
		if ( ! empty( $wonder_popup_gallery ) ) {
			?>
				<div class="animal-gallery-popup-main" style="display:none">
					<div class="animal-gallery-popup-wrapper">
						<div>
							<a href="javascript:void(0)" class="close"></a>
							<div class="slider slider-for">
								<?php
								foreach ( $wonder_popup_gallery as $image ) {
									?>
										<div><img src="<?php echo esc_url( $image ); ?>" alt="gallery-image"></div>
									<?php
								}
								?>
							</div>
							<!-- <div class="slider slider-nav">
								<?php
								foreach ( $wonder_popup_gallery as $image ) {
									?>
										<div><img src="<?php echo esc_url( $image ); ?>" alt="gallery-image"></div>
									<?php
								}
								?>
							</div> -->
						</div>
					</div>
				</div>
			<?php
		}
		?>
	</div>
</main>
<?php
get_footer();
