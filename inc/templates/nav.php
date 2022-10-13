<div style="text-align: center;">
    <div class="float-left">
        <p>
            <a href="index.php?task=report">All Students</a>
            <?php
            if (hasPrevilege()) :
            ?>
                |
                <a href="index.php?task=add">Add New Students</a>
            <?php
            endif;
            ?>
            <?php
            if (isAdmin()) :
            ?>
                |
                <a href="index.php?task=seed">Seed</a>
            <?php
            endif;
            ?>
        </p>
    </div>
    <div class="float-right">
        <?php
        if (!isset($_SESSION['loggedin'])) :
        ?>
            <a href="auth.php">Log In</a>
        <?php
        else :
        ?>
            <a href="auth.php?logout=true">Log out(<?php echo $_SESSION['role']; ?>)</a>
        <?php
        endif;
        ?>
    </div>
    <p></p>
</div>