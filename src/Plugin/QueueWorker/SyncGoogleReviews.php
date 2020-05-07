<?php

namespace Drupal\neg_google_reviews\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\neg_google_reviews\Plugin\ReviewsSync;

/**
 * Class SyncGoogleReviews.
 */
class SyncGoogleReviews extends QueueWorkerBase {

  /**
   * Processes a queue item.
   */
  public function processItem($data) {

    switch ($data['op']) {
      case 'syncReviews':
        $id = $data['placeId'];

        $reviews = new ReviewsSync($id);
        $reviews->sync();
        break;
    }
  }

}
