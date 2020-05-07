<?php

namespace Drupal\neg_google_reviews\Plugin;

use Drupal\neg_google_reviews\ReviewSettings;
use Drupal\neg_google_reviews\ReviewService;

/**
 * Class ReviewsSync.
 */
class ReviewsSync {
  protected $placeId;

  /**
   * Implements __construct().
   */
  public function __construct($place_id) {
    $this->placeId = $place_id;
  }

  /**
   * Logs a message.
   */
  protected function log($message, $params = [], $log_level = 'notice') {
    \Drupal::logger('neg_google_reviews')->$log_level($message, $params);
  }

  /**
   * Gets a config object.
   */
  protected function config() {
    return \Drupal::config(ReviewSettings::CONFIGNAME);
  }

  /**
   * Gets an editable config object.
   */
  protected function getEditableConfig() {
    return \Drupal::service('config.factory')->getEditable(ReviewSettings::CONFIGNAME);
  }

  /**
   * Syncs the calendar.
   */
  public function sync() {
    $config = $this->getEditableConfig();
    $service = new ReviewService();

    $result = $service->fetchReviews($this->placeId);

    $reviews = $result['reviews'];
    $url = $result['url'];

    $this->log('Fetched %c reviews from Google', [
      '%c' => count($reviews),
    ], 'notice');

    // Set reviews.
    $config->set('reviews', $reviews);

    // Set url to google listing.
    $config->set('url', $url);

    // Set last_full_sync.
    $config->set('last_sync', time());

    $config->save();

    // Invalidate Cache Tags.
    ReviewSettings::invalidateCache();
    return TRUE;
  }

}
