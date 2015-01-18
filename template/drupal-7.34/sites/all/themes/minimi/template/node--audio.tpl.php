<article>
  <header>
    <?php print render($title_prefix); ?>
     <?php if (!$page): ?>
      <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
    <?php endif; ?>
    <?php print render($title_suffix); ?>
  </header>
    <?php
    // locate file and make available to page
      // get the file location
      $uri = ($content['field_audio']['#items'][0]['uri']);
      // transform uri (public://file_name.mp3) into url (http://example.com/files/default/file_name.mp3)
      $url = file_create_url($uri);
      // get the file name (for fallback in audio tag)
      $file_name = ($content['field_audio']['#items'][0]['filename']);
    ?>

  <?php 
  // print only body of node as field_audio must be enclosed in audio tag
    print render($content['body']);
  ?>
  <audio controls>
    <source src="<?php print $url; ?>" type='audio/mpeg; codecs="mp3"'>
    <!-- Fallback for old browsers -->
    <a href="<?php print $url; ?>"><?php print $file_name; ?></a>
  </audio>
</article>