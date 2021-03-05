<?php

namespace Drupal\example;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Security\TrustedCallbackInterface;

class TimeDataService implements TrustedCallbackInterface {

  /**
   *
   * @var Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $languageManager;

  /**
   * The Date format variable.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormat;

  public function __construct(ConfigFactoryInterface $config_factory, DateFormatter $date_format) {
    $this->configFactory = $config_factory;
    $this->dateFormat = $date_format;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'), $container->get('date.formatter')
    );
  }

  /**
   * Function that return country and city.
   * @return type
   */
  public function getConfigData() {
    $result = [];
    $result['city'] = $this->configFactory->getEditable('example.timeconfig')->get('city');
    $result['country'] = $this->configFactory->getEditable('example.timeconfig')->get('country');
    $result['zone'] = $this->configFactory->getEditable('example.timeconfig')->get('zone');
    return $result;
  }

  /**
   * Function that return time.
   * @return type
   */
  public function getTime() {
    $timeZone = $this->configFactory->getEditable('example.timeconfig')->get('zone');
    $date = $this->dateFormat->format(time(), 'custom', "jS M Y \- h:i:s A", $timeZone);
    return array(
      '#markup' => $date,
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function trustedCallbacks() {
    return ['getTime'];
  }

}
