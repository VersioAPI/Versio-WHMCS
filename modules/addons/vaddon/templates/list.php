<?php if (!empty($view['errorMessage'])): ?>
    <?php echo $view['errorMessage']; ?>
<?php endif; ?>

<?php if (!empty($view['products'])): ?>
    <table width="100%" cellspacing="1" cellpadding="3" border="0" class="datatable">
        <tbody>
            <tr>
                <th><?php echo $view['lang']['productid']; ?></th>
                <th><?php echo $view['lang']['producttype']; ?></th>
                <th><?php echo $view['lang']['productname']; ?></th>
				<th><?php echo $view['lang']['currency']; ?></th>
                <th><?php echo $view['lang']['price']; ?></th>
                <th><?php echo $view['lang']['max_years']; ?></th>
                <th><?php echo $view['lang']['san_support']; ?></th>
            </tr>
            <?php
			foreach ($view['products'] as $item): ?>
                <tr>
                    <td><?php echo $item->id; ?></td>
                    <td><?php echo $item->type; ?></td>
                    <td><?php echo $item->brand_name; ?></td>
					<td><?php echo $item->currency;?></td>
                    <td><?php echo $item->price; ?></td>
                    <td><?php echo $item->max_period; ?></td>
                    <td><?php echo $item->number_of_domains; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>

<a href="<?php echo $view['global']['mod_url'] ?>&action=default"><?php echo 'Back'; ?></a>
