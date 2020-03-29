
<div class="wrap">
    <h2>Top List</h2>

    <?php if (isset($_POST['delete'])) { ?>
        <div class="updated"><p>Rating deleted</p></div>
        <a href="<?php echo admin_url('admin.php?page=top_list_list') ?>">&laquo; Back to products list</a>

    <?php } else if ($wrong == true) { ?>
        <div class="updated"><p>The rating must be a number between 0 and 5 and the name must be a number</p></div>
        <a href="<?php echo admin_url('admin.php?page=top_list_list') ?>">&laquo; Back to products list</a>

    <?php } else if (isset($_POST['update'])) { ?>
        <div class="updated"><p>Rating updated</p></div>
        <a href="<?php echo admin_url('admin.php?page=top_list_list') ?>">&laquo; Back to products list</a>

    <?php } else { ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <table class='wp-list-table widefat fixed'>
                <tr>
                    <th class="ss-th-width">Name</th>
                    <td>
                        <input type="text" name="name" value="<?php echo $name; ?>" class="ss-field-width" />
                    </td>
                </tr>
                <tr>
                    <th class="ss-th-width">Rating</th>
                    <td>
                        <input type="text" name="rating" value="<?php echo $rating; ?>" class="ss-field-width" />
                    </td>
                </tr>
            </table>
            <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
            <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Do you want to delete this element?')">
        </form>
    <?php } ?>
</div>
