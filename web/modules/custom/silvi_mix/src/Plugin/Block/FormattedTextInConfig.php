<?php

namespace Drupal\silvi_mix\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a formatted text block that stores all settings in config.
 *
 * @Block(
 *   id = "formatted_text_in_config",
 *   admin_label = @Translation("Formatted Text In Config"),
 *   category = @Translation("custom"),
 * )
 */
class FormattedTextInConfig extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Get config.
    $config = $this->getConfiguration();

    // Prep CSS classes.
    $all_css_classes = [];
    if (!empty($config['common_usage'])) :
      $all_css_classes[] = 'usage-'.$config['common_usage'];
    endif;
    if (!empty($config['css_classes'])) :
      $all_css_classes[] = $config['css_classes'];
    endif;

    return [
      '#type' => 'markup',
      '#markup' => $config['formatted_text'],
      '#attributes' => [
        'class' => [
          implode(' ', $all_css_classes),
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['formatted_text'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Formatted Text'),
      '#description' => $this->t('Formatted text for display in this block.'),
      '#default_value' => $config['formatted_text'] ?? '',
      '#format' => $config['formatted_text_format'] ?? 'full_html',
    ];

    $form['common_usage'] = [
      '#type' => 'radios',
      '#title' => $this->t('Common Uses'),
      '#description' => $this->t('Is this block intended for one of the following standard uses?'),
      '#default_value' => $config['common_usage'] ?? 'no',
      '#options' => [
        'no' => 'No, this block is not intended for a standard use',
        'full_width_banner' => 'Full width, banner',
        'half_width_text_on_image' => 'Half width, text overlaid on an image',
        'half_width_text_on_solid' => 'Half width, text overlaid on a solid background',
      ],
      '#required' => TRUE,
    ];

    $form['css_classes'] = [
      '#type' => 'textfield',
      '#title' => $this->t('CSS Classes'),
      '#description' => $this->t('Additional CSS classes applied to the block.'),
      '#default_value' => $config['css_classes'] ?? '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['formatted_text'] = $values['formatted_text']['value'];
    $this->configuration['formatted_text_format'] = $values['formatted_text']['format'];
    $this->configuration['common_usage'] = $values['common_usage'];
    $this->configuration['css_classes'] = $values['css_classes'];
  }

}
