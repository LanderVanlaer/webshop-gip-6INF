<?php global $product; ?>
<article class="article" data-id="<?= $product['id'] ?>" data-specifications='<?= str_replace("'", '"', json_encode($product['specifications'])); ?>'>
    <a href="<?php echo $product["link"] ?>">
        <div class="brand bg-image thumbnail" style="background-image: url('/images/articles/<?= $product["src"] ?>')"></div>
    </a>
    <div>
        <a href="<?php echo $product["link"] ?>">
            <h4 class="product-name"><?= $product["name"] ?></h4>
            <div class="brand bg-image" style="background-image: url('/images/brands/<?= $product["brand"]["src"] ?>')"></div>
            <div class="description"><?= substr($product["description"], 0, 220) ?>...</div>
        </a>
        <div class="bottom">
            <h3 class="price">&euro; <?= $product["price"] ?></h3>
            <div class="basket">
                <a href="/user/shopping-list/add?id=<?= $product['id'] ?>">
                    <img src="/images/Icon_basket-gradient.svg" alt="Basket">
                </a>
            </div>
        </div>
    </div>
</article>