
<div class="wrap">
    <h2>Top List</h2>

    <?php if (isset($_POST['delete'])) { ?>
        <div class="updated"><p>Product deleted</p></div>
        <a href="<?php echo admin_url('admin.php?page=top_list_list') ?>">&laquo; Back to products list</a>
    <?php } else { ?>

        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=top_list_create'); ?>">Add New</a>
                
            </div>
            <br class="clear">
        </div>

        <table class='wp-list-table widefat fixed striped posts'>
            <tr>
                <th class="manage-column ss-list-width">Name</th>
                <th class="manage-column ss-list-width">Rating</th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <!-- <td class="manage-column ss-list-width"><?php echo $row->id; ?></td> -->
                    <td class="manage-column ss-list-width"><?php echo $row->name; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->rating; ?></td>
                    <td class="manage-column ss-list-width"><a href="<?php echo admin_url('admin.php?page=top_list_update&id=' . $row->id); ?>">Update</a></td>
                    <td class="manage-column ss-list-width">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=top_list_list">
                            <input type='hidden' name="delete" value="<?php echo $row->id; ?>">
                            <input class="deleteproduct" type='submit' value='Delete'>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php } ?>
</div>
