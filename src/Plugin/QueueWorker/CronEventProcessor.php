<?php

namespace Drupal\neg_google_reviews\Plugin\QueueWorker;

/**
 *
 * @QueueWorker(
 * id = "google_reviews_sync",
 * title = "Syncs Google Reviews with Drupal",
 * cron = {"time" = 60}
 * )
 */
class CronEventProcessor extends SyncGoogleReviews {
}
