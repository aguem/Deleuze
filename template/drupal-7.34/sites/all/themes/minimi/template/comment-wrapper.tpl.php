<section id="comments" class="<?php print $classes; ?>"<?php print $attributes; ?>>

  <h2><?php print t('Comments'); ?></h2>

  <?php print render($content['comments']); ?>

  <?php if ($content['comment_form']): ?>
    <h3><?php print t('Add new comment'); ?></h3>
    <?php print render($content['comment_form']); ?>
  <?php endif; ?>

</section>
