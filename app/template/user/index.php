<div class="user-container">
    <?php for ($i = 0; $i < count($data['users']); $i++) { ?>

        <div class="one-user">
            <div class="user-number">
                <?php echo $i + 1; ?>
            </div>

            <div class="user-name">
                <?php echo $data['users'][$i]['name'] ?>
            </div>

            <div class="user-message">
                <a href="<?php echo BASE_URL ?>/chat/show/<?php echo $data['users'][$i]['id']; ?>">message</a>
            </div>
        </div>

    <?php } ?>
</div>


