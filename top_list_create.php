
<div class="wrap">
    <h2>Add New List</h2>
    <?php 
    
    if (isset($message)): 
        ?>
        <div class="updated"><p><?php echo $message; ?></p></div>
        <a href="<?php echo admin_url('admin.php?page=top_list_list') ?>">&laquo; Back to locations list</a>
        <?php 
    elseif ($wrong == true) :
        ?>
        <div class="updated"><p>The rating must be a number between 0 and 5 and the name must be a number</p></div>
        <a href="<?php echo admin_url('admin.php?page=top_list_list') ?>">&laquo; Back to locations list</a>
        <?php 
    elseif (isset($exists)) :
        ?>
        <div class="updated"><p>The record already exists</p></div>
        <a href="<?php echo admin_url('admin.php?page=top_list_list') ?>">&laquo; Back to locations list</a>
        <?php 
    else:
        ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <p>Three capital letters for the ID</p>
            <table class='wp-list-table widefat fixed'>
                <tr>
                    <th class="ss-th-width">Name</th>
                    <td><input type="text" name="name" value="<?php echo $name; ?>" class="ss-field-width" /></td>
                </tr>
                <tr>
                    <th class="ss-th-width">Rating</th>
                    <td><input type="text" name="rating" value="<?php echo $rating; ?>" class="ss-field-width" /></td>
                </tr>
            </table>
            <input type='submit' name="insert" value='Save' class='button'>
        </form>

        <?php 
    endif; 
    ?>
</div>
