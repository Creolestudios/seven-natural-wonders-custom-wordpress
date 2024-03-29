<?php
/**
 * Template for explore diversity section.
 */

$selected_category_ids = get_field( 'explore_the_diversity_section', get_the_ID() );
$wonder_list           = get_page_by_title( 'Wonders' );
$wonder_list_url       = get_permalink( $wonder_list->ID );

if ( ! empty( $selected_category_ids ) ) {
	?>
		<div class="explore-the-diversity">
			<div class="container">
				<h2 class="title-curve black"><?php echo esc_html( 'Discover the wonders of the world.' ); ?></h2>
				<a class="btn btn-transparent-brown" href="<?php echo esc_url( $wonder_list_url ); ?>">
					<?php echo esc_html( 'Explore Now' ); ?>
				</a>
				<div class="explore-the-diversity-card-wrapper">
					<?php
					foreach ( $selected_category_ids as $category_id ) {
						$category       = get_term( $category_id, 'wonder-categories' );
						$category_slug  = $category->slug;
						$category_name  = $category->name;
						$category_url   = $wonder_list_url . "?category=$category_slug";
						$category_image = get_field( 'category_image', 'wonder-categories_' . $category_id );
						?>
							<a href="<?php echo esc_url( $category_url ); ?>" class="explore-the-diversity-card-item diversity-category-detail">
								<div class="explore-the-diversity-card-img-wrapper">
									<?php
									if ( ! empty( $category_image ) ) {
										?>
											<img class="img-cover" src="<?php echo esc_url( $category_image ); ?>" alt="wonder-img" >
										<?php
									} else {
										$no_image_thumbnail = get_field( 'default_category_image', 'option' );
										?>
											<img class="img-cover" src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail" >
										<?php
									}
									?>
								</div>
								<h5 class="explore-the-diversity-card-content"><?php echo esc_html( $category_name ); ?></h5>
							</a>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	<?php
}
?>
