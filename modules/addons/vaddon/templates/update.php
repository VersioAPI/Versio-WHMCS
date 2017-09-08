<?php if (!empty($view['errorMessage'])): ?>
    <?php echo $view['errorMessage']; ?>
<?php else: ?>
    Products has been successfully synchronized with Versio.
<?php endif; ?>

<br/>

<a href="<?php echo $view['global']['mod_url'] ?>&action=default"><?php echo 'Back';  ?></a>
