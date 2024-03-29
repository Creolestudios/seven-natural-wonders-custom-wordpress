<?php
/**
 * Theme archive page.
 *
 * @version 1.0.0
 *
 * @package akd
 */

get_header();

$category_obj           = get_queried_object();
$category_id            = $category_obj->term_id;
$category_name          = $category_obj->name;
$category_description   = get_field( 'additional_description', 'wonder-categories_' . $category_id );
$category_image         = get_field( 'category_image', 'wonder-categories_' . $category_id );
$category_faqs          = get_field( 'faqs_category', 'wonder-categories_' . $category_id );
$default_category_image = get_field( 'default_category_image', 'option' );
$is_parent_category     = $category_obj->parent;

// Page title.
if ( $is_parent_category === 0 ) {
	$page_title        = '7 Natural Wonders of ' . $category_name;
	$parent_categoy_id = $category_id;
	$child_category_id = '';
} else {
	$page_title        = $category_name . ' Wonders';
	$child_category_id = $category_id;
	$parent_categoy_id = $category_obj->parent;
}
$category_image ? $category_image : $category_image = $default_category_image;
?>

<main>
	<div class="blog-container">
		<!-- <div class="banner-inner-page">
			</div> -->
			<div class="animal-detail-banner-slider-wrapper">
				<div class="banner-inner-title"><?php echo esc_html( $page_title ); ?></div>
				<div class="animal-detail-banner-slider sldier">
					<div class="animal-detail-banner-slider-item modal-popup">
						<img class="img-cover" src="<?php echo esc_url( $category_image ); ?>" alt="wonder-image">
					</div>
				</div>
			</div>
		<?php echo do_shortcode( '[ads_section_layout layout="horizontal-banner"]' ); ?>
		<div class="container">
			<div class="about-animal-detail-wrapper about-short-description">
				<div class="about-animal-detail">
					<!-- <h2 class="title-curve black center"><?php echo esc_html( "Natural Wonders of $category_name" ); ?></h2> -->
					<div class="animal-content">
						<p><?php echo wp_kses_post( $category_description ); ?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="blog-list-wrapper">
			<div class="container">
				<?php
				// Initialize arrays.
				$world_category_posts  = [];
				$parent_category_posts = [];
				$child_category_posts  = [];

				// Query to retrieve posts from the world category.
				if ( isset( $child_category_id ) && ! empty( $child_category_id ) ) {
					$world_category_args      = [
						'post_type'      => 'wonders',
						'post_status'    => 'publish',
						'posts_per_page' => -1,
						'orderby'        => 'title',
						'order'          => 'ASC',
						'tax_query'      => [
							'relation' => 'AND',
							[
								'taxonomy' => 'wonder-categories',
								'field'    => 'name',
								'terms'    => 'World',
							],
							[
								'taxonomy' => 'wonder-categories',
								'field'    => 'id',
								'terms'    => $child_category_id,
							],
						],
						'fields'         => 'ids',
					];
					$world_category_posts_qry = new WP_Query( $world_category_args );
					$world_category_posts     = $world_category_posts_qry->posts;
				}

				// Query to retrieve posts from the parent category.
				if ( isset( $parent_categoy_id ) && ! empty( $parent_categoy_id ) ) {
					$parent_category_args = [
						'post_type'      => 'wonders',
						'post_status'    => 'publish',
						'posts_per_page' => -1,
						'orderby'        => 'title',
						'order'          => 'ASC',
						'tax_query'      => [
							'relation' => 'AND',
							[
								'taxonomy'         => 'wonder-categories',
								'field'            => 'id',
								'terms'            => $parent_categoy_id,
								'include_children' => false,
							],
						],
						'fields'         => 'ids',
					];
					if ( ! empty( $child_category_id ) ) {
						$parent_category_args['tax_query'][] = [
							[
								'taxonomy' => 'wonder-categories',
								'field'    => 'id',
								'terms'    => $child_category_id,
							],
						];
					}
					$parent_category_posts_qry = new WP_Query( $parent_category_args );
					$parent_category_posts     = $parent_category_posts_qry->posts;
				}

				// Query to retrieve posts from the child categories.
				if ( isset( $child_category_id ) && ! empty( $child_category_id ) ) {
					$child_category_args  = [
						'post_type'      => 'wonders',
						'post_status'    => 'publish',
						'posts_per_page' => -1,
						'orderby'        => 'title',
						'order'          => 'ASC',
						'tax_query'      => [
							[
								'taxonomy' => 'wonder-categories',
								'field'    => 'id',
								'terms'    => $child_category_id,
							],
						],
						'fields'         => 'ids',
					];
					$child_category_posts = new WP_Query( $child_category_args );
					$child_category_posts = $child_category_posts->posts;
				}

				$all_posts = array_values( array_unique( array_merge( $world_category_posts, $parent_category_posts, $child_category_posts ) ) );
				if ( ! empty( $all_posts ) ) {
					?>
						<div class="blog-list akd-blog-listing">
						<?php
						foreach ( $all_posts as $wonder_id ) {
							$post_url     = get_permalink( $wonder_id );
							$post_title   = get_the_title( $wonder_id );
							$post_image   = get_the_post_thumbnail_url( $wonder_id, 'large' );
							$post_content = get_post_field( 'post_content', $wonder_id );
							$trim_content = wp_trim_words( $post_content, 50, '...' );
							$excerpt      = get_the_excerpt( $wonder_id );
							?>
								<a href="<?php echo esc_url( $post_url ); ?>" class="list">
									<div class="img-content">
									<?php
									if ( $post_image ) {
										?>
												<img src="<?php echo esc_url( $post_image ); ?>" alt="<?php echo esc_attr( akd_get_img_alt( $wonder_id ) ); ?>" />
											<?php
									} else {
										$no_image_thumbnail = get_field( 'default_post_thumbnail_image', 'option' );
										?>
												<img src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
											<?php
									}
									?>
									</div>
									<div class="content">
										<div>
											<h4><?php echo esc_html( $post_title ); ?></h4>
										</div>
										<ul>
											<li><?php echo esc_html( ! empty( $excerpt ) ? $excerpt : $trim_content ); ?></li>
										</ul>
									</div>
								</a>
							<?php
						}
						?>
						</div>
					<?php
				}
				wp_reset_postdata();
				?>
			</div>
		</div>

		<?php
		if ( ! empty( $category_faqs ) ) {
			?>
				<div class="faq-wrapper">
					<div class="container">
						<h2 class="title-curve brown"><?php echo esc_html( 'FAQ’s' ); ?></h2>
						<div class="faqs">
							<?php
							$faq_index = 1;
							foreach ( $category_faqs as $faq ) {
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
		?>
	</div>
</main>

<?php
get_footer();
