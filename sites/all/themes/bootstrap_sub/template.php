<?php

// Remove author's blog link from the bottom of every blog post
function bootstrap_sub_node_view_alter(&$build) {
      if (isset($build['links']['blog']['#links']['blog_usernames_blog'])) {
        unset($build['links']['blog']['#links']['blog_usernames_blog']);
      }
    }

    /**
     * Returns HTML for a menu link and submenu. Needed for Book menu
     * https://www.drupal.org/project/bootstrap/issues/2752999
     *
     * @param array $variables
     *   An associative array containing:
     *   - element: Structured array data for a menu link.
     *
     * @return string
     *   The constructed HTML.
     *
     * @see theme_menu_link()
     *
     * @ingroup theme_functions
     */
    function bootstrap_sub_menu_link(array $variables) {
      $element = $variables['element'];
      $sub_menu = '';
      if ($element['#below']) {
        // Prevent dropdown functions from being added to management menu so it
        // does not affect the navbar module.
        if (($element['#original_link']['menu_name'] == 'management') &&
          (module_exists('navbar'))) {
          $sub_menu = drupal_render($element['#below']);
        }
        elseif ((!empty($element['#original_link']['depth'])) &&
          ($element['#original_link']['depth'] == 1) &&
          $element['#original_link']['module'] != 'book') {
          // Add our own wrapper.
          unset($element['#below']['#theme_wrappers']);
          $sub_menu = '<ul class="dropdown-menu">' . drupal_render
            ($element['#below']) . '</ul>';
          // Generate as standard dropdown.
          $element['#title'] .= ' <span class="caret"></span>';
          $element['#attributes']['class'][] = 'dropdown';
          $element['#localized_options']['html'] = TRUE;

          // Set dropdown trigger element to # to prevent inadvertant page loading
          // when a submenu link is clicked.
          $element['#localized_options']['attributes']['data-target'] = '#';
          $element['#localized_options']['attributes']['class'][] = 'dropdown-toggle';
          $element['#localized_options']['attributes']['data-toggle'] = 'dropdown';
        }
        // Disable for Book navigation block.
        elseif ($element['#original_link']['module'] == 'book') {
          $sub_menu = drupal_render($element['#below']);
        }
      }

      $output = l($element['#title'], $element['#href'], $element['#localized_options']);
      return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
    }

// to make themplate overrides work
/**
* Variables preprocess function for the "page" theming hook. Theme name must be specified in the function call ie THEMENAME_preprocess_page
*/
function bootstrap_sub_preprocess_page(&$vars) {

// Do we have a node?
if (isset($vars['node'])) {

  // Ref suggestions cuz it's stupid long.
  $suggests = &$vars['theme_hook_suggestions'];

  // Get path arguments.
  $args = arg();
  // Remove first argument of "node".
  unset($args[0]);

  // Set type.
  $type = "page__type_{$vars['node']->type}";

  // Bring it all together.
  $suggests = array_merge(
    $suggests,
    array($type),
    theme_get_suggestions($args, $type)
  );

  // if the url is: 'http://domain.com/node/123/edit'
  // and node type is 'blog'..
  //
  // This will be the suggestions:
  //
  // - page__node
  // - page__node__%
  // - page__node__123
  // - page__node__edit
  // - page__type_blog
  // - page__type_blog__%
  // - page__type_blog__123
  // - page__type_blog__edit
  //
  // Which connects to these templates:
  //
  // - page--node.tpl.php
  // - page--node--%.tpl.php
  // - page--node--123.tpl.php
  // - page--node--edit.tpl.php
  // - page--type-blog.tpl.php          << this is what you want.
  // - page--type-blog--%.tpl.php
  // - page--type-blog--123.tpl.php
  // - page--type-blog--edit.tpl.php
  //
  // Latter items take precedence.
}
}

/**
 * Returns HTML for a menu link and submenu.
 *
 * @param array $variables
 *   An associative array containing:
 *   - element: Structured array data for a menu link.
 *
 * @return string
 *   The constructed HTML.
 *
 * @see theme_menu_link()
 *
 * @ingroup theme_functions
 */
// function bootstrap_sub_menu_link(array $variables) {
//       $element = $variables['element'];
//       $sub_menu = '';
//       if ($element['#below']) {
//         // Prevent dropdown functions from being added to management menu so it
//         // does not affect the navbar module.
//         if (($element['#original_link']['menu_name'] == 'management') &&
//           (module_exists('navbar'))) {
//           $sub_menu = drupal_render($element['#below']);
//         }
//         elseif ((!empty($element['#original_link']['depth'])) &&
//           ($element['#original_link']['depth'] == 1) &&
//           $element['#original_link']['module'] != 'book') {
//           // Add our own wrapper.
//           unset($element['#below']['#theme_wrappers']);
//           $sub_menu = '<ul class="dropdown-menu">' . drupal_render
//             ($element['#below']) . '</ul>';
//           // Generate as standard dropdown.
//           $element['#title'] .= ' <span class="caret"></span>';
//           $element['#attributes']['class'][] = 'dropdown';
//           $element['#localized_options']['html'] = TRUE;

//           // Set dropdown trigger element to # to prevent inadvertant page loading
//           // when a submenu link is clicked.
//           $element['#localized_options']['attributes']['data-target'] = '#';
//           $element['#localized_options']['attributes']['class'][] = 'dropdown-toggle';
//           $element['#localized_options']['attributes']['data-toggle'] = 'dropdown';
//         }
//         // Disable for Book navigation block.
//         elseif ($element['#original_link']['module'] == 'book') {
//           $sub_menu = drupal_render($element['#below']);
//         }
//       }

//       $output = l($element['#title'], $element['#href'], $element['#localized_options']);
//       return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
//     }

/**
 * Implements hook_comment_view_alter().
 *
 * Add a custom nesting wrapper that includes the initial comment and all its
 * replies. Core adds indent wrappers that work fine for each specific level of
 * reply, but no wrapper that includes the comment itself. Due to the way core
 * handles the markup related to comment indents, we need to work directly on
 * the render array for each comment as opposed to altering, or pre-processing,
 * any comment-specific theme functions. Note that alter hooks are allowed
 * inside theme functions (unlike normal module hooks).
 */

 /**
  * Implements hook_comment_view_alter().
  *
  * Add a custom nesting wrapper that includes the initial comment and all its
  * replies. Core adds indent wrappers that work fine for each specific level of
  * reply, but no wrapper that includes the comment itself. Due to the way core
  * handles the markup related to comment indents, we need to work directly on
  * the render array for each comment as opposed to altering, or pre-processing,
  * any comment-specific theme functions. Note that alter hooks are allowed
  * inside theme functions (unlike normal module hooks).
  */
