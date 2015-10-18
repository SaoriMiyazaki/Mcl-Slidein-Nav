<?php
/* --------------------------------------------------------------
Plugin Name: Mcl slidein nav
Plugin URI: http://memocarilog.info/
Description: This Plugin will make with Custom menu Slidein nav 
Text Domain: mcl-slidein-nav
Domain Path: /languages
Version: 0.1
Author: Saori Miyazaki
Author URI: http://memocarilog.info/
License: GPL2
-------------------------------------------------------------- */
/*  
Copyright 2015 Saori Miyazaki ( email : saomocari@gmail.com )

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA */

/* -----------------------------------------------------------
	プラグイン有効語の設定リンク表示 
----------------------------------------------------------- */
function mcl_slidein_nav_action_links( $links, $file ) {
	if ( plugin_basename( __FILE__ ) == $file ) {
		$settings_link = sprintf( '<a href="%1$s">%2$s</a>', 
		admin_url( 'options-general.php?page=mcl-slidein-nav.php' ), 
		__( 'Settings' , 'mcl-slidein-nav' ) );
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'mcl_slidein_nav_action_links', 10, 2 );


/* -----------------------------------------------------------
	テキストドメイン読み込み 
----------------------------------------------------------- */
function mcl_slidein_nav_textdomain() {
	load_plugin_textdomain( 'mcl-slidein-nav', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action( 'plugins_loaded', 'mcl_slidein_nav_textdomain' );

/* -----------------------------------------------------------
	管理画面メニューへメニュー項目を追加
----------------------------------------------------------- */
function mcl_slidein_nav_add_admin_menu() {
	add_options_page(
		__( 'Mcl slidein nav Setting', 'mcl-slidein-nav' ),
		__( 'Mcl Slidein Nav', 'mcl-slidein-nav' ),
		'manage_options',
		'mcl-slidein-nav.php',
		'mcl_slidein_nav_admin' // 定義した関数を呼び出し
	);
}
add_action( 'admin_menu', 'mcl_slidein_nav_add_admin_menu' );

/* -----------------------------------------------------------
	管理画面 CSS ファイル読み込み 
----------------------------------------------------------- */
/*
function mcl_slidein_nav_admin($hook) {
    if ( 'settings_page_mcl_slidein_nav' != $hook ) {
        return;
    }
    wp_enqueue_style( 'mcl_head_clean_style', plugin_dir_url( __FILE__ ) . 'css/mcl-admin-style.css' );
}
add_action( 'admin_enqueue_scripts', 'mcl_slidein_nav_admin' );
*/
/* -----------------------------------------------------------
	アンインストール時のオプションデータ削除 
----------------------------------------------------------- */
function mcl_slidein_nav_uninstall() {
	delete_option( 'mcl_slidein_nav_options' );
}

/* -----------------------------------------------------------
	初期設定
----------------------------------------------------------- */
function mcl_slidein_nav_option_init() {
	// Settings API　オプション設定
	register_setting( 
		'mcl_slidein_nav_group', 
		'mcl_slidein_nav_options'
	);
	
	// アンインストール時の処理
	register_uninstall_hook( __FILE__, 'mcl_slidein_nav_uninstall' );
}
add_action( 'admin_init', 'mcl_slidein_nav_option_init' );

/* -----------------------------------------------------------
	デフォルト オプション値　を設定
----------------------------------------------------------- */
function mcl_slidein_nav_default_options() {
	$default_options = array(
		'name'      => '',
		'cnt_width' => 768,
		'position'  => 'left',
		'bg_color'  => '#666666',
	);
}

function get_mcl_slidein_nav_options() {
	return get_option( 'mcl_slidein_nav_options', mcl_slidein_nav_default_options() );
}

/* -----------------------------------------------------------
	管理画面を作成する関数を定義
----------------------------------------------------------- */
function mcl_slidein_nav_admin(){ 
	
	$options = get_mcl_slidein_nav_options();
	
	//var_dump($menu);
	var_dump( $options );
	
	if ( !current_user_can( 'manage_options' ) )  {    
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );    
    } 
?>	
	<div class="wrap">
	<h2><?php _e( 'Mcl Slidein Nav Setting', 'mcl-slidein-nav' ); ?></h2>
	
	<div class="postbox">
		<form method="post" action="options.php">
		<?php 
	    settings_fields( 'mcl_slidein_nav_group' );
	    do_settings_sections( 'mcl_slidein_nav_group' );
	    ?>	    
	    
	    <table class="form-table">
        <tbody>
          <tr>
            <th scope="row">
              <label for="name">select menu</label>
            </th>
            <td>
	            
	            <select id="name" name="mcl_slidein_nav_options[name]" > 
             	<?php 
	            $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );				
				if( isset( $menus ) ):
					foreach ( $menus as $menu ) : 					
					$option_name = $menu -> name;
				?>	            
					<option value="<?php echo esc_attr( $option_name ); ?>" 
					<?php if( $option_name == $options['name'] ){ echo 'selected'; } ?>>
					<?php echo esc_attr( $option_name ); ?>
					</option>

				<?php 
					endforeach;
				endif; ?>
				</select>
	        </td>
          </tr>
          
          <tr>
            <th scope="row">
            	<label for="position">nav position</label>
            </th>
            <td>
	        	<select id="position" name="mcl_slidein_nav_options[position]" > 
	            <?php 
	            $positions = array( 'left', 'right');				
				if( isset( $positions ) ):
					foreach ( $positions as $position ) : 					
				?>	
	            	<option value="<?php echo esc_attr( $position ); ?>"
	            	<?php if( $position == $options['position'] ){ echo 'selected'; } ?>>
	            	<?php echo esc_attr( $position ); ?>
	            	</option>
	            
	            <?php 
					endforeach;
				endif; ?>	
	        	</select>
	        </td>
          </tr>
          
          <tr>
            <th scope="row">
            	<label for="cont_width">content width</label>
            </th>
            <td>
	        	<input type="text" id="cont_width" name="mcl_slidein_nav_options[cont_width]" value="<?php echo esc_attr( $options['cont_width'] ); ?>"/> px
	        </td>
          </tr>
          
        </tbody>
      </table>
      <?php submit_button(); ?>
	    	    
		</form>
	</div>
	</div>
<?php
} 

/* -----------------------------------------------------------
	load CSS & JS 
----------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', 'mcl_slidein_nav_scripts' );
function mcl_slidein_nav_scripts(){
	
	wp_enqueue_style( 'mcl_slidein_nav_css' , plugins_url('css/mcl-slidein-nav.css', __FILE__) );
	wp_enqueue_script('mcl_slidein_nav_js', plugins_url( '/js/mcl-slidein-nav.js', __FILE__ ), array( 'jquery' ));
	
	$options = get_mcl_slidein_nav_options();
	$mcl_nav_options = array(
		'position' => $options['position'],
	);
	wp_localize_script( 'mcl_slidein_nav_js', 'mcl_slidein_nav', $mcl_nav_options );
} 

/* -----------------------------------------------------------
	output HTML header
----------------------------------------------------------- */
// ヘッダーへスタイル書き出し
add_action('wp_head', 'mcl_slidein_nav_style');
function mcl_slidein_nav_style(){ 

	$options = get_mcl_slidein_nav_options();
	if( !empty( $options['name'] ) ){ ?>
		<style type="text/css" >
			@media only screen and (min-width: <?php echo $options["cont_width"] ?>px) {
				.mcl_nav_btn{
					display: none;
				}
			}	
		</style>
	<?php 
	} 
} 

/* -----------------------------------------------------------
	output HTML footer
----------------------------------------------------------- */
add_action('wp_footer', 'mcl_slidein_nav_func', 100);
function mcl_slidein_nav_func(){
	
	$options = get_mcl_slidein_nav_options();
	if( !empty( $options['name'] ) ){ ?> 
				
		<div id="mcl_slidein_nav" class="mcl_nav_wrap <?php echo $options['position'] ?>">
		<button id="mcl_slidein_nav_btn" class="mcl_nav_btn">
			×
		</button>
		<ul id="mcl_slidein_nav_list" class="mcl_nav_list">
			<?php wp_nav_menu( 
				array(
					'menu'       => $options['name'],
					'container'  => false,
					'items_wrap' => '%3$s'
				) 
			); ?>
		</ul>
		</div>
		<div id="mcl_slidein_nav_layer" class="mcl_nav_layer"></div>
	<?php 
	} 
}  

