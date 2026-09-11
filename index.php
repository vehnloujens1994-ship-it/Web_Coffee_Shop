<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

$stmt = $pdo->query('SELECT * FROM menu_items ORDER BY id ASC LIMIT 4');
$featuredItems = $stmt->fetchAll();

$pageTitle = 'Dizon Coffee Roasters — Fresh Roasted. Locally Ground.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="background-image: url('<?= BASE_URL ?>/assets/img/hero-bg.png');">
    <div class="hero__decor hero__decor--ring"></div>
    <div class="hero__decor hero__decor--dot"></div>
    <div class="hero__decor hero__decor--ring-2"></div>
    <div class="hero__decor hero__decor--dot-2"></div>
    <div class="container">
        <div class="hero__content">
            <span class="eyebrow">Local Roastery — Dumaguete City</span>
            <h1>FRESH ROASTED.<br>LOCALLY GROUND.<br><span class="gold">ALWAYS LOCAL.</span></h1>
            <p>Small-batch coffee, sourced and roasted right here — brewed with proper equipment for a cup you can only get local.</p>
            <a href="<?= BASE_URL ?>/menu.php" class="btn btn--gold">Shop the Collection &rarr;</a>
        </div>
    </div>
</section>

<section class="mission" id="mission">
    <div class="container">
        <span class="eyebrow">Our Mission</span>
        <p class="mission__text">
            We're committed to serving our community the freshest coffee possible &mdash; sourced locally, ground in-house,
            and brewed with proper equipment to bring out its full flavor. From bean to cup, we believe great coffee starts
            with quality ingredients, careful craftsmanship, and a genuine connection to the neighborhood we serve.
        </p>
    </div>
</section>

<section class="process">
    <div class="container">
        <h2>From Bean to Cup</h2>
        <p class="process__sub">Four steps, done right, every single day.</p>
        <div class="process__grid">
            <div class="process__step">
                <div class="process__icon">&#127793;</div>
                <h4>Source</h4>
                <p>We partner with local growers for the freshest green beans.</p>
            </div>
            <div class="process__step">
                <div class="process__icon">&#128293;</div>
                <h4>Roast</h4>
                <p>Small batches, roasted in-house for peak flavor.</p>
            </div>
            <div class="process__step">
                <div class="process__icon">&#9881;</div>
                <h4>Grind</h4>
                <p>Ground fresh to order, never left sitting on a shelf.</p>
            </div>
            <div class="process__step">
                <div class="process__icon">&#9749;</div>
                <h4>Brew</h4>
                <p>Brewed with proper equipment for the best possible cup.</p>
            </div>
        </div>
    </div>
</section>

<section class="menu-preview">
    <div class="container">
        <h2>What We Pour</h2>
        <p class="menu-preview__sub">Fresh grind, brewed to order.</p>
        <div class="menu-grid">
            <?php foreach ($featuredItems as $item): ?>
                <div class="menu-card">
                    <?php if (!empty($item['image'])): ?>
                        <img class="menu-card__image" src="<?= BASE_URL ?>/uploads/menu/<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>">
                    <?php else: ?>
                        <div class="menu-card__placeholder">&#9749;</div>
                    <?php endif; ?>
                    <span class="menu-card__category"><?= e($item['category']) ?></span>
                    <h3><?= e($item['name']) ?></h3>
                    <p><?= e($item['description']) ?></p>
                    <div class="menu-card__price"><?= formatPrice($item['price']) ?></div>
                    <a href="<?= BASE_URL ?>/menu.php" class="btn btn--dark btn--small btn--full">Add to Bag</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="visit" id="visit" style="background-image: linear-gradient(100deg, rgba(20,13,8,0.95) 0%, rgba(20,13,8,0.9) 55%, rgba(20,13,8,0.85) 100%), url('<?= BASE_URL ?>/assets/img/visit-bg.png');">
    <div class="container">
        <div class="visit__inner">
            <div>
                <h2>Visit the Roastery</h2>
                <p>123 Real Street</p>
                <p>Dumaguete City, Negros Oriental</p>
                <p class="visit__hours-label">Hours</p>
                <p>Mon &ndash; Sat: 7:00 AM &ndash; 6:00 PM</p>
                <p>Sun: 8:00 AM &ndash; 4:00 PM</p>
                <br>
                <a href="https://maps.google.com" target="_blank" rel="noopener" class="btn btn--gold">Get Directions</a>
            </div>
            <div class="visit__map">
                <span>&#128205;</span>
                Find Us
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
