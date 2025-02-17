<?php

// Register Shortcodes
function twentytwentyfive_register_shortcodes() {
  add_shortcode('recent_book', 'twentytwentyfive_recent_book_title_shortcode');
  add_shortcode('genre_books', 'twentytwentyfive_genre_book_list_shortcode');
}
add_action('init', 'twentytwentyfive_register_shortcodes');

/**
 * Shortcode to display the most recent book title.
 * Usage: [recent_book]
 * @return string The formatted book title with link
 */ 
function twentytwentyfive_recent_book_title_shortcode($atts) {
  $args = array(
      'post_type' => 'book',
      'posts_per_page' => 1,
      'orderby' => 'meta_value', // Order by publication date
      'meta_key' => 'publication_date',
      'order' => 'DESC',
      'post_status' => 'publish'
  );

  $recent_book = new WP_Query($args);

  if ($recent_book->have_posts()) {
    $recent_book->the_post();
    $output = sprintf(
        '<div class="recent-book">
            <a href="%s">%s</a>
        </div>',
        esc_url(get_permalink()),
        esc_html(get_the_title())
    );
      wp_reset_postdata();

      return $output;
  }

  return '<p>No books found.</p>';
}

/**
 * Shortcode to display a list of books by specific genre.
 * Usage: [genre_books genre_id="3"]
 * @param array $atts Shortcode attributes
 * @return string The formatted list of books
 */
function twentytwentyfive_genre_book_list_shortcode($atts) {
  // Extract and validate attributes
  $atts = shortcode_atts(
    array(
        'genre_id' => 3,
    ),
    $atts,
    'genre'
  );

  // Validate genre_id
  if (empty($atts['genre_id']) || !term_exists((int)$atts['genre_id'], 'genre')) {
      return '<p>Please provide a valid genre ID.</p>';
  }

  $args = array(
      'post_type' => 'book',
      'posts_per_page' => 5,
      'orderby' => 'title',
      'order' => 'ASC',
      'post_status' => 'publish',
      'tax_query' => array(
          array(
              'taxonomy' => 'genre',
              'field' => 'term_id',
              'terms' => (int)$atts['genre_id']
          )
      )
  );

  $genre_books = new WP_Query($args);
  $genre_term = get_term($atts['genre_id'], 'genre');

  if ($genre_books->have_posts()) {
      $output = sprintf(
          '<div class="genre-books">
              <h3 class="genre-title">%s Genre Book List</h3>
              <ul>',
          esc_html($genre_term->name)
      );

      while ($genre_books->have_posts()) {
          $genre_books->the_post();
          $pub_date = get_post_meta(get_the_ID(), 'publication_date', true);
          
          $output .= sprintf(
              '<li>
                  <a href="%s">%s</a> 
                   %s
              </li>',
              esc_url(get_permalink()),
              esc_html(get_the_title()),
              $pub_date ? '<span class="book-date">' . esc_html($pub_date) . '</span>' : ''
          );
      }

      $output .= '</ul></div>';
      wp_reset_postdata();

      return $output;
  }

  return sprintf('<p>No books found in the "%s" genre.</p>', esc_html($genre_term->name));
}