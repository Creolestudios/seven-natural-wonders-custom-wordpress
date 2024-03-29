<?php
/**
 * Page template of theme.
 *
 * @version 1.0.0
 * @package akd
 */

get_header();

$page_id            = get_the_ID();
$page_name          = get_the_title( $page_id );
$feature_image      = get_the_post_thumbnail_url( $page_id );
$image_gallary      = get_field( 'image_gallary', $page_id );
$no_image_thumbnail = get_field( 'default_post_thumbnail_image', 'option' );
?>
<main>
	<div class="animaldetail-container">
	<div class="animal-detail-banner-slider-wrapper">
			<div class="banner-inner-title"><?php the_title(); ?></div>
			<?php
			if ( ! empty( $wonder_slider_images ) ) {
				?>
					<div class="animal-detail-banner-slider sldier">
						<?php
						foreach ( $wonder_slider_images as $image ) {
							?>
								<div class="animal-detail-banner-slider-item">
									<img class="img-cover modal-popup" src="<?php echo esc_url( $image ); ?>" alt="animal-gallery">
								</div>
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
								<div class="animal-detail-banner-slider-item">
									<img class="img-cover modal-popup" src="<?php echo esc_url( $feature_image ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $animal_id ) ); ?>">
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
					<h2 class="title-curve black"><?php echo esc_html( 'About ' . $page_name ); ?></h2>
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
	</div>
</main>
<?php
get_footer();
