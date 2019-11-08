<?php

namespace Drupal\site_api_key\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;

/**
 * Configure site information settings.
 */
class SiteApiKeySiteInformationForm extends SiteInformationForm {

  /**
   * Override site information and add site api key field.
   *
   * @param array $form
   *   The site information form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Retrieve the system.site configuration.
    $site_config = $this->config('system.site');

    // Get the original form from the class we are extending.
    $form = parent::buildForm($form, $form_state);

    if (empty($site_config->get('siteapikey'))) {
      $defaultValue = $this->t("No API key yet");
      $submitText = $form['actions']['submit']['#value'];
    }
    else {
      $defaultValue = $site_config->get('siteapikey');
      $submitText = $this->t('Update Configuration');
    }

    // Add a textarea for site api key to the site information section.
    $form['site_information']['siteapikey'] = [
      '#type' => 'textfield',
      '#title' => t('Site API Key'),
      '#default_value' => $defaultValue,
      '#description' => $this->t('Site api key'),
    ];

    $form['actions']['submit']['#value'] = $submitText;

    return $form;
  }

  /**
   * {@inheritdoc}
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Now we need to save the new siteapikey to the
    // system.site.siteapikey configuration.
    $this->config('system.site')
      ->set('siteapikey', $form_state->getValue('siteapikey'))
      ->save();

    // Pass the remaining values off to the original form that we have extended,
    // so that they are also saved.
    parent::submitForm($form, $form_state);
  }

}
