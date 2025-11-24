<div class="product-card vinyl-card">
    <div class="product-image vinyl-image">
        <span class="vinyl-label">Vinilo</span>
        <span class="condition-badge <?php echo $conditionClass; ?>"><?php echo $condition; ?></span>
    </div>
    <div class="product-info">
        <h3><?php echo $title; ?></h3>
        <p class="artist"><?php echo $artist; ?></p>
        <p class="details"><?php echo $year; ?> • <?php echo $edition; ?></p>
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