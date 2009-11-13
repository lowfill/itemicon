<?php
/**
 * Elgg itemicon icon page
 *
 * @package ElggItemIcon
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2009
 * @link http://www.somosmas.org
 * 
 * @uses get_input("item_guid")
 * @uses get_input("size")
 */

global $CONFIG;
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$item_guid = get_input('item_guid');
$item = get_entity($item_guid);

$size = strtolower(get_input('size'));
if (!in_array($size,array('large','medium','small','tiny','master','topbar')))
$size = "medium";

$success = false;

$filehandler = new ElggFile();
$filehandler->owner_guid = $item->owner_guid;
$filehandler->setFilename("icons/{$item->guid}-{$item->title}{$size}.jpg");

$success = false;
if ($filehandler->open("read")) {
  if ($contents = $filehandler->read($filehandler->size())) {
    $success = true;
  }
}

//@todo Add suppor for default icons per type
if (!$success) {
  $contents = @file_get_contents($CONFIG->pluginspath . "items/graphics/default{$size}.jpg");
}

header("Content-type: image/jpeg");
header('Expires: ' . date('r',time() + 864000));
header("Pragma: public");
header("Cache-Control: public");
header("Content-Length: " . strlen($contents));
echo $contents;

?>