<div class="product-card cd-card">
    <div class="product-image cd-image">
        <span class="cd-label">CD</span>
    </div>
    <div class="product-info">
        <h3><?php echo $title; ?></h3>
        <p class="artist"><?php echo $artist; ?></p>
        <p class="genre"><?php echo $genre; ?> • <?php echo $year; ?></p>
        <div class="rating">
            <?php echo $rating; ?> <span class="rating-count">(<?php echo $ratingCount; ?>)</span>
        </div>
        <div class="price-section">
            <span class="price">$<?php echo $price; ?></span>
            <?php if (isset($originalPrice)): ?>
                <span class="original-price">$<?php echo $originalPrice; ?></span>
            <?php endif; ?>
        </div>
        <button class="add-to-cart">Añadir al Carrito</button>
    </div>
</div>