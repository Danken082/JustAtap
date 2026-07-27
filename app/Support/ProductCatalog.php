<?php

namespace App\Support;

class ProductCatalog
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            'smart-tag' => [
                'id' => 'smart-tag',
                'name' => 'Smart Tag',
                'description' => 'NFC smart keychain tag for instant profile sharing.',
                'price' => 24.99,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Accessories',
                'colors' => ['Midnight Black', 'Arctic White', 'Coral Red'],
                'sizes' => ['Standard'],
                'slides' => [
                    [
                        'title' => 'Tap-ready keychain build',
                        'caption' => 'Compact NFC tag for everyday carry and instant digital sharing.',
                        'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=1200&q=80',
                    ],
                    [
                        'title' => 'Scratch-resistant finish',
                        'caption' => 'Durable shell made to keep your profile access looking clean.',
                        'image' => 'https://images.unsplash.com/photo-1468495244123-6c6f2b763bd8?auto=format&fit=crop&w=1200&q=80',
                    ],
                    [
                        'title' => 'Works with profile card URL',
                        'caption' => 'Perfect match for your NFC and QR-powered profile page.',
                        'image' => 'https://images.unsplash.com/photo-1512428559087-560fa5ceab42?auto=format&fit=crop&w=1200&q=80',
                    ],
                ],
            ],
            'smart-card' => [
                'id' => 'smart-card',
                'name' => 'Smart Card',
                'description' => 'Premium digital business card with NFC tap and QR.',
                'price' => 39.99,
                'image' => 'https://images.unsplash.com/photo-1556740738-b6a63e27c4df?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Cards',
                'colors' => ['Matte Black', 'Sand Gold', 'Ocean Blue', 'Pearl White'],
                'sizes' => ['Slim', 'Standard'],
                'slides' => [
                    [
                        'title' => 'NFC + QR dual sharing',
                        'caption' => 'One card, two ways to open your profile in seconds.',
                        'image' => 'https://images.unsplash.com/photo-1556742502-ec7c0e9f34b1?auto=format&fit=crop&w=1200&q=80',
                    ],
                    [
                        'title' => 'Fast scan profile access',
                        'caption' => 'Optimized for tap and scan moments during events and meetings.',
                        'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80',
                    ],
                    [
                        'title' => 'Clean wallet-friendly design',
                        'caption' => 'Minimal profile card styling with premium material feel.',
                        'image' => 'https://images.unsplash.com/photo-1556745757-8d76bdb6984b?auto=format&fit=crop&w=1200&q=80',
                    ],
                ],
            ],
            'smart-accessory' => [
                'id' => 'smart-accessory',
                'name' => 'Accessory Pack',
                'description' => 'Card holder and tag sleeve bundle.',
                'price' => 18.50,
                'image' => 'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Bundles',
                'colors' => ['Graphite', 'Forest Green', 'Stone Gray'],
                'sizes' => ['Small', 'Medium', 'Large'],
                'slides' => [
                    [
                        'title' => 'Protective sleeve pair',
                        'caption' => 'Bundle includes a card holder and smart tag sleeve.',
                        'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1200&q=80',
                    ],
                    [
                        'title' => 'Compact carry setup',
                        'caption' => 'Built for daily use with lightweight travel convenience.',
                        'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=1200&q=80',
                    ],
                    [
                        'title' => 'Everyday travel ready',
                        'caption' => 'Keeps your smart items protected while on the move.',
                        'image' => 'https://images.unsplash.com/photo-1517638851339-4aa32003c11a?auto=format&fit=crop&w=1200&q=80',
                    ],
                ],
            ],
        ];
    }
}
