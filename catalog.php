<?php
$products = [
    // KR-921 (PNG изображения)
    [
        'id' => 1,
        'name' => 'Респираторное оборудование',
        'image' => '/goods/kr-921/photos/1.png',
        'category' => 'Реабилитация',
        'link' => '/catalog',
        'type' => 'large'
    ],
    [
        'id' => 2,
        'name' => 'Медицинские кровати',
        'image' => '/goods/kr-921/photos/2.png',
        'category' => 'Кровати',
        'link' => '/catalog',
        'type' => 'large'
    ],
    [
        'id' => 3,
        'name' => 'Компрессионный трикотаж',
        'image' => '/goods/kr-921/photos/3.png',
        'category' => 'Трикотаж',
        'link' => '/catalog',
        'type' => 'compact'
    ],
    [
        'id' => 4,
        'name' => 'Средства гигиены',
        'image' => '/goods/kr-921/photos/4.png',
        'category' => 'Гигиена',
        'link' => '/catalog',
        'type' => 'compact'
    ],
    [
        'id' => 5,
        'name' => 'Костыли и трости',
        'image' => '/goods/kr-921/photos/5.png',
        'category' => 'Опора',
        'link' => '/catalog',
        'type' => 'compact'
    ],

    // KR-929 (JPG изображения)
    [
        'id' => 6,
        'name' => 'Технические средства реабилитации',
        'image' => '/goods/kr-929/photos/1.jpg',
        'category' => 'Реабилитация',
        'link' => '/catalog',
        'type' => 'large'
    ],
    [
        'id' => 7,
        'name' => 'Ортопедические изделия',
        'image' => '/goods/kr-929/photos/2.jpg',
        'category' => 'Ортопедия',
        'link' => '/catalog',
        'type' => 'compact'
    ],
    [
        'id' => 8,
        'name' => 'Инвалидные коляски',
        'image' => '/goods/kr-929/photos/3.jpg',
        'category' => 'Коляски',
        'link' => '/catalog',
        'type' => 'large'
    ],
    [
        'id' => 9,
        'name' => 'Протезы и бандажи',
        'image' => '/goods/kr-929/photos/4.jpg',
        'category' => 'Протезирование',
        'link' => '/catalog',
        'type' => 'compact'
    ],
    [
        'id' => 10,
        'name' => 'Подъёмники для инвалидов',
        'image' => '/goods/kr-929/photos/5.jpg',
        'category' => 'Подъёмное оборудование',
        'link' => '/catalog',
        'type' => 'compact'
    ]
];


// Перемешиваем
shuffle($products);

// Разделяем по типам
$largeProducts = [];
$compactProducts = [];
foreach ($products as $product) {
    if ($product['type'] == 'large' && count($largeProducts) < 2) {
        $largeProducts[] = $product;
    } elseif ($product['type'] == 'compact' && count($compactProducts) < 2) {
        $compactProducts[] = $product;
    }

    // Когда набрали достаточно товаров
    if (count($largeProducts) >= 2 && count($compactProducts) >= 2) {
        break;
    }
}

// Если не хватило товаров нужного типа, добираем любыми
if (count($largeProducts) < 2) {
    $needed = 2 - count($largeProducts);
    foreach ($products as $product) {
        if (!in_array($product, $largeProducts, true)) {
            $largeProducts[] = $product;
            $needed--;
            if ($needed == 0) break;
        }
    }
}

if (count($compactProducts) < 2) {
    $needed = 2 - count($compactProducts);
    foreach ($products as $product) {
        if (!in_array($product, $compactProducts, true)) {
            $compactProducts[] = $product;
            $needed--;
            if ($needed == 0) break;
        }
    }
}
?>

<main class="content-box">
    <ul class="breadcrumb">
        <li><a href="/">Главная</a></li>
        <li><a href="/"><i class="fa fa-home"></i></a></li>
        <li>Каталог</li>
    </ul>

    <h1 class="text-left">Каталог</h1>


    <div class="row row-10">
        <!-- Большие карточки (первые две) -->
        <?php foreach ($largeProducts as $product): ?>
            <div class="col-12 col-md-6 col-lg-4 mb-3">
                <div class="light-box list-category-main-item">
                    <a href="<?= $product['link'] ?>">
                        <img loading="lazy" src="<?= $product['image'] ?>" class="mb-4" alt="<?= $product['name'] ?>">
                    </a>
                    <div class="list-category-main-item-title mb-4">
                        <?= htmlspecialchars($product['name']) ?>
                    </div>
                    <a href="<?= $product['link'] ?>" class="btn btn-small">Перейти в каталог</a>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Компактные карточки (третья колонка) -->
        <div class="col-12 col-lg-4 flex-column ">

            <?php foreach ($compactProducts as $product): ?>
                <div class="col-12 col-md-6 col-lg-12 ">
                    <div class="light-box list-category-main-item flex-column">
                        <div class="row">
                            <div class="col-12 col-md-5 smaller_img">
                                <a href="<?= $product['link'] ?>">
                                    <img loading="lazy" src="<?= $product['image'] ?>" class="mb-4" alt="<?= $product['name'] ?>">
                                </a>
                            </div>
                            <div class="col-12 col-md-7">
                                <div class="list-category-main-item-title mb-4">
                                    <?= htmlspecialchars($product['name']) ?>
                                </div>
                                <a href="<?= $product['link'] ?>" class="btn btn-small">Перейти в каталог</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

    </div>
    <div class="row mb-2">
        <div class="col-12 col-md-9">
            <ul class="list-item row">
            </ul>
        </div>
    </div>
    <div id="content"></div>
</main>