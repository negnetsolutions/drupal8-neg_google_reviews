<?php

namespace Drupal\neg_google_reviews;

/**
 * Class ReviewService.
 */
class ReviewService {

  /**
   * Fetches Google Reviews.
   */
  public function fetchReviews($placeId) {
    $config = \Drupal::config(ReviewSettings::CONFIGNAME);
    $client = \Drupal::httpClient();
    $apiKey = $config->get('api_key');

    $request = $client->request('GET', 'https://maps.googleapis.com/maps/api/place/details/json?place_id=' . $placeId . '&key=' . $apiKey . '&fields=review,url');

    $response = json_decode($request->getBody(), TRUE);

    if (!$response || $response['status'] !== 'OK') {
      throw new \Exception("Invalid response from google: " . $response['status']);
    }

    return $response['result'];
  }

}
