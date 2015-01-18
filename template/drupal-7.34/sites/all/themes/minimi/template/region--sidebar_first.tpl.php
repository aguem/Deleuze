<?php
/**
 * @file
 * Implementation to display the first sidebar region.
 */
?>

<?php if ($content): ?>
  <aside id="<?php print $region; ?>" class="<?php print $classes; ?>">
    <?php print $content; ?>
  </aside>
<?php endif; ?>
