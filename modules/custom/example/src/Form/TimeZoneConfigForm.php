<?php

namespace Drupal\example\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\CacheBackendInterface;

/**
 * Class HeaderConfigForm.
 */
class TimeZoneConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory, CacheBackendInterface $cache) {
    $this->setConfigFactory($config_factory);
    $this->cache = $cache;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'), $container->get('cache.render')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'example.timeconfig'
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'header_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('example.timeconfig');
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#default_value' => $config->get('country'),
    ];

    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $config->get('city'),
    ];
    $timeZoneList = [
      'America/Chicago',
      'America/New_York',
      'Asia/Tokyo',
      'Asia/Dubai',
      'Asia/Kolkata',
      'Europe/Amsterdam',
      'Europe/Oslo',
      'Europe/London',
    ];
    $options = array_combine($timeZoneList, $timeZoneList);

    $form['zone'] = array(
      '#type' => 'select',
      '#title' => $this->t('TimeZone'),
      '#options' => $options,
      '#default_value' => $config->get('zone'),
      '#description' => t('Set the desired timezone.'),
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    // Retrieve the configuration.
    $this->configFactory->getEditable('example.timeconfig')
      // Set the submitted configuration setting.
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('zone', $form_state->getValue('zone'))
      ->save();
    $this->cache->invalidateAll();
  }

}
