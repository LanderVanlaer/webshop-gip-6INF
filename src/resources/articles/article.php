<?php global $product; ?>
<article class="article">
    <a href="<?php echo $product["link"] ?>">
        <img class="thumbnail" src="<?php echo $product["src"] ?>" alt="<?php echo $product["name"] ?>">
        <div>
            <h4 class="product-name"><?php echo $product["name"] ?></h4>
            <img class="brand" src="<?php echo $product["brand"]["src"] ?>" alt="<?php echo $product["brand"]["name"] ?>">
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
            <div class="description"><?php echo $product["description"] ?></div>
            <div class="bottom">
                <h3 class="price">&euro; <?php echo $product["price"] ?></h3>
                <div class="basket">
                    <img src="/images/Icon_basket-gradient.svg" alt="Basket">
                </div>
            </div>
        </div>
    </a>
</article>