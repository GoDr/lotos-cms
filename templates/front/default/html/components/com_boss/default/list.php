<aside class="category">
    <h1>
        <?php $this->displayCatTitle(); ?>
    </h1>

    <div class="pathway">
        <?php $this->displayPathway(0, 1); ?>
    </div>

    <nav class="subcats">
        <?php $this->displaySubCats(); ?>
    </nav>

    <blockquote>
        <?php $this->displayCatDescription(); ?>
    </blockquote>

    <div class="index">
        <?php $this->displayAlphaIndex($this->directory); ?>
    </div>

    <div class="search">
        <?php $this->displayOrderOption(); ?>
    </div>
</aside>

<section class="category">
    <?php $this->displayContents(); ?>

    <div class="pagecount">
        <?php $this->displayPagesCounter(); ?>
    </div>

    <div class="navigation">
        <?php echo $this->displayPagesLinks(); ?>
    </div>

</section>
