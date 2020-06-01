<?php

namespace Drupal\neg_google_reviews;

/**
 * Class Reviews.
 */
class Reviews {

  /**
   * Get the google listing url.
   */
  public static function getUrl() {
    $config = \Drupal::config(ReviewSettings::CONFIGNAME);
    return $config->get('url');
  }

  /**
   * Gets Google reviews.
   */
  public static function getReviews() {
    $data = \Drupal::state()->get('neg_google_reviews.reviews', NULL);

    $reviews = [];
    foreach ($data as $r) {
      $reviews[] = new Review($r);
    }

    return $reviews;
  }

}
