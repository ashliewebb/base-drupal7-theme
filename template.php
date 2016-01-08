<?php

/**
 * Pre-process the node.
 *
 * Implements theme_preprocess_node().
 *
 * @param $variables
 */
function base_preprocess_node(&$variables) {

  switch ($variables['view_mode']) {
    case 'teaser':
      $variables['theme_hook_suggestions'][] = 'node__' . $variables['node']->type . '__teaser';
      $variables['theme_hook_suggestions'][] = 'node__' . $variables['node']->nid . '__teaser';
      break;
  }

}

/**
 * Implements hook_preprocess_field().
 *
 * Adding new template suggestions for fields.
 *
 * @param $variables
 */
function base_preprocess_field(&$variables) {
  $element = $variables['element'];
  if (isset($element['#field_name'])) {
    $variables['theme_hook_suggestions'][] = 'field__' . $element['#field_name'] . '__' . $element['#bundle'] . '__' . $element['#view_mode'];
  }
}

/**
 * Pre-process the page.
 *
 * Implements theme_preprocess_page().
 *
 * @param $variables
 */
function base_preprocess_page(&$variables) {

  // Add in template suggestions based on node type.
  if (isset($variables['node']->type)) {

    $nodetype = $variables['node']->type;
    $variables['theme_hook_suggestions'][] = 'page__' . $nodetype;

    // Add the home page hero image and text variable to template.
    switch ($variables['node']->type) {
      case 'content_type':
        drupal_add_js(path_to_theme() . '/js/app/sub-nav.js');
        break;
    }
  }
}


/**
 * Pre-process html
 *
 * Implements hook_preprocess_html().
 *
 * @param $variables
 */
function base_preprocess_html(&$variables) {

  // Add the Page's first argument as body class.
	if(!empty(_sds_mywow_get_section_class())) {
		$variables['classes_array'][] = _sds_mywow_get_section_class();
	}

  // Add viewport meta to head
  $viewport = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'viewport',
      'content' => 'width=device-width, initial-scale=1',
    ),
  );
  drupal_add_html_head($viewport, 'viewport');

  // Add X-UA-Compatible for IE Support
  $xua = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'http-equiv' => 'X-UA-Compatible',
      'content' => 'IE=edge,chrome=1',
    ),
  );
  drupal_add_html_head($xua, 'xua');
}

/**
 * Get the section class.
 *
 * @return string
 */
function _base_get_section_class() {

	$class = '';

	// Add the Page's first argument as body class.
	$path_arguments = explode('/', drupal_get_path_alias(implode('/', arg())));

	if (isset($path_arguments[0])) {
		$class = drupal_html_class($path_arguments[0]);
	}

	return $class;
}

/**
 * Change the directory of the file's icons.
 *
 * Implements hook_file_icon().
 *
 * @param $variables
 *
 * @return string
 */
function base_file_icon($variables) {
  $file = $variables['file'];
  $icon_directory = drupal_get_path('theme', 'base') . '/images/doc-types';

  $mime = check_plain($file->filemime);
  $icon_url = file_icon_url($file, $icon_directory);
  return '<img alt="' . $mime . '" class="file-icon" src="' . $icon_url . '" title="" width="56" height="74" />';
}

