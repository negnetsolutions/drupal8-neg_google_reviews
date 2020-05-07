<?php

namespace Drupal\neg_google_reviews\Form;

use Drupal\neg_google_reviews\ReviewSettings;
use Drupal\neg_google_reviews\Plugin\ReviewsSync;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Settings for Google Reviews.
 */
class ReviewsSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'google_reviews_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      ReviewSettings::CONFIGNAME,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(ReviewSettings::CONFIGNAME);

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => t('Google API Key'),
      '#default_value' => $config->get('api_key'),
      '#description' => t('Enter your api key with access to the google place api.'),
      '#required' => TRUE,
    ];

    $form['place_id'] = [
      '#type' => 'textfield',
      '#title' => t('Google Place ID'),
      '#default_value' => $config->get('place_id'),
      '#description' => t('Enter the Google Place ID'),
      '#required' => TRUE,
    ];

    $form['frequency'] = [
      '#type' => 'select',
      '#title' => t('Sync Frequency'),
      '#default_value' => $config->get('frequency'),
      '#options' => [
        '0' => 'Every Cron Run',
        '21600' => 'Every 12 Hours',
        '86400' => 'Every 24 Hours',
      ],
      '#required' => TRUE,
    ];

    $form['last_sync'] = [
      '#markup' => '<p>Last Sync: ' . date('r', $config->get('last_sync')) . '</p>',
    ];

    $form['force_sync'] = [
      '#type' => 'submit',
      '#value' => t('Force Sync Now'),
      '#submit' => ['::forceSync'],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Forces a resync.
   */
  public function forceSync(array &$form, FormStateInterface $form_state) {
    $config = $this->config(ReviewSettings::CONFIGNAME);
    $sync = new ReviewsSync($config->get('place_id'));

    try {
      $sync->sync();
    }
    catch (\Exception $e) {
      drupal_set_message($e->getMessage(), 'error', TRUE);
      return;
    }

    drupal_set_message('Google Reviews Updated.', 'status', TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $config = $this->configFactory->getEditable(ReviewSettings::CONFIGNAME);

    $resync = FALSE;
    if ($form_state->getValue('place_id') !== $config->get('place_id')) {
      $resync = TRUE;
    }

    $config->set('place_id', $form_state->getValue('place_id'))
      ->set('api_key', $form_state->getValue('api_key'))
      ->set('frequency', $form_state->getValue('frequency'));

    $config->save();

    if ($resync === TRUE) {
      $this->forceSync($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

}
