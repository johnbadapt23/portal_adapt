<section class="home-chat">
    <div class="container">
        <div class="inner">
            <span class="chat-image-container">
                <?php $image = get_sub_field( 'image' ); ?>
                <?php if ( $image ) { ?>
                    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                <?php } ?>
            </span>
            <h2 class="chat-title"><?php echo get_sub_field( 'title' ); ?></h2>
            <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
            <span class="button-container">
                <a class="button chat-button red-button" href="#"><?php echo get_sub_field( 'chat_button_text' ); ?></a>
            </span>
        </div>
    </div>
</section>
