<?php
/**
 * Display the icon field
 *
 * @package ElggItemIcon
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Corporación Somos más - 2009; Diego Andrés Ramírez Aragón 2010
 * @link http://github.com/lowfill/itemicon
 *
 */
?>
<p>
	<label><?php echo elgg_echo("itemicon:icon"); ?><br />
	<?php
		echo elgg_view("input/file",array('internalname' => 'icon'));
	?>
	</label>
</p>
