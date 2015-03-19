<article class="content_item">

    <h1>
        <?php $this->displayContentTitle($content, false); ?>
    </h1>

    <div class="pathway">
        <?php $this->displayPathway($content->catid, 1); ?>
    </div>

    <div class="icon right">
        <?php $this->PrintIcon($content, '<img src="/images/system/printButton.png" alt="Print" />'); ?>
        <?php $this->EmailIcon($content, '<img src="/images/system/emailButton.png" alt="Email" />'); ?>
    </div>

    <div class="vote">
        <?php $this->rating->displayVoteForm($content, $this->directory, $this->conf); ?>
    </div>

    <div class="content">
        <div class="content_block">
            <figure>
                <?php $this->loadFieldsInGroup($content, "Images", ""); ?>
                <figcaption>
                    <?php $this->loadFieldsInGroup($content, "Name", " "); ?>
                </figcaption>
            </figure>
            <div>
                <?php $this->loadFieldsInGroup($content, "Info", "<br>"); ?>
            </div>
        </div>

        <div class="content_block">
            <?php $this->loadFieldsInGroup($content, "Descrip", "<br>"); ?>
        </div>
    </div>

    <div class="tablestyle">

        <div class="tags">
            <?php echo $this->displayTags(); ?>
        </div>

        <div class="comment">
            <?php
            if ($this->isReviewAllowed()) {
                echo '&nbsp;&nbsp;';
                $this->comments->displayNumReviews($content, $this->reviews, $this->conf);
            }
            ?>
        </div>

        <div class="hits">
            <?php echo $this->displayContentHits($content); ?>
        </div>

    </div>

    <?php if ($this->isReviewAllowed()) { ?>
        <hr>
        <div class="comments">
            <h3>
                <?php echo BOSS_REVIEWS; ?>
            </h3>

            <div class="reviews">
                <?php $this->comments->displayReviews($content, $this->directory, $this->conf, $this->reviews); ?>
            </div>

            <h4>
                <?php echo BOSS_ADD_REVIEWS; ?>
            </h4>

            <div>
                <?php $this->comments->displayAddReview($this->directory, $content, $this->conf); ?>
            </div>
        </div>
    <?php } ?>

    <div class="edit"><?php $this->displayContentEditDelete($content); ?></div>
</article>
