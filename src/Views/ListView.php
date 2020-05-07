<?php

namespace Drupal\neg_google_reviews\Views;

use Drupal\neg_google_reviews\Reviews;

/**
 * Class ListView.
 */
class ListView {

  protected $variables;

  /**
   * Implements constructor.
   */
  public function __construct(array &$variables) {
    $this->variables = &$variables;
  }

  /**
   * Fetches reviews.
   */
  protected function fetchReviews() {
    return Reviews::getReviews();
  }

  /**
   * Fetches the url to the google listing.
   */
  protected function fetchUrl() {
    return Reviews::getUrl();
  }

  /**
   * Renders the view.
   */
  public function render() {
    $reviews = $this->fetchReviews();
    $url = $this->fetchUrl();

    $this->variables['view'] = [
      '#theme' => 'neg_google_reviews_list_view',
      '#reviews' => $reviews,
      '#url' => $url,
      '#cache' => [
        'contexts' => ['url'],
        'tags' => ['google_reviews'],
      ],
    ];
  }

}
