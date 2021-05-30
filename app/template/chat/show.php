<div id="chat-room" class="chat-room">

    <?php for ($i=0; $i<count($data['messagesOfRoom']); $i++) { ?>
        <?php if ($data['messagesOfRoom'][$i]['from_id'] == $data['authId']) { ?>

            <div class="message message-right">
                <div class="message-top">
                    <div class="message-date">
                        <?= $data['messagesOfRoom'][$i]['date'] ?>
                    </div>

                    <div class="message-from">
                        <?= $data['messagesOfRoom'][$i]['from_name'] ?>
                    </div>
                </div>

                <div class="full-message">
                    <div class="full-message-subject">
                        <?= $data['messagesOfRoom'][$i]['subject'] ?>
                    </div>

                    <div class="full-message-text">
                        <?= $data['messagesOfRoom'][$i]['text'] ?>
                    </div>
                </div>
            </div>

        <?php } else { ?>

            <div class="message">
                <div class="message-top">
                    <div class="message-from">
                        <?= $data['messagesOfRoom'][$i]['from_name'] ?>
                    </div>

                    <div class="message-date">
                        <?= $data['messagesOfRoom'][$i]['date'] ?>
                    </div>
                </div>

                <div class="full-message">
                    <div class="full-message-subject">
                        <?= $data['messagesOfRoom'][$i]['subject'] ?>
                    </div>

                    <div class="full-message-text">
                        <?= $data['messagesOfRoom'][$i]['text'] ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<?php echo $data['form']; ?>

<script>
    let chatHistory = document.getElementById("chat-room");
    chatHistory.scrollTop = chatHistory.scrollHeight;
</script>


