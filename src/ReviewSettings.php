<?php

namespace Drupal\neg_google_reviews;

use Drupal\Core\Cache\Cache;

/**
 * Class ReviewSettings.
 */
class ReviewSettings {

  const CONFIGNAME = 'neg_google_reviews.settings';

  /**
   * Invalidates review cache.
   */
  public static function invalidateCache() {
    Cache::invalidateTags(['google_reviews']);
  }

}
