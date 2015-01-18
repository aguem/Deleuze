<?php
/**
 * @file
 * Implementation to display the footer region.
 */
?>

<?php if ($content): ?>
  <footer id="<?php print $region; ?>" class="<?php print $classes; ?> clearfix" role="contentinfo">
    <?php print $content; ?>
  </footer>
<?php endif; ?>
