<?php
/**
 * Elgg itemicon icon page
 *
 * @package ElggItemIcon
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Corporación Somos más - 2009; Diego Andrés Ramírez Aragón 2010
 * @link http://github.com/lowfill/itemicon
 *
 * @uses get_input("item_guid")
 * @uses get_input("size")
 */
global $CONFIG;
$item = $vars['entity'];

$subtype = $item->getSubtype();
if (($item instanceof ElggObject) && in_array($subtype,$CONFIG->itemicon)) {
  // Get size
  if (!in_array($vars['size'],array('small','medium','large','tiny','master','topbar'))){
    $vars['size'] = "medium";
  }
  // Get any align and js
  if (!empty($vars['align'])) {
    $align = " align=\"{$vars['align']}\" ";
  } else {
    $align = "";
  }

  if ($icontime = $vars['entity']->icontime) {
    $icontime = "{$icontime}";
  } else {
    $icontime = "default";
  }
?>
<div class="itemicon">
  <a href="<?php echo $vars['entity']->getURL(); ?>"class="icon"><img src="<?php echo $vars['entity']->getIcon($vars['size']); ?>" border="0" <?php echo $align; ?> title="<?php echo $name; ?>" <?php echo $vars['js']; ?> /></a>
</div>
<?php
}
?>