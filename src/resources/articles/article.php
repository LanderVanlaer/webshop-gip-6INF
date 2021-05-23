<?php global $product; ?>
<article class="article" data-specifications='<?= str_replace("'", '"', json_encode($product['specifications'])); ?>'>
    <a href="<?php echo $product["link"] ?>">
        <div class="brand bg-image thumbnail" style="background-image: url('/images/articles/<?= $product["src"] ?>')"></div>
        <div>
            <h4 class="product-name"><?= $product["name"] ?></h4>
            <div class="brand bg-image" style="background-image: url('/images/brands/<?= $product["brand"]["src"] ?>')">
            </div>
            <div class="star-rating">
                <div class="stars">
                    <?php
                        for ($i = 0; $i < $product["stars"]; $i++)
                            echo "<img class='star' src='/images/Icon_star_solid.svg' alt='Solid star'>";

                        for ($i = 0; $i < 5 - $product["stars"]; $i++)
                            echo "<img class='star' src='/images/Icon_star_outline.svg' alt='Empty star'>";
                    ?>
                </div>
                <h5 class="amount-of-reviews"><?php echo $product["amountOfReviews"] == 1 ? "{$product["amountOfReviews"]} review" : "{$product["amountOfReviews"]} reviews"; ?></h5>
            </div>
            <div class="description"><?= substr($product["description"], 0, 150) ?>...</div>
            <div class="bottom">
                <h3 class="price">&euro; <?= $product["price"] ?></h3>
                <div class="basket">
                    <img src="/images/Icon_basket-gradient.svg" alt="Basket">
                </div>
            </div>
        </div>
    </a>
</article>