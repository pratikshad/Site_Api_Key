<?php

namespace Drupal\site_api_key\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class SiteApiKeyRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Change form for the system.site_information_settings route
    // to Drupal\site_api_key\Form\SiteApiKeySiteInformationForm
    // First, we need to act only on the system.site_information_settings route.
    if ($route = $collection->get('system.site_information_settings')) {
      // Set the value for _form to the form we have created.
      $route->setDefault('_form', 'Drupal\site_api_key\Form\SiteApiKeySiteInformationForm');
    }
  }

}
