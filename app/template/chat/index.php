<?php

echo $data['form'];

for ($i = 0; $i < count($data['messages']); $i++) { ?>
    <a href="<?php echo BASE_URL ?>/chat/show/<?php
        if ($data['authId'] == $data['messages'][$i]['to_id']) {
            echo $data['messages'][$i]['from_id'];
        } else {
            echo $data['messages'][$i]['to_id'];
        } ?>">
        <div class="message message-center">
            <div class="message-top">
                <div class="message-from">
                    <?php if ($data['authName'] == $data['messages'][$i]['to_name']) {
                        echo $data['messages'][$i]['from_name']; ?><span class="<?php if ($data['messages'][$i]['is_seen'] == 1) { echo 'hidden'; } ?> chat-N">N</span><?php
                    } else {
                        echo $data['messages'][$i]['to_name']; ?> <?php
                    } ?>
                </div>

                <div class="message-date">
                    <?= $data['messages'][$i]['date'] ?>
                </div>
            </div>

            <div class="full-message">
                <div class="full-message-subject">
                    <?= $data['messages'][$i]['subject'] ?>
                </div>

                <div class="full-message-text">
                    <?= $data['messages'][$i]['text'] ?>
                </div>
            </div>
        </div>
    </a>
<?php } ?>
