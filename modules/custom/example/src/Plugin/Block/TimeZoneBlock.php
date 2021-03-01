<?php

namespace Drupal\example\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\example\TimeDataService;

/**
 *
 * @Block(
 *   id = "timezone_block",
 *   admin_label = @Translation("TimeZoneBlock"),
 *   category = @Translation("Custom")
 * )
 */
class TimeZoneBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var Drupal\example\TimeDataService
   */
  protected $timeService;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, TimeDataService $time_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->timeService = $time_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, $plugin_id, $plugin_definition, $container->get('example.time_data')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $result = $this->timeService->getConfigData();
    return [
      
      '#theme' => 'time_zone_block',
      '#result' => $result,
      '#timestamp' => array(
        '#lazy_builder' => ['example.time_data:getTime', array()],
        '#create_placeholder' => TRUE
      ),
    ];
  }

}
