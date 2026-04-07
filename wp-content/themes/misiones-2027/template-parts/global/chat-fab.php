<?php
/**
 * Template Part: Chat FAB (Lupita AI) + Chat Panel
 */
?>

<!-- ════════════════════════════════════════════
     CHAT FAB
════════════════════════════════════════════ -->
<button class="chat-fab" aria-label="<?php esc_attr_e( 'Abrir chat con Lupita', 'misiones-2027' ); ?>" aria-expanded="false">
  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
  </svg>
</button>

<!-- ════════════════════════════════════════════
     CHAT PANEL
════════════════════════════════════════════ -->
<div class="chat-panel is-hidden" role="dialog" aria-label="<?php esc_attr_e( 'Chat con Lupita', 'misiones-2027' ); ?>" aria-modal="true">

  <!-- Header -->
  <div class="chat-panel__header">
    <div class="chat-panel__bot">
      <div class="chat-panel__avatar" aria-hidden="true">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" aria-hidden="true">
          <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
        </svg>
      </div>
      <div>
        <div class="chat-panel__name">Lupita</div>
        <div class="chat-panel__status">
          <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:var(--c-success);margin-right:4px;vertical-align:middle;" aria-hidden="true"></span>
          <?php esc_html_e( 'En línea · Responde al instante', 'misiones-2027' ); ?>
        </div>
      </div>
    </div>
    <button class="icon-btn icon-btn--plain btn-chat-close" style="color:var(--c-gray-400);" aria-label="<?php esc_attr_e( 'Cerrar chat', 'misiones-2027' ); ?>">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path d="M18 6L6 18M6 6l12 12"/>
      </svg>
    </button>
  </div>

  <!-- Messages -->
  <div class="chat-panel__messages">
    <div class="chat-bubble">
      ¡Hola! Soy <strong>Lupita</strong>, tu asistente virtual de turismo en Misiones. ¿En qué puedo ayudarte?
    </div>
    <div class="chat-suggestions" role="list" aria-label="<?php esc_attr_e( 'Preguntas frecuentes', 'misiones-2027' ); ?>">
      <?php
      $suggestions = [
        '¿Cómo llego a Iguazú?',
        'Alojamiento económico',
        'Eventos esta semana',
        'Turismo aventura',
      ];
      foreach ( $suggestions as $s ) :
      ?>
        <button class="chat-suggestion-chip" role="listitem"><?php echo esc_html( $s ); ?></button>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Input -->
  <div class="chat-panel__input">
    <input
      type="text"
      class="chat-input"
      placeholder="<?php esc_attr_e( 'Escribí tu consulta…', 'misiones-2027' ); ?>"
      aria-label="<?php esc_attr_e( 'Mensaje para Lupita', 'misiones-2027' ); ?>"
    >
    <button class="chat-send-btn" aria-label="<?php esc_attr_e( 'Enviar mensaje', 'misiones-2027' ); ?>">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </button>
  </div>

</div>
