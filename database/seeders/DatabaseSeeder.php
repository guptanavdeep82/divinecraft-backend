<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@divinekraft.com'],
            ['name' => 'DivineKraft Admin', 'password' => 'DivineKraft@123', 'role' => 'admin']
        );

        $categories = collect([
            'Nitya Seva',
            'Utsav',
            'Heirloom',
            'Jugal Jodi',
        ])->mapWithKeys(function (string $name, int $index) {
            $category = Category::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'sort' => $index]
            );

            return [$name => $category->id];
        });

        $products = [
            ['Mor Mukut Peela', 'mor-mukut-peela-poshak', 4850, 'Utsav', 'Utsav · Zardozi, stones', 'Bestseller', '#7A1E2C', '4 Dec', '99c51cc19ef35e1464d8d2573846f133', 'Peela silk with a border of hand-worked mor-pankh, a stone-studded peacock and a mukut motif. Comes with a matching mukut and patka.', 'Laddu Gopal', 'Peela', ['bestsellers', 'fast']],
            ['Mayur Rang', 'mayur-rang', 5400, 'Utsav', 'Utsav · Zardozi, stones', 'Bestseller', '#7A1E2C', '4 Dec', 'b5989acf165fdf017276f956966c1925', 'A side-facing peacock poshak in zardozi and stones, stitched to His size.', 'Laddu Gopal', 'Kesari', ['bestsellers']],
            ['Hans Jodi Stone', 'hans-jodi-stone', 12400, 'Heirloom', 'Heirloom · Stone work', 'Bestseller', '#7A1E2C', '19 Dec', 'd366a4f2ddf15f4b6365b213c0a74bab', 'A matched hans pair with stone work for Radha and Kanha.', 'Radha Krishna', 'Ivory', ['bestsellers', 'vivah']],
            ['Nritya Mor Peela', 'nritya-mor-peela', 3200, 'Utsav', 'Utsav · Resham, zardozi', 'Bestseller', '#7A1E2C', '4 Dec', '934c7631087da1ca07df879df48b1149', 'A lighter peela poshak with resham and zardozi, made for daily utsav seva.', 'Laddu Gopal', 'Peela', ['bestsellers']],
            ['Mor Chakra Zardozi', 'mor-chakra-zardozi', 9700, 'Heirloom', 'Heirloom · Zardozi collar', 'Bestseller', '#7A1E2C', '19 Dec', 'e05eea7b0a24cbccba1fc7ecd402ba53', 'A heavy zardozi collar with a mor chakra, for winter and festival seva.', 'Laddu Gopal', 'Maroon', ['bestsellers', 'sardi']],
            ['Rangeela Kamal', 'rangeela-kamal', 4200, 'Utsav', 'Utsav · Resham', 'New', '#1F5E45', '4 Dec', '6c8b5180806f0b3f31905b7b0831b0be', 'Turquoise and pink resham with a kamal motif.', 'Laddu Gopal', 'Firozi', ['new']],
            ['Kairi Mor Pankh', 'kairi-mor-pankh', 4850, 'Utsav', 'Utsav · Sequins, stones', 'New', '#1F5E45', '4 Dec', 'b91ce095858e92b7e07c2cf71b5b8a85', 'Kairi green with sequins and a peacock feather border.', 'Laddu Gopal', 'Tota green', ['new', 'fast']],
            ['Neelkamal Resham', 'neelkamal-resham', 2450, 'Nitya Seva', 'Nitya Seva · Resham', 'New', '#1F5E45', '29 Nov', '211bc59b6dde1cddf12b721c44fcf19a', 'A daily neel resham poshak that can be ready on a short order.', 'Laddu Gopal', 'Peacock blue', ['new', 'fast']],
            ['Van Mor Gota', 'van-mor-gota', 7200, 'Utsav', 'Utsav · Gota, fringe', 'New', '#1F5E45', '4 Dec', '4da02c408340cca173272c167cabc92e', 'Gold and green gota with a peacock and a fringe hem.', 'Laddu Gopal', 'Tota green', ['new', 'sardi']],
            ['Shahi Mor Velvet', 'shahi-mor-velvet', 8600, 'Heirloom', 'Heirloom · Velvet, stones', 'Sardi', '#0F4D3A', '19 Dec', '00f81a265cffcaef86d2d0fd56a349a7', 'A winter velvet poshak with stone peacock work.', 'Laddu Gopal', 'Peacock blue', ['sardi']],
            ['Moti Hans Ivory', 'moti-hans-ivory', 6400, 'Heirloom', 'Heirloom · Moti, stones', 'Vivah Panchami', '#8E1F4A', '19 Dec', '099df3db2817e17bf0bce51733883d0c', 'Ivory silk with moti and hans work for the divine wedding.', 'Radha Krishna', 'Ivory', ['vivah']],
            ['Neel Moti Kairi', 'neel-moti-kairi', 6800, 'Utsav', 'Utsav · Moti, sequins', 'Vivah Panchami', '#8E1F4A', '4 Dec', 'dcf20fc07739ccbd76b2a14b3ca33edd', 'Blue floral silk with moti and sequins.', 'Laddu Gopal', 'Peacock blue', ['vivah']],
        ];

        foreach ($products as $index => $row) {
            [$name, $slug, $price, $category, $tier, $badge, $color, $eta, $image, $description, $deity, $colour, $groups] = $row;
            $product = Product::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $categories[$category],
                    'name' => $name,
                    'price' => $price,
                    'tier' => $tier,
                    'badge' => $badge,
                    'badge_color' => $color,
                    'eta' => $eta,
                    'image' => '/images/'.$image.'.jpg',
                    'description' => $description,
                    'deity' => $deity,
                    'colour' => $colour,
                    'is_active' => true,
                    'sort' => $index,
                ]
            );
            $product->groups()->delete();
            foreach ($groups as $sort => $group) {
                ProductGroup::query()->create([
                    'product_id' => $product->id,
                    'group' => $group,
                    'sort' => $sort,
                ]);
            }
        }

        $banners = [
            ['Sardi Seva · Nov to Feb', 'Keep Him warm this winter', 'Velvet poshaks, woollen sets, topi, shawl and razai, hand-embroidered in Vrindavan with His name.', 'Shop Sardi Seva', '/collections', 'Browse all poshaks', 'Order today, arrives by 29 Nov', '/images/00f81a265cffcaef86d2d0fd56a349a7.jpg', 'Shahi Mor Velvet Poshak', '/images/211bc59b6dde1cddf12b721c44fcf19a.jpg', 'Winter favourite', 'Velvet + stones', '#5A1220'],
            ['Vivah Panchami · Mon, 14 Dec', 'Ivory & pearl for the divine wedding', 'Moti, hans and stone-work poshaks for Ram-Sita vivah utsav, with matching shringaar.', 'Shop Vivah Panchami', '/collections', 'See festival store', 'Order by 28 Nov for on-time delivery', '/images/099df3db2817e17bf0bce51733883d0c.jpg', 'Moti Hans Ivory Poshak', '/images/d366a4f2ddf15f4b6365b213c0a74bab.jpg', '27 days to go', 'Order by 28 Nov', '#8E1F4A'],
            ['Custom design', 'Your design, in His size', 'Send a photo of any poshak you love. Our designers plan it with you. Nothing to pay until you approve.', 'Start a custom design', '/custom', 'How it works', 'Free design consult on WhatsApp', '/images/6c8b5180806f0b3f31905b7b0831b0be.jpg', 'Rangeela Kamal Resham Poshak', '/images/dcf20fc07739ccbd76b2a14b3ca33edd.jpg', 'Name included', 'लल्ला', '#123F6B'],
        ];

        foreach ($banners as $index => $row) {
            Banner::query()->updateOrCreate(
                ['title' => $row[1]],
                [
                    'eyebrow' => $row[0],
                    'subtitle' => $row[2],
                    'cta' => $row[3],
                    'cta_href' => $row[4],
                    'cta2' => $row[5],
                    'note' => $row[6],
                    'image' => $row[7],
                    'image_alt' => $row[8],
                    'image2' => $row[9],
                    'chip_top' => $row[10],
                    'chip' => $row[11],
                    'background' => $row[12],
                    'sort' => $index,
                    'is_active' => true,
                ]
            );
        }

        foreach ([
            'whatsapp' => '918285003300',
            'whatsapp_label' => '+91 82850 03300',
            'announce_1' => 'Sardi Seva is live: velvet & woollen poshaks',
            'announce_2' => 'His name embroidered free',
            'announce_3' => 'Free shipping above ₹2,499',
            'announce_4' => 'Vivah Panchami: order by 28 Nov',
        ] as $key => $value) {
            Setting::put($key, $value);
        }

        $stories = [
            [
                'Dressing Laddu Gopal for Janmashtami',
                'dressing-laddu-gopal-for-janmashtami',
                'A small order of seva for the night He arrives: poshak, mukut, and the yellow that Vrindavan keeps for Him.',
                "Janmashtami is not a new wardrobe. It is the same Thakurji, dressed as if the gopis had one more evening.\n\nStart with a peela or kesari poshak. The colour carries the night, and a mor-pankh border keeps the silhouette from looking plain on a small vigraha. If the mukut is separate, set it last, after the patka is tied and the jewellery sits flat.\n\nMeasure Him before you order. A poshak that is a size too long folds over the feet and hides the flute. Write the height in the note, and if you want His name on the patka, say so when you pay. Embroidery is optional. The poshak is the seva.",
                '/images/99c51cc19ef35e1464d8d2573846f133.jpg',
            ],
            [
                'Gold zari or silver zari',
                'gold-zari-or-silver-zari',
                'Both belong in a mandir. The choice is about the metal already on Him, not about which one is grander.',
                "Gold zari warms a peela, rani, or kesari silk. It sits next to a mukut that already has a yellow stone and does not argue with it.\n\nSilver zari is the quieter line. It belongs on neel, ivory, and white, and on a Radha-Krishna jodi where one side should not outshine the other.\n\nIf you are unsure, look at the jewellery He already wears. Match the metal, then choose the colour of the silk. Personalisation can take either zari. Leaving the name off is also a complete order.",
                '/images/b5989acf165fdf017276f956966c1925.jpg',
            ],
            [
                'A size note before you order',
                'a-size-note-before-you-order',
                'Thakurji is measured in inches, not in age. Three numbers are enough for the studio to cut the poshak.',
                "Write down the height from the base to the top of the crown, the width across the shoulders, and the distance around the waist if the poshak closes at the back.\n\nLaddu Gopal sizes are often spoken as numbers, but two vigrahas called size 3 are rarely the same. The note on the order is where those inches should go.\n\nA designer confirms the size on WhatsApp after payment. If the poshak is for a festival with a fixed date, say the date in the same note so the stitching queue can see it.",
                '/images/d366a4f2ddf15f4b6365b213c0a74bab.jpg',
            ],
            [
                'What a shringaar box is for',
                'what-a-shringaar-box-is-for',
                'The poshak is the garment. The box is everything that finishes Him: mukut, mala, patka, and a place to keep them.',
                "A complete shringaar is the poshak plus the pieces that sit on it. A mukut without a matching patka looks borrowed. A mala that is too long covers the embroidery you asked for.\n\nOrder the poshak first. Add the name only if you want it stitched. Then, if the festival needs it, ask for the mukut and mala in the same note so they are cut to that garment and not to a different one.\n\nThe box is how it travels. It is not a separate product you have to understand before you can buy the dress.",
                '/images/4da02c408340cca173272c167cabc92e.jpg',
            ],
        ];

        foreach ($stories as $story) {
            Blog::query()->updateOrCreate(
                ['slug' => $story[1]],
                [
                    'title' => $story[0],
                    'excerpt' => $story[2],
                    'body' => $story[3],
                    'image' => $story[4],
                    'author' => 'DivineKraft',
                    'published_at' => now()->subDays(1),
                ]
            );
        }

        $reviews = [
            ['Meera Sharma', 'Jaipur', 'The peela poshak arrived packed like a seva box. The mukut sits flat, and the zari has not snagged once.', 5],
            ['Radha Agarwal', 'Vrindavan', 'We sent His inches on WhatsApp and the Janmashtami poshak came back to that size, name stitched in Devanagari.', 5],
            ['Ankit Mishra', 'Lucknow', 'Ivory and moti for Vivah Panchami. Quiet work. The stones do not shout over the silk.', 5],
            ['Priya Desai', 'Ahmedabad', 'They confirmed the measurement before a single cut. That is the part I was worried about, and it was right.', 4],
        ];

        foreach ($reviews as $index => $review) {
            Review::query()->updateOrCreate(
                ['quote' => $review[2]],
                ['name' => $review[0], 'city' => $review[1], 'rating' => $review[3], 'sort' => $index, 'is_active' => true]
            );
        }

        $clips = [
            ['POSHAK', 'Mor Mukut Peela', '/images/99c51cc19ef35e1464d8d2573846f133.jpg', '/product/mor-mukut-peela-poshak'],
            ['MUKUT', 'Mayur Rang', '/images/b5989acf165fdf017276f956966c1925.jpg', '/product/mayur-rang'],
            ['JODI', 'Hans Jodi Stone', '/images/d366a4f2ddf15f4b6365b213c0a74bab.jpg', '/product/hans-jodi-stone'],
            ['ZARI', 'Mor Chakra Zardozi', '/images/e05eea7b0a24cbccba1fc7ecd402ba53.jpg', '/product/mor-chakra-zardozi'],
            ['SARDI', 'Shahi Mor Velvet', '/images/00f81a265cffcaef86d2d0fd56a349a7.jpg', '/product/shahi-mor-velvet'],
            ['VIVAH', 'Moti Hans Ivory', '/images/099df3db2817e17bf0bce51733883d0c.jpg', '/product/moti-hans-ivory'],
        ];

        foreach ($clips as $index => $clip) {
            Video::query()->updateOrCreate(
                ['title' => $clip[1]],
                ['label' => $clip[0], 'poster' => $clip[2], 'href' => $clip[3], 'sort' => $index, 'is_active' => true]
            );
        }
    }
}
