<?php
/**
 * Template Part: Experience Profiles + Quiz CTA
 */

$profiles = [
  [ 'id' => 'adventure', 'label' => 'Aventura',   'count' => 23, 'color' => '#2d8653', 'desc' => 'Kayak, trekking, rappel',    'icon' => '<path d="m8 3 4 8 5-5 5 15H2L8 3z"/>' ],
  [ 'id' => 'family',    'label' => 'Familia',    'count' => 17, 'color' => '#3b82f6', 'desc' => 'Parques, playas, campings',  'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>' ],
  [ 'id' => 'culture',   'label' => 'Cultura',    'count' => 12, 'color' => '#a855f7', 'desc' => 'Ruinas, museos, guaraní',   'icon' => '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>' ],
  [ 'id' => 'gastro',    'label' => 'Sabores',    'count' => 28, 'color' => '#f59e0b', 'desc' => 'Té, yerba, gastronomía',    'icon' => '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2M7 2v20M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>' ],
  [ 'id' => 'nature',    'label' => 'Naturaleza', 'count' =>  8, 'color' => '#10b981', 'desc' => 'Aves, selva, fauna',        'icon' => '<path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/>' ],
];
?>

<section id="experiencias" aria-label="<?php esc_attr_e( 'Experiencias por perfil', 'misiones-2027' ); ?>">

  <div class="section-title">
    <div class="section-title__text">
      <span class="section-title__icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
      </span>
      <h2 class="section-title__heading">Tu experiencia ideal</h2>
    </div>
  </div>

  <div class="experience-profiles">

    <!-- Profile buttons -->
    <div class="experience-profiles__scroll" role="list">
      <?php foreach ( $profiles as $profile ) : ?>
        <a
          href="#"
          class="profile-btn"
          data-color="<?php echo esc_attr( $profile['color'] ); ?>"
          data-profile="<?php echo esc_attr( $profile['id'] ); ?>"
          role="listitem"
        >
          <span class="profile-btn__icon" style="color:<?php echo esc_attr( $profile['color'] ); ?>;" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <?php echo $profile['icon']; // PHPCS: XSS OK — hardcoded SVG paths ?>
            </svg>
          </span>
          <span class="profile-btn__label" style="color:var(--c-gray-800);">
            <?php echo esc_html( $profile['label'] ); ?>
            <span class="profile-btn__count"><?php echo esc_html( $profile['count'] ); ?></span>
          </span>
          <span class="profile-btn__desc"><?php echo esc_html( $profile['desc'] ); ?></span>
        </a>
      <?php endforeach; ?>
    </div>


  </div>

</section>
