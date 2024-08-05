<?php

namespace Drupal\unimenu\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Universal Menu Block' block.
 *
 * @Block(
 *   id = "universal_menu",
 *   admin_label = "Универсальный блок меню с мобильной версией"
 * )
 */
class UniversalMenuBlock extends BlockBase {

  /**
   * {@inheritdoc}
   *
   * This method sets the block default configuration. This configuration
   * determines the block's behavior when a block is initially placed in a
   * region. Default values for the block configuration form should be added to
   * the configuration array. System default configurations are assembled in
   * BlockBase::__construct() e.g. cache setting and block title visibility.
   *
   * @see \Drupal\block\BlockBase::__construct()
   */
  public function defaultConfiguration() {
    return [
      'unimenu_machinename_menu' => 'main',
      'unimenu_phone' => '8 (905) 530-30-50',
      'unimenu_address' => 'г. Москва, Походный проезд, дом 7',
      'unimenu_worktime' => 'Ежедневно с 9:00 до 21:00',
      'unimenu_telegram' => 'https://t.me/htmpro',
      'unimenu_whatsapp' => 'https://wa.me/79055303050?text=Здравствуйте!%20Меня%20интересует...',
      'unimenu_panel' => false,
    ];
  }

  /**
   * {@inheritdoc}
   *
   * This method defines form elements for custom block configuration. Standard
   * block configuration fields are added by BlockBase::buildConfigurationForm()
   * (block title and title visibility) and BlockFormController::form() (block
   * visibility settings).
   *
   * @see \Drupal\block\BlockBase::buildConfigurationForm()
   * @see \Drupal\block\BlockFormController::form()
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $query = \Drupal::database()->select('menu_tree', 'mt');
    $query->addField('mt', 'menu_name');
    $query->groupBy('menu_name');
    $menus = $query->execute()->fetchCol();

    $menusArr = [];
    foreach ($menus as $menu) {
      $menusArr[$menu] = $menu;
    }

    $form['unimenu_machinename_menu'] = [
      '#type' => 'select',
      '#title' => 'Машинное имя меню',
      '#options' => $menusArr,
      '#default_value' => $this->configuration['unimenu_machinename_menu'],
    ];

    $form['unimenu_phone'] = [
      '#type' => 'textfield',
      '#title' => 'Телефон',
      '#default_value' => $this->configuration['unimenu_phone'],
    ];

    $form['unimenu_address'] = [
      '#type' => 'textfield',
      '#title' => 'Адрес',
      '#default_value' => $this->configuration['unimenu_address'],
    ];

    $form['unimenu_worktime'] = [
      '#type' => 'textfield',
      '#title' => 'График работы',
      '#default_value' => $this->configuration['unimenu_worktime'],
    ];

    $form['unimenu_telegram'] = [
      '#type' => 'textfield',
      '#title' => 'Telegram',
      '#default_value' => $this->configuration['unimenu_telegram'],
    ];

    $form['unimenu_whatsapp'] = [
      '#type' => 'textfield',
      '#title' => 'WhatsApp',
      '#default_value' => $this->configuration['unimenu_whatsapp'],
    ];

    $form['unimenu_panel'] = [
      '#type' => 'checkbox',
      '#title' => 'Нижняя панель меню',
      '#default_value' => $this->configuration['unimenu_panel'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   *
   * This method processes the blockForm() form fields when the block
   * configuration form is submitted.
   *
   * The blockValidate() method can be used to validate the form submission.
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['unimenu_machinename_menu'] = $form_state->getValue('unimenu_machinename_menu');
    $this->configuration['unimenu_phone'] = $form_state->getValue('unimenu_phone');
    $this->configuration['unimenu_address'] = $form_state->getValue('unimenu_address');
    $this->configuration['unimenu_worktime'] = $form_state->getValue('unimenu_worktime');
    $this->configuration['unimenu_telegram'] = $form_state->getValue('unimenu_telegram');
    $this->configuration['unimenu_panel'] = $form_state->getValue('unimenu_panel');
  }

  /**
   * {@inheritdoc}
   *
   * The return value of the build() method is a renderable array. Returning an
   * empty array will result in empty block contents. The front end will not
   * display empty blocks.
   */
  public function build() {
    $menu_id = $this->configuration['unimenu_machinename_menu'];
    $menu_tree_parameters = new MenuTreeParameters();
    $tree = \Drupal::menuTree()->load($menu_id, $menu_tree_parameters);
    $tree_array = \Drupal::menuTree()->build($tree);

    $tree_array['#theme'] = 'universal_menu';
    $tree_array['#attached']['library'][] = 'unimenu/unimenu';

    $tree_array['#unimenu_phone'] = $this->configuration['unimenu_phone'];
    $tree_array['#unimenu_numphone'] = preg_replace('/[^0-9,.]+/', '', $tree_array['#unimenu_phone']);
    $tree_array['#unimenu_address'] = $this->configuration['unimenu_address'];
    $tree_array['#unimenu_worktime'] = $this->configuration['unimenu_worktime'];
    $tree_array['#unimenu_telegram'] = $this->configuration['unimenu_telegram'];
    $tree_array['#unimenu_whatsapp'] = $this->configuration['unimenu_whatsapp'];
    $tree_array['#unimenu_panel'] = $this->configuration['unimenu_panel'];

    // $tree_array['#attached']['library'][] = 'unimenu/unimenu';
    return [
      '#markup' => \Drupal::service('renderer')->render($tree_array),
    ];
  }
}
