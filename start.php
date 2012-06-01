<?php
/**
 * Elgg itemicon plugin
 *
 * @package ElggItemIcon
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Corporación Somos más - 2009; Diego Andrés Ramírez Aragón 2010
 * @link http://github.com/lowfill/itemicon
 */

/**
 * Item icon initialization
 *
 * Register css and handlers
 */
function itemicon_init(){
  global $CONFIG;

  elgg_extend_view("css","itemicon/css");

  elgg_register_page_handler('itemicon','itemicon_icon_page_handler');

  elgg_register_event_handler("create","object","itemicon_icon_handler");
  elgg_register_event_handler("update","object","itemicon_icon_handler");

  elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'itemicon_icon_hook');
  elgg_register_plugin_hook_handler('display', 'view', 'itemicon_overwrite_hook');

  if(elgg_is_active_plugin('blogextended')){
      $CONFIG->itemicon[]='blog';
      elgg_extend_view('blog/fields_after','itemicon/add');
  }
}

/**
 * Itemicon entity url hook
 *
 * @param $hook 'entity:icon:url'
 * @param $entity_type object
 * @param $returnvalue
 * @param $params
 * @return string item's icon url
 */
function itemicon_icon_hook($hook, $entity_type, $returnvalue, $params){
  global $CONFIG;

  if ((!$returnvalue) && ($hook == 'entity:icon:url') && ($params['entity'] instanceof ElggObject)){
    $entity = $params['entity'];
    $subtype = $entity->getSubtype();
    $viewtype = $params['viewtype'];
    $size = $params['size'];

    if ($icontime = $entity->icontime) {
      $icontime = "{$icontime}";
    } else {
      $icontime = "default";
    }

    $filehandler = new ElggFile();
    $filehandler->owner_guid = $entity->owner_guid;
    $filehandler->setFilename("icons/{$entity->guid}-{$entity->title}{$ize}.jpg");

    if ($filehandler->exists()) {
      $url = $CONFIG->url . "pg/itemicon/{$entity->guid}/$size/$icontime.jpg";

      return $url;
    }
  }
}

/**
 * Itemicon url page handler
 */
function itemicon_icon_page_handler($page) {

  global $CONFIG;

  // The username should be the file we're getting
  if (isset($page[0])) {
    set_input('item_guid',$page[0]);
  }
  if (isset($page[1])) {
    set_input('size',$page[1]);
  }
  // Include the standard profile index
  include(dirname(__FILE__) . "/icon.php");
  exit;
}

/**
 * Handle the icon assigned to the object and create extra views from the icon
 *
 * @param $event create | update
 * @param $object_type object
 * @param $object
 * @return boolean
 */
function itemicon_icon_handler($event, $object_type, $object){
  global $CONFIG;
  $subtype = $object->getSubtype();
  if(in_array($subtype,$CONFIG->itemicon)){
    switch($event){
      case "create":
      case "update":
        if ((isset($_FILES['icon'])) && (substr_count($_FILES['icon']['type'],'image/'))){
          $prefix = "icons/{$object->guid}-".$object->title;

          $filehandler = new ElggFile();
          $filehandler->owner_guid = $object->owner_guid;
          $filehandler->setFilename($prefix . ".jpg");
          $filehandler->open("write");
          $filehandler->write(get_uploaded_file('icon'));
          $filehandler->close();

          //@todo Let users configure square icons?
          $thumbtiny = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),25,25, false);
          $thumbsmall = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),40,40, false);
          $thumbmedium = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),100,100, false);
          $thumblarge = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),200,200, false);
          if ($thumbtiny) {

            $thumb = new ElggFile();
            $thumb->owner_guid = $object->owner_guid;
            $thumb->setMimeType('image/jpeg');

            $thumb->setFilename($prefix."tiny.jpg");
            $thumb->open("write");
            $thumb->write($thumbtiny);
            $thumb->close();

            $thumb->setFilename($prefix."small.jpg");
            $thumb->open("write");
            $thumb->write($thumbsmall);
            $thumb->close();

            $thumb->setFilename($prefix."medium.jpg");
            $thumb->open("write");
            $thumb->write($thumbmedium);
            $thumb->close();

            $thumb->setFilename($prefix."large.jpg");
            $thumb->open("write");
            $thumb->write($thumblarge);
            $thumb->close();

            $object->set("icon",true);
          }
        }
        break;
    }
  }
  return true;
}

/**
 * Overwrites the profile/icon view when the entity_id param is available
 *
 * @param $hook display
 * @param $entity_type view
 * @param $returnvalue
 * @param $params
 */
function itemicon_overwrite_hook($hook, $entity_type, $returnvalue, $params){
  global $CONFIG;

  $view = $params["view"];
  $vars = $params["vars"];
  $entity_id = $vars["entity_id"];

  if($view =="profile/icon" && !empty($entity_id)){
    $entity = get_entity($entity_id);
    $subtype = $entity->getSubtype();
    $icon = $entity->icon;
    if(in_array($subtype,$CONFIG->itemicon) && !empty($icon)){
      $vars["entity"]=$entity;
      $view_file = "itemicon/view";
      ob_start();

      $viewtype = elgg_get_viewtype();
      $view_location = elgg_get_view_location($view_file);
      include($view_location . "{$viewtype}/{$view_file}.php");

      $content = ob_get_clean();
      return $content;
    }
  }
}

register_elgg_event_handler('init','system','itemicon_init',1000);

?>