<?php
/**
 * Template for wonder-category mega menu.
 */

// Get all the wonder-category terms.
$category_terms         = get_terms(
	[
		'taxonomy'   => 'wonder-categories',
		'hide_empty' => true,
		'parent'     => 0,
		'meta_key'   => 'mega_menu_order',
		'orderby'    => 'meta_value_num',
		'order'      => 'ASC',
	]
);
$first_cat_id           = $category_terms[0]->term_id;
$default_category_image = get_field( 'default_category_image', 'option' );
?>

<div class="menu-popup-wrapper">
	<div class="menu-popup-category-wrapper">
		<ul>
			<?php
			// Display wonder-category terms.
			if ( ! empty( $category_terms ) ) {
				foreach ( $category_terms as $cat_term ) {
					$term_id       = $cat_term->term_id;
					$term_name     = $cat_term->name;
					$active_class  = $term_id === $first_cat_id ? 'active' : '';
					$category_page = get_field( 'category_page', 'wonder-categories_' . $term_id ) ? get_field( 'category_page', 'wonder-categories_' . $term_id ) : '#';
					?>
						<a href="<?php echo esc_url( $category_page ); ?>">
							<li class="animal-category <?php echo esc_attr( $active_class ); ?>" data-term-id="<?php echo esc_attr( $term_id ); ?>">
								<?php echo esc_attr( $term_name ); ?>
							</li>
						</a>
					<?php
				}
			}
			?>
		</ul>
	</div>
	<div class="megamenu-category-item-wrapper">
		<?php
		if ( ! empty( $category_terms ) ) {
			foreach ( $category_terms as $cat_term ) {
				$term_id      = $cat_term->term_id;
				$term_name    = $cat_term->name;
				$term_slug    = $cat_term->slug;
				$active_class = $term_id === $first_cat_id ? 'flex' : 'none';

				if ( $term_id == $first_cat_id ) {
					$child_category_ids = [];
					foreach ( $category_terms as $category ) {
						$child_category_ids [] = $category->term_id;
					}
				} else {
					// Get the IDs of all child categories
					$child_category_ids = get_term_children( $term_id, 'wonder-categories' );
				}
				?>
					<ul class="megamenu-category-item" data-term-id="<?php echo esc_attr( $term_id ); ?>" style="display:<?php echo $active_class; ?>">
						<?php
						foreach ( $child_category_ids as $child_category_id ) {
							$child_category   = get_term_by( 'id', $child_category_id, 'wonder-categories' );
							$child_term_url   = get_term_link( $child_category_id, 'wonder-categories' );
							$child_term_title = $child_category->name;
							$child_term_image = get_field( 'category_image', 'wonder-categories_' . $child_category_id );
							$child_term_image ? $child_term_image : $child_term_image = $default_category_image;
							?>
								<li>
									<a href="<?php echo esc_url( $child_term_url ); ?>">
										<img class="img-cover" src="<?php echo esc_url( $child_term_image ); ?>" alt="term-image">
										<span><?php echo esc_html( $child_term_title ); ?></span>
									</a>
								</li>
							<?php
						}
						if ( $term_id !== $first_cat_id ) {
							?>
								<li class="seeall-cat">
									<a class="seeall-category" data-cat-id="<?php echo esc_attr( $term_slug ); ?>" href="#"><?php echo esc_html( 'All ' . $term_name ); ?></a>
								</li>
							<?php
						}
						?>
					</ul>
				<?php
			}
		}
		?>
	</div>
</div>
