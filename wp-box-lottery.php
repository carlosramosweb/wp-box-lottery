<?php

/**
 *
 * @link              https://criacaocriativa.com
 * @since             1.0.0
 * @package           WP_Box_Lottery 
 *
 * @wordpress-plugin
 * Plugin Name:       Resultados Loteria da Caixa
 * Plugin URI:        https://plugins.criacaocriativa.com
 * Description:       Plugin personalizado para exibir os resultados da loteria federal da Caixa Economica do Brasil na página ou post do WordPress.
 * Version:           1.0.0
 * Author:            carlosramosweb
 * Author URI:        https://criacaocriativa.com
 * Donate link: 	  https://donate.criacaocriativa.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-box-lottery
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die();
}

if ( ! class_exists( 'WP_Box_Lottery' ) ) {
		
	class WP_Box_Lottery {	

		public $lottery;
		public $game;

		public function __construct() {	
			register_activation_hook( __FILE__, array( $this, 'plugin_activate' ) );
			add_action( 'init', array( $this, 'plugin_start' ) );			
		}

		public function plugin_start() {
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_action_links_settings' ) );
			add_action('admin_menu', array( $this, 'register_admin_menu' ));	
			add_action( 'wp_footer',  array( $this, 'product_single_loop_styles' ), 10 );	
			add_shortcode('wp_box_lottery', array( $this, 'get_show_shortcode' ) );					
		}

		public static function plugin_activate() {	
			add_option( 'Activated_Plugin', 'wp-box-lottery' );	  
			if ( is_admin() && get_option( 'Activated_Plugin' ) == 'wp-box-lottery' ) {				
				$settings = array(
					'enabled' => 'yes'
				);				
				update_option( 'wp_box_lottery_settings', $settings, 'yes' );
			}
		}

		public static function get_herokuapp_api_url() {	
			return 'https://loteriascaixa-api.herokuapp.com/api/';
		}

		public function get_herokuapp_api_results() {	
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->get_herokuapp_api_url() . $this->lottery . $this->game );
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			$headers = array();
			$headers[] = 'Accept: application/json';
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$json = curl_exec($ch);
			$result = json_decode( $json, true );
			curl_close($ch);
			return $result;
		}

		public function get_show_shortcode( $atts ) {
			$plugin_dir_url = plugin_dir_url( __DIR__ ) . 'wp-box-lottery/';
			$this->lottery = (isset($atts['lottery'])) ? $atts['lottery'] . '/' : '';
			$this->game = (isset($atts['game'])) ? $atts['game'] . '/' : '';
			$results = $this->get_herokuapp_api_results();
			@include( plugin_dir_path( __FILE__ ) . 'includes/html-shortcode.php' );
			return;
		}

		public function get_total_premium( $awards ) {
			$total = 0;
			if (isset($awards)) {
				foreach ($awards as $key => $row) {
					if ($row['vencedores']) {
						$premium = str_replace('R$', '', $row['premio']);
						$premium = str_replace('.', '', $premium);
						$premium = str_replace(',', '.', $premium);
						$premium = trim($premium);
						$total += (intval($row['vencedores']) * $premium);
					}
				}
			}
			return number_format($total, 2, ',', '.');
		}

		public function plugin_action_links_settings( $links ) {
			$action_links = array(
				'settings' 	=> '<a href="' . admin_url('admin.php?page=settings-box-lottery') . '" title="Configurações" class="error">Configurações</a>',
				'donate' 	=> '<a href="' . esc_url( 'https://donate.criacaocriativa.com') . '" title="Doação Plugin" class="error">Doação</a>',
			);
			return array_merge( $action_links, $links );
		}

		public function register_admin_menu() {		
			add_menu_page(
				'Configurações - Loteria da Caixa - Resultados',
				'Resultados da Loteria',
				'manage_options',
				'settings-box-lottery',
				array( $this, 'settings_box_lottery_page_callback'),
				'dashicons-awards',
				6
			);
		}

		public function settings_box_lottery_page_callback() {
			@include_once( plugin_dir_path( __FILE__ ) . 'includes/settings-admin.php' );
		}

		public static function product_single_loop_styles() {
			?>
			<style type="text/css">
			</style>
			<script type="text/javascript">
	        jQuery(document).ready(function() {
	        });
			</script>
			<?php
		}

	}
	$FlatsomCore = new WP_Box_Lottery();
}