<?php

namespace Drupal\site_api_key\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\node\Entity\Node;

/**
 * Provides a resource to get view modes of contentty page.
 *
 * @RestResource(
 *   id = "page_json",
 *   label = @Translation("Page API"),
 *   uri_paths = {
 *     "canonical" = "/page_json/{siteApiKey}/{nid}"
 *   }
 * )
 */
class PageNodeRestResource extends ResourceBase {

  /**
   * Responds to GET requests.
   *
   * @param string $siteApiKey
   *   Api key. 
   * @param int $nid
   *   Page nid.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get($siteApiKey, $nid) {

    // Retrieve the system.site configuration.
    $site_config = \Drupal::config('system.site');
    // Load node.
    $node = Node::load($nid);
    if ($siteApiKey != $site_config->get('siteapikey') || empty($node)) {
      throw new AccessDeniedHttpException();
    }
    if ($node) {
      $data = ['id' => $node->id(), 'title' => $node->getTitle(), 'body' => $node->get('body')->value];
    }
    $response = new ResourceResponse($data);
    // In order to generate fresh result every time (without clearing
    // the cache), you need to invalidate the cache.
    $response->addCacheableDependency($data);
    return $response;
  }

}
