    <h1>Test</h1>

    <?php PC_Hooks::pc_after_opening_footer_tag(); /* Framework hook wrapper */ ?>
    <?php get_sidebar( 'footer' ); // Adds support for the four footer widget areas ?>


	</footer>

</div><!-- #body-container -->
<?php wp_footer(); ?>

<?php PC_Hooks::pc_after_closing_footer_tag(); /* Framework hook wrapper */ ?>


</body>
</html>