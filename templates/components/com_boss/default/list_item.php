<article class="simple_item">

    <h2>
        <?php $this->displayContentTitle($content); ?>
    </h2>

    <div class="cat">
        / <?php $this->displayCategoryTitle($content, 3); ?> /
    </div>

    <div class="vote">
        <?php $this->rating->displayVoteResult($content, $this->directory, $this->conf); ?>
    </div>

    <div class="date">
        <?php $this->displayContentDate($content); ?>
    </div>

    <div class="content">
        <?php if ($this->countFieldsInGroup("CatShort")) {
            $this->loadFieldsInGroup($content, "CatShort", "&nbsp;");
        } ?>
    </div>

    <div class="tablestyle">

        <div class="tags">
            <?php echo $this->displayListTags($content); ?>
        </div>

        <div class="comments">
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

    <div class="more right">
        <?php echo $this->displayContentLinkMore($content); ?>
    </div>

    <div class="edit">
        <?php $this->displayContentEditDelete($content); ?>
    </div>

</article>