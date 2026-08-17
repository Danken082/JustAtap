<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Guest Shop | Smart Tap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #f5f7ff;
            --muted: #bcc4db;
            --panel: #141b2a;
            --line: rgba(255, 255, 255, 0.12);
            --accent: #ff8a3d;
            --accent-2: #7dc4b6;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: var(--ink);
            background: radial-gradient(circle at 10% 0%, rgba(69, 104, 220, 0.35), transparent 30%), #0c1018;
        }

        .wrap {
            width: min(1100px, 92%);
            margin: 0 auto;
            padding: 28px 0 42px;
        }

        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .head a {
            color: #d7def3;
            text-decoration: none;
            font-weight: 700;
        }

        h1 {
            margin: 0;
            font-size: clamp(1.7rem, 4vw, 2.4rem);
        }

        .status {
            margin: 0 0 14px;
            border-radius: 10px;
            border: 1px solid rgba(112, 242, 167, 0.5);
            background: rgba(112, 242, 167, 0.13);
            color: #d8ffe8;
            padding: 10px 12px;
        }

        .error {
            margin: 0 0 14px;
            border-radius: 10px;
            border: 1px solid rgba(255, 121, 121, 0.5);
            background: rgba(255, 121, 121, 0.13);
            color: #ffd7d7;
            padding: 10px 12px;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 0 0 14px;
        }

        .chip {
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 7px 12px;
            background: rgba(255, 255, 255, 0.03);
            color: var(--ink);
            font-weight: 700;
            cursor: pointer;
        }

        .chip.is-active {
            border-color: var(--accent);
            background: rgba(255, 138, 61, 0.18);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .card {
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
            background: var(--panel);
            cursor: pointer;
        }

        .image {
            min-height: 180px;
            background: linear-gradient(140deg, var(--accent-2), #1f2534);
            background-size: cover;
            background-position: center;
            display: grid;
            place-items: center;
            font-size: 1.8rem;
            font-weight: 800;
        }

        .content {
            padding: 14px;
        }

        h2 {
            margin: 0;
            font-size: 1.24rem;
        }

        p {
            margin: 8px 0 12px;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .category {
            display: inline-flex;
            border: 1px solid rgba(125, 196, 182, 0.45);
            color: #d7fff5;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 8px;
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .price {
            font-size: 1.1rem;
            font-weight: 800;
        }

        .row .hint {
            font-size: 0.82rem;
            color: #dce5ff;
        }

        button {
            border: 0;
            border-radius: 999px;
            padding: 8px 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .modal {
            position: fixed;
            inset: 0;
            background: rgba(4, 7, 12, 0.75);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 20px;
        }

        .modal.is-open {
            display: flex;
        }

        .modal-card {
            width: min(900px, 100%);
            background: #101726;
            border: 1px solid var(--line);
            border-radius: 16px;
            overflow: hidden;
        }

        .modal-body {
            display: grid;
            grid-template-columns: 1.1fr 1fr;
        }

        .slides {
            position: relative;
            min-height: 340px;
            background: linear-gradient(140deg, #17325b, #15233d 60%, #111827);
            display: grid;
            grid-template-rows: 1fr auto;
            align-items: flex-end;
            overflow: hidden;
        }

        .slide-image {
            grid-row: 1 / -1;
            grid-column: 1;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.5;
        }

        .slide-overlay {
            grid-row: 2;
            grid-column: 1;
            z-index: 1;
            padding: 20px;
            background: linear-gradient(180deg, transparent, rgba(6, 10, 18, 0.84));
        }

        .slide-title {
            font-size: 1.35rem;
            font-weight: 800;
            margin: 0;
        }

        .slide-caption {
            margin: 8px 0 0;
            color: #d0dbf7;
        }

        .slider-controls {
            position: absolute;
            top: 12px;
            right: 12px;
            display: flex;
            gap: 6px;
        }

        .slider-controls button {
            border-radius: 10px;
            padding: 7px 10px;
            background: rgba(255, 255, 255, 0.14);
            color: white;
        }

        .modal-info {
            padding: 16px;
        }

        .modal-info h3 {
            margin: 0;
            font-size: 1.45rem;
        }

        .modal-fields {
            display: grid;
            gap: 10px;
            margin-top: 14px;
        }

        label {
            display: block;
            font-size: 0.85rem;
            color: #d1dbf4;
            margin-bottom: 5px;
            font-weight: 700;
        }

        select {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            font: inherit;
        }

        .modal-actions {
            display: flex;
            gap: 8px;
            margin-top: 14px;
        }

        .add-btn {
            background: #edf2ff;
            color: #111827;
        }

        .ghost {
            border: 1px solid var(--line);
            background: transparent;
            color: #fff;
        }

        @media (max-width: 900px) {
            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .modal-body {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="wrap">
        <header class="head">
            <h1>Guest Shop</h1>
            <a href="{{ route('cart.index') }}">View Cart ({{ $cartCount }})</a>
        </header>

        @if (session('status'))
            <p class="status">{{ session('status') }}</p>
        @endif

        @if ($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif

        <div class="filters" aria-label="Product categories">
            <button class="chip is-active" type="button" data-category="all">All</button>
            @foreach ($categories as $category)
                <button class="chip" type="button" data-category="{{ $category }}">{{ $category }}</button>
            @endforeach
        </div>

        <section class="grid" aria-label="Sample products">
            @foreach ($products as $product)
                <article
                    class="card"
                    data-product-card
                    data-product='@json($product)'
                    data-category="{{ $product['category'] }}"
                >
                    <div class="image" style="background-image: linear-gradient(140deg, rgba(20, 30, 52, 0.15), rgba(12, 18, 30, 0.7)), url('{{ $product['image'] }}');"></div>
                    <div class="content">
                        <span class="category">{{ $product['category'] }}</span>
                        <h2>{{ $product['name'] }}</h2>
                        <p>{{ $product['description'] }}</p>
                        <div class="row">
                            <span class="price">₱{{ number_format($product['price'], 2) }}</span>
                            <span class="hint">Tap to choose color &amp; size</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>
    </main>

    <section class="modal" id="product_modal" aria-hidden="true">
        <article class="modal-card" role="dialog" aria-modal="true" aria-label="Product options">
            <div class="modal-body">
                <div class="slides">
                    <div class="slider-controls">
                        <button type="button" id="slide_prev" aria-label="Previous slide">Prev</button>
                        <button type="button" id="slide_next" aria-label="Next slide">Next</button>
                    </div>
                    <img id="slide_image" class="slide-image" src="" alt="Product slide preview">
                    <div class="slide-overlay">
                        <p class="slide-title" id="slide_title"></p>
                        <p class="slide-caption" id="slide_caption"></p>
                    </div>
                </div>

                <div class="modal-info">
                    <p class="category" id="modal_category"></p>
                    <h3 id="modal_name"></h3>
                    <p id="modal_description"></p>
                    <p class="price" id="modal_price"></p>

                    <form method="POST" id="variant_form">
                        @csrf
                        <div class="modal-fields">
                            <div>
                                <label for="modal_color">Select Color</label>
                                <select id="modal_color" name="color" required></select>
                            </div>
                            <div>
                                <label for="modal_size">Select Size</label>
                                <select id="modal_size" name="size" required></select>
                            </div>
                        </div>

                        <div class="modal-actions">
                            <button class="add-btn" type="submit">Order</button>
                            <button class="ghost" type="button" id="modal_close">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </article>
    </section>

    <script>
        const cards = document.querySelectorAll('[data-product-card]');
        const chips = document.querySelectorAll('[data-category]');

        const modal = document.getElementById('product_modal');
        const modalClose = document.getElementById('modal_close');
        const slidePrev = document.getElementById('slide_prev');
        const slideNext = document.getElementById('slide_next');
        const slideImage = document.getElementById('slide_image');
        const slideTitle = document.getElementById('slide_title');
        const slideCaption = document.getElementById('slide_caption');

        const modalCategory = document.getElementById('modal_category');
        const modalName = document.getElementById('modal_name');
        const modalDescription = document.getElementById('modal_description');
        const modalPrice = document.getElementById('modal_price');
        const modalColor = document.getElementById('modal_color');
        const modalSize = document.getElementById('modal_size');
        const variantForm = document.getElementById('variant_form');
        const addRouteTemplate = @json(route('cart.add', ['product' => '__PRODUCT__']));

        let activeProduct = null;
        let activeSlideIndex = 0;

        function currentSlides() {
            if (!activeProduct) {
                return [];
            }

            const colorImages = activeProduct.color_images || {};
            const selectedColor = modalColor.value;
            const colorSlides = colorImages[selectedColor] || [];

            return colorSlides.length > 0 ? colorSlides : (activeProduct.slides || []);
        }

        function renderSlide() {
            if (!activeProduct) {
                return;
            }

            const slides = currentSlides();

            if (slides.length === 0) {
                slideImage.src = activeProduct.image || '';
                slideTitle.textContent = activeProduct.name;
                slideCaption.textContent = activeProduct.description;
                return;
            }

            const slide = slides[activeSlideIndex] || null;

            if (typeof slide === 'string') {
                slideImage.src = activeProduct.image || '';
                slideTitle.textContent = `${activeProduct.name} - Slide ${activeSlideIndex + 1}`;
                slideCaption.textContent = slide;
                return;
            }

            slideImage.src = slide.image || activeProduct.image || '';
            slideTitle.textContent = slide.title || `${activeProduct.name} - Slide ${activeSlideIndex + 1}`;
            slideCaption.textContent = slide.caption || activeProduct.description;
        }

        function fillSelect(selectEl, values) {
            selectEl.innerHTML = '';
            values.forEach((value) => {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = value;
                selectEl.appendChild(option);
            });
        }

        function openModal(product) {
            activeProduct = product;
            activeSlideIndex = 0;

            modalCategory.textContent = product.category;
            modalName.textContent = product.name;
            modalDescription.textContent = product.description;
            modalPrice.textContent = `₱${Number(product.price).toFixed(2)}`;

            fillSelect(modalColor, product.colors || []);
            fillSelect(modalSize, product.sizes || []);

            variantForm.action = addRouteTemplate.replace('__PRODUCT__', product.id);

            renderSlide();
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
        }

        modalColor.addEventListener('change', () => {
            activeSlideIndex = 0;
            renderSlide();
        });

        function closeModal() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
        }

        chips.forEach((chip) => {
            chip.addEventListener('click', () => {
                chips.forEach((item) => item.classList.remove('is-active'));
                chip.classList.add('is-active');

                const category = chip.dataset.category;

                cards.forEach((card) => {
                    if (category === 'all' || card.dataset.category === category) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        cards.forEach((card) => {
            card.addEventListener('click', () => {
                const raw = card.getAttribute('data-product');

                if (!raw) {
                    return;
                }

                openModal(JSON.parse(raw));
            });
        });

        slidePrev.addEventListener('click', () => {
            const slides = currentSlides();

            if (slides.length === 0) {
                return;
            }

            activeSlideIndex = (activeSlideIndex - 1 + slides.length) % slides.length;
            renderSlide();
        });

        slideNext.addEventListener('click', () => {
            const slides = currentSlides();

            if (slides.length === 0) {
                return;
            }

            activeSlideIndex = (activeSlideIndex + 1) % slides.length;
            renderSlide();
        });

        modalClose.addEventListener('click', closeModal);

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>
