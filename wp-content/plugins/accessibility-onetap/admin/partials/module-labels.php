<?php
/**
 * Content template for submenu page.
 *
 * @package    Accessibility_Onetap
 * @since      1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wrap">
	<?php onetap_load_template( 'admin/partials/header.php' ); ?>
	<div class="box-current-language" style="display: none;">
		<a href="<?php echo esc_url( admin_url() . 'admin.php?page=accessibility-onetap-settings' ); ?>" class="current-language">
			<ul style="display: none;">
				<li role="listitem" data-language="en">
					<button type="button">
						<?php esc_html_e( 'English', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/english.png' ); ?>" alt="flag">
					</button>			
				</li>
				<li role="listitem" data-language="de">
					<button type="button">
						<?php esc_html_e( 'Deutsch', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/german.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="es">
					<button type="button">
						<?php esc_html_e( 'Español', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/spanish.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="fr">
					<button type="button">
						<?php esc_html_e( 'Français', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/french.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="it">
					<button type="button">
						<?php esc_html_e( 'Italiano', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/italia.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="pl">
					<button type="button">
						<?php esc_html_e( 'Polski', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/poland.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="se">
					<button type="button">
						<?php esc_html_e( 'Svenska', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/swedish.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="fi">
					<button type="button">
						<?php esc_html_e( 'Suomi', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/finnland.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="pt">
					<button type="button">
						<?php esc_html_e( 'Português', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/portugal.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="ro">
					<button type="button">
						<?php esc_html_e( 'Română', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/rumania.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="si">
					<button type="button">
						<?php esc_html_e( 'Slovenščina', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/slowakia.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="sk">
					<button type="button">
						<?php esc_html_e( 'Slovenčina', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/slowenien.png' ); ?>" alt="flag">	
					</button>
				</li>					
				<li role="listitem" data-language="nl">
					<button type="button">
						<?php esc_html_e( 'Nederlands', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/netherland.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="dk">
					<button type="button">
						<?php esc_html_e( 'Dansk', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/danish.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="gr">
					<button type="button">
						<?php esc_html_e( 'Ελληνικά', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/greece.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="cz">
					<button type="button">
						<?php esc_html_e( 'Čeština', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/czech.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="hu">
					<button type="button">
						<?php esc_html_e( 'Magyar', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/hungarian.png' ); ?>" alt="flag">	
					</button>
				</li>									
				<li role="listitem" data-language="lt">
					<button type="button">
						<?php esc_html_e( 'Lietuvių', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/lithuanian.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="lv">
					<button type="button">
						<?php esc_html_e( 'Latviešu', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/latvian.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="ee">
					<button type="button">
						<?php esc_html_e( 'Eesti', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/estonian.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="hr">
					<button type="button">
						<?php esc_html_e( 'Hrvatski', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/croatia.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="ie">
					<button type="button">
						<?php esc_html_e( 'Gaeilge', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/ireland.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="bg">
					<button type="button">
						<?php esc_html_e( 'Български', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/bulgarian.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="no">
					<button type="button">
						<?php esc_html_e( 'Norsk', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/norwegan.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="tr">
					<button type="button">
						<?php esc_html_e( 'Türkçe', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/turkish.png' ); ?>" alt="flag">	
					</button>
				</li>
				<li role="listitem" data-language="id">
					<button type="button">
						<?php esc_html_e( 'Bahasa Indonesia', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/indonesian.png' ); ?>" alt="flag">	
					</button>
				</li>		
				<li role="listitem" data-language="pt-br">
					<button type="button">
						<?php esc_html_e( 'Português (Brasil)', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/brasilian.png' ); ?>" alt="flag">	
					</button>
				</li>	
				<li role="listitem" data-language="ja">
					<button type="button">
						<?php esc_html_e( '日本語', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/japanese.png' ); ?>" alt="flag">	
					</button>
				</li>	
				<li role="listitem" data-language="ko">
					<button type="button">
						<?php esc_html_e( '한국어', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/korean.png' ); ?>" alt="flag">	
					</button>
				</li>	
				<li role="listitem" data-language="zh">
					<button type="button">
						<?php esc_html_e( '简体中文', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/chinese-simplified.png' ); ?>" alt="flag">	
					</button>
				</li>	
				<li role="listitem" data-language="ar">
					<button type="button">
						<?php esc_html_e( 'العربية', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/arabic.png' ); ?>" alt="flag">	
					</button>
				</li>	
				<li role="listitem" data-language="ru">
					<button type="button">
						<?php esc_html_e( 'Русский', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/russian.png' ); ?>" alt="flag">	
					</button>
				</li>	
				<li role="listitem" data-language="hi">
					<button type="button">
						<?php esc_html_e( 'हिन्दी', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/hindi.png' ); ?>" alt="flag">	
					</button>
				</li>	
				<li role="listitem" data-language="uk">
					<button type="button">
						<?php esc_html_e( 'Українська', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/ukrainian.png' ); ?>" alt="flag">	
					</button>
				</li>	
				<li role="listitem" data-language="sr">
					<button type="button">
						<?php esc_html_e( 'Srpski', 'accessibility-onetap' ); ?>
						<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/serbian.png' ); ?>" alt="flag">	
					</button>
				</li>	
			</ul>
			<div class="image">
				<img src="<?php echo esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/english.png' ); ?>" alt="flag" class="active">
			</div>

			<span class="text-current-language">
				<strong class="language-editing"><?php esc_html_e( 'You’re currently editing', 'accessibility-onetap' ); ?></strong>
				<strong class="language-name"><?php esc_html_e( 'English', 'accessibility-onetap' ); ?></strong> 
				<strong class="language-labels"><?php esc_html_e( 'labels', 'accessibility-onetap' ); ?></strong> 
				<span class="language-description"><?php esc_html_e( 'Choose another language to edit its translations.', 'accessibility-onetap' ); ?></span>
			</span>
		</a>

		<a href="<?php echo esc_url( admin_url() . 'admin.php?page=accessibility-onetap-settings#change-language' ); ?>" class="button outline change-language">
			<?php esc_html_e( 'Change Language', 'accessibility-onetap' ); ?>
		</a>
	</div>	
	<?php $this->settings_api->show_forms(); ?>
	<?php onetap_load_template( 'admin/partials/footer.php' ); ?>
</div>