<footer id="site-footer" class="bg-[#10422B] pt-12 pb-8 rounded-t-3xl">
    <div class="max-w-[1200px] mx-auto px-6 flex flex-col items-center gap-10">

        <!-- Logo -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/mnes-footer.png' ); ?>"
                 alt="<?php bloginfo( 'name' ); ?>"
                 class="w-60 h-auto">
        </a>

        <!-- Redes sociales -->
        <div class="flex items-center justify-center gap-5">
            <a href="#" aria-label="X" class="footer-social-btn">
                <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/x.svg' ); ?>" alt="X" width="22" height="22">
            </a>
            <a href="#" aria-label="YouTube" class="footer-social-btn">
                <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/youtube.svg' ); ?>" alt="YouTube" width="22" height="22">
            </a>
            <a href="#" aria-label="Facebook" class="footer-social-btn">
                <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/facebook.svg' ); ?>" alt="Facebook" width="22" height="22">
            </a>
            <a href="#" aria-label="Instagram" class="footer-social-btn">
                <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/instagram.svg' ); ?>" alt="Instagram" width="22" height="22">
            </a>
            <a href="#" aria-label="TikTok" class="footer-social-btn">
                <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/tiktok.svg' ); ?>" alt="TikTok" width="22" height="22">
            </a>
        </div>

        <!-- Contacto -->
        <ul class="flex flex-col gap-4 w-full">
            <li>
                <a href="#" class="footer-contact-item">
                    <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/whatsapp.svg' ); ?>" alt="" width="20" height="20" aria-hidden="true">
                    <span>+54 9 3764 13-8114</span>
                </a>
            </li>
            <li>
                <a href="#" class="footer-contact-item">
                    <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/phone.svg' ); ?>" alt="" width="20" height="20" aria-hidden="true">
                    <span>(0376) 4447539/40</span>
                </a>
            </li>
            <li>
                <a href="#" class="footer-contact-item">
                    <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/mail.svg' ); ?>" alt="" width="20" height="20" aria-hidden="true">
                    <span>promocionymarketing@misiones.tur.ar</span>
                </a>
            </li>
            <li>
                <a href="#" class="footer-contact-item">
                    <img src="<?php echo esc_url( TURISMO_URI . '/assets/images/icons/gps.svg' ); ?>" alt="" width="20" height="20" aria-hidden="true">
                    <span>Colón 1985 – Posadas – Misiones</span>
                </a>
            </li>
        </ul>

        <!-- Copyright -->
        <p class="text-white/40 text-sm text-center">
            &copy; <?php echo date( 'Y' ); ?> Ministerio de Turismo de Misiones
        </p>

    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
