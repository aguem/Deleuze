<?php
/**
 * @file
 * Default theme implementation to display a single Drupal page.
 */
?>

    <header role="banner" class="branding clearfix">
      <?php if ($logo): ?>
        <!-- #logo -->
        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
          <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
        </a>
        <!-- End of #logo -->
      <?php endif; ?>
      <?php if ($site_name): ?>
        <h1 id="site-name">
          <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
        </h1>
      <?php endif; ?>
      <?php if ($site_slogan): ?>
        <h2 id="site-slogan"><?php print $site_slogan; ?></h2>
      <?php endif; ?>
      <?php print render($page['header']); ?>
    </header>


    <?php print $messages; ?>


      <section role="main">
        <a id="main-content"></a>
        <!-- Page title -->
        <?php print render($title_prefix); ?>
        <?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>
        <?php print render($title_suffix); ?>
        <!-- Edit and View link when viewing the node as an editor/administrator -->
        <?php if ($tabs = render($tabs)): ?><div class="tabs"><?php print $tabs; ?></div><?php endif; ?>
        <!-- Help text for admin page -->
        <?php print render($page['help']); ?>
        <!-- Link for features in administraiton page, i.e. "Add menu" -->
        <?php if ($action_links = render($action_links)): ?><ul class="action-links"><?php print $action_links; ?></ul><?php endif; ?>
        <?php print render($page['content']); ?>
      </section> <!-- /main -->

      <?php print render($page['sidebar_first']); ?>

    <?php print render($page['footer']); ?>

<!--
regions[header] = Header
regions[help] = Help
regions[content] = Content
regions[sidebar_first] = Left sidebar
regions[footer] = Footer
-->
