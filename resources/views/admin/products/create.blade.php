<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product | Just A Tap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0b1017;
            --panel: #131b28;
            --line: rgba(255, 255, 255, 0.12);
            --text: #f3f7ff;
            --muted: #bfcde8;
            --accent: #ff8447;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: var(--text);
            background: radial-gradient(circle at 0% 0%, rgba(74, 104, 213, 0.33), transparent 35%), var(--bg);
        }

        .wrap { width: min(900px, 95%); margin: 0 auto; padding: 22px 0 60px; }

        .top { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px; }
        .top a { color: #dde5f8; text-decoration: none; font-weight: 700; }

        h1 { margin: 0 0 16px; font-size: clamp(1.5rem, 3vw, 2.1rem); }

        .panel {
            border: 1px solid var(--line);
            background: var(--panel);
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 16px;
        }

        .panel h2 { margin: 0 0 12px; font-size: 1.15rem; }

        label {
            display: block;
            font-size: 0.85rem;
            color: #d1dbf4;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .field { margin-bottom: 14px; }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            font: inherit;
        }

        input[type="file"] {
            width: 100%;
            color: var(--muted);
        }

        textarea { min-height: 90px; resize: vertical; }

        .row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        .repeat-item {
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 10px;
            background: rgba(255, 255, 255, 0.03);
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .repeat-item .grow { flex: 1; }

        .icon-btn {
            border: 1px solid rgba(255, 121, 121, 0.5);
            background: rgba(255, 121, 121, 0.15);
            color: #ffd7d7;
            border-radius: 8px;
            padding: 8px 10px;
            cursor: pointer;
            font-weight: 700;
        }

        .add-item-btn {
            border: 1px dashed var(--line);
            background: transparent;
            color: #d7def3;
            border-radius: 10px;
            padding: 9px 14px;
            cursor: pointer;
            font-weight: 700;
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-row input { width: auto; }

        .submit-row {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .submit-btn {
            border: 0;
            border-radius: 999px;
            padding: 11px 18px;
            background: var(--accent);
            color: #1b1206;
            font-weight: 800;
            cursor: pointer;
        }

        .ghost-btn {
            border: 1px solid var(--line);
            background: transparent;
            color: #fff;
            border-radius: 999px;
            padding: 11px 18px;
            font-weight: 700;
            text-decoration: none;
        }

        .error-box {
            margin: 0 0 14px;
            border-radius: 10px;
            border: 1px solid rgba(255, 121, 121, 0.5);
            background: rgba(255, 121, 121, 0.13);
            color: #ffd7d7;
            padding: 10px 12px;
        }

        .error-box ul { margin: 6px 0 0; padding-left: 18px; }
    </style>
</head>
<body>
    <main class="wrap">
        <header class="top">
            <h1>Add Product</h1>
            <a href="{{ route('admin.products.index') }}">Back to Products</a>
        </header>

        @if ($errors->any())
            <div class="error-box">
                <strong>Please fix the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

            <section class="panel">
                <h2>Details</h2>
                <div class="field">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="field">
                    <label for="description">Description</label>
                    <textarea id="description" name="description">{{ old('description') }}</textarea>
                </div>
                <div class="row2">
                    <div class="field">
                        <label for="price">Price (PHP)</label>
                        <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price') }}" required>
                    </div>
                    <div class="field">
                        <label for="category">Category</label>
                        <input type="text" id="category" name="category" value="{{ old('category') }}">
                    </div>
                </div>
                <div class="field">
                    <label for="main_image">Main Image</label>
                    <input type="file" id="main_image" name="main_image" accept="image/*">
                </div>
                <div class="checkbox-row">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                    <label for="is_active" style="margin:0;">Active (visible in shop)</label>
                </div>
            </section>

            <section class="panel">
                <h2>Sizes</h2>
                <div id="sizes_container"></div>
                <button type="button" class="add-item-btn" id="add_size_btn">+ Add Size</button>
            </section>

            <section class="panel">
                <h2>Colors &amp; Variant Images</h2>
                <p class="muted" style="margin-top:0;color:var(--muted);">Add a color and upload one or more images for that color/variant.</p>
                <div id="colors_container"></div>
                <button type="button" class="add-item-btn" id="add_color_btn">+ Add Color</button>
            </section>

            <div class="submit-row">
                <a class="ghost-btn" href="{{ route('admin.products.index') }}">Cancel</a>
                <button type="submit" class="submit-btn">Save Product</button>
            </div>
        </form>
    </main>

    <template id="size_template">
        <div class="repeat-item">
            <div class="grow">
                <input type="text" name="sizes[]" placeholder="e.g. Standard" required>
            </div>
            <button type="button" class="icon-btn" data-remove-item>Remove</button>
        </div>
    </template>

    <template id="color_template">
        <div class="repeat-item">
            <div class="grow">
                <div class="field">
                    <label>Color Name</label>
                    <input type="text" name="colors[__INDEX__][name]" placeholder="e.g. Midnight Black" required>
                </div>
                <div class="field" style="margin-bottom:0;">
                    <label>Images</label>
                    <input type="file" name="colors[__INDEX__][images][]" accept="image/*" multiple>
                </div>
            </div>
            <button type="button" class="icon-btn" data-remove-item>Remove</button>
        </div>
    </template>

    <script>
        let colorIndex = 0;

        function addSize() {
            const template = document.getElementById('size_template');
            const container = document.getElementById('sizes_container');
            container.appendChild(template.content.cloneNode(true));
        }

        function addColor() {
            const template = document.getElementById('color_template');
            const container = document.getElementById('colors_container');
            const fragment = template.content.cloneNode(true);
            fragment.querySelectorAll('[name*="__INDEX__"]').forEach((el) => {
                el.name = el.name.replace('__INDEX__', colorIndex);
            });
            colorIndex += 1;
            container.appendChild(fragment);
        }

        document.getElementById('add_size_btn').addEventListener('click', addSize);
        document.getElementById('add_color_btn').addEventListener('click', addColor);

        document.addEventListener('click', (event) => {
            if (event.target.matches('[data-remove-item]')) {
                event.target.closest('.repeat-item').remove();
            }
        });

        // Seed with one size and one color field by default.
        addSize();
        addColor();
    </script>
</body>
</html>
