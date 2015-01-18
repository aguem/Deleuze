<article class="<?php print $classes; ?>" <?php print $attributes; ?>>

  <?php print render($title_prefix); ?>
  <?php if ($title): ?>
    <h3<?php print $title_attributes; ?>>
      <?php print $title; ?>
    </h3>
  <?php endif; ?>
  <?php print render($title_suffix); ?>


  <footer>
    <?php if ($new): ?>
      <mark><?php print $new; ?></mark>
    <?php endif; ?>
    <span class="date"><?php print t('Published'); ?> <time pubdate><?php print $created; ?></time></span><br />
    <span class="changed">(<?php print t('Changed'); ?> <time pubdate><?php print $changed; ?></time>)</span><br />
	<span class="author"><?php print t('By'); ?> <?php print $author; ?></span><br />
    <?php print $permalink; ?>
  </footer>



  <div class="content"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['links']);
      print render($content);
    ?>

    <?php if ($signature): ?>
      <aside class="user-signature">
        <?php print $signature; ?>
      </aside>
    <?php endif; ?>
  </div>


  <?php print render($content['links']) ?>
</article>

