<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('eyebrow')->nullable();
            $table->text('summary')->nullable();
            $table->longText('body');
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->unsignedInteger('qty')->default(1);
            $table->unsignedInteger('price');
            $table->timestamps();
        });

        $now = now();
        DB::table('pages')->insert([
            [
                'slug' => 'about',
                'title' => 'Born in Vrindavan. Stitched as seva.',
                'eyebrow' => 'About DivineKraft',
                'summary' => 'In most homes, Thakurji is not an idol. He is family. So why should His clothes come off a shelf?',
                'body' => "DivineKraft began with a simple belief: a family that calls their Laddu Gopal “Lalla” or “Kanha” deserves a poshak made for Him alone. Not picked from a pile, not machine-stitched in bulk, but planned with you and embroidered by hand.\n\nWe work with karigar families in Vrindavan whose hands have shaped zardozi, gota-patti and resham for generations. Every poshak is started only after you order, carries the name you call Him, and comes home with the name of the karigar who made it.\n\n## What we stand for\n\nMade after you order, in His size. His name embroidered in the script you choose. A karigar’s name on every piece, so the hands behind the work are never anonymous.\n\n## The hands behind every poshak come first\n\nThe atelier is in Vrindavan. Orders close before the big festivals so every karigar has time to stitch without a rush that would show in the work.",
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'policies',
                'title' => 'Clear promises, in plain words',
                'eyebrow' => 'Policies',
                'summary' => 'Every poshak is made after you order. Please read these before you pay.',
                'body' => "## Made-to-order and returns\n\nEvery DivineKraft poshak is stitched after you order, in your Thakurji’s size and with His name embroidered. Because each piece is personalised, we can’t accept returns or exchanges.\n\nBefore stitching starts you approve the name spelling and, for custom orders, the final sketch. Please check these carefully.\n\n## If we make a mistake\n\nIf the poshak does not fit because we used the wrong size, or the embroidery does not match the spelling you approved, we remake it at no charge.\n\n## Payment\n\nOrders are prepaid by UPI, card or netbanking through Razorpay. We do not offer cash on delivery, because the work starts only after the order is confirmed.\n\n## Shipping\n\nMaking time is included in the arrival date on the product page. Once the parcel is booked, tracking appears on the order.",
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'size-guide',
                'title' => 'Find your Thakurji’s size in two minutes',
                'eyebrow' => 'Size guide',
                'summary' => 'Laddu Gopal poshaks come in sizes 0 to 6. Measure once, or send a photo and the team will tell you the size for free.',
                'body' => "## How to measure\n\nSeat Thakurji on a flat surface, the way He sits on the singhasan. Measure the height from the base to the top of the crown, and the width across the lap.\n\nIf you land between two sizes, choose the larger one so the poshak sits comfortably.\n\n## Not sure?\n\nSend a photo of your Thakurji next to a ruler on WhatsApp. The size check is free.",
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'contact',
                'title' => 'Talk to a designer',
                'eyebrow' => 'Contact',
                'summary' => 'Write to the Vrindavan atelier. A designer replies on WhatsApp.',
                'body' => "WhatsApp +91 82850 03300.\n\nThe studio is in Vrindavan. Share the size, a photo of your Thakurji, or the festival you are dressing Him for.",
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'custom',
                'title' => 'Tell us about your Thakurji',
                'eyebrow' => 'Custom design brief',
                'summary' => 'A design advisor calls you, plans the poshak with you, and sends an itemised quote. Nothing is charged until you approve it.',
                'body' => "Send the brief below. Two sketch revisions are included. Changes after the sketch is signed off are quoted separately.\n\nThe design consultation is free. You pay only when you approve the quote.",
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'faq',
                'title' => 'Frequently asked questions',
                'eyebrow' => 'Help centre',
                'summary' => 'Ordering, sizing, payment and care.',
                'body' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $faqs = [
            ['How long does a poshak take?', 'Nitya Seva designs ship in 7–10 days, Utsav in 12–15 days and Heirloom pieces in 20–30 days. The delivery date on the product already includes making time.'],
            ['Can I order a design exactly as shown?', 'Yes. Choose the size, colour and the name to embroider, and pay at checkout. Want changes? Send a custom brief and plan it with the team.'],
            ['When do festival orders close?', 'Orders close before Janmashtami, Radhashtami and Diwali so every karigar has time. The date is shown on the announcement bar.'],
            ['How does a custom design work?', 'Send a brief with the photos you like. A design advisor calls you, then sends a concept and an itemised quote. You pay an advance, approve the sketch and the name, and stitching begins.'],
            ['Is the design consultation free?', 'Yes. You pay only when you approve the quote.'],
            ['How many changes can I make?', 'Two sketch revisions are included. Changes after the sketch is signed off are quoted separately.'],
            ['How do I know my Thakurji’s size?', 'Use the size guide, or send a photo with a scale and the team will tell you for free.'],
            ['Which scripts can you embroider?', 'Devanagari, English, or both, in gold or silver zari. You approve a preview before the name is stitched.'],
            ['Do you offer cash on delivery?', 'No. Each poshak is made specially for you, so orders are prepaid: UPI, cards and netbanking.'],
            ['Do you ship outside India?', 'Yes, to selected countries. Courier charges are confirmed before dispatch, and any import duty is paid by the recipient.'],
            ['Can I return a poshak?', 'Personalised items can’t be returned. If it doesn’t fit because of our error, we remake it free.'],
            ['How do I care for zari and stone work?', 'Spot clean or dry clean only. Store it folded in the keepsake box, away from moisture.'],
        ];
        foreach ($faqs as $index => [$question, $answer]) {
            DB::table('faqs')->insert([
                'question' => $question,
                'answer' => $answer,
                'sort' => $index + 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('pages');
    }
};
