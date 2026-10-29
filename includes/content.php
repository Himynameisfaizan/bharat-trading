<?php
/**
 * includes/content.php
 * Static, genuine content built only from the company details in the Go2ExportMart form.
 * Used as the default catalogue and page copy. Anything added through the admin panel
 * (products, categories, about sections) takes priority over this file.
 * Nothing here states a certification, number or capability that is not in that form.
 */

/** Terms that apply to every product (taken from the company form). */
function trade_terms(): array {
    return [
        ['Price', 'On enquiry, based on specification, quantity, packing, destination and agreed FOB or CIF terms'],
        ['Minimum order', 'As per customer requirement'],
        ['Packing', 'Customised as per buyer requirement'],
        ['Quality inspection', 'Arranged as mutually agreed'],
        ['Shipment terms', 'FOB or CIF, as agreed with the buyer'],
        ['Supplier type', 'Merchant exporter, APEDA RCMC registered'],
    ];
}

function enquiry_checklist(): array {
    return ['Product and variety or grade you need', 'Size range or specification, if any', 'Quantity per shipment and how often', 'Packing type and weight', 'Destination country and port', 'Preferred terms (FOB or CIF) and delivery window'];
}

function process_steps(): array {
    return [
        ['Enquiry', 'You tell us the product, quantity and destination. A short message is enough to start.'],
        ['Specification', 'We confirm grade, size, packing and marking with you before any quotation is made.'],
        ['Quotation', 'Price is quoted on your specification, quantity, packing, destination and FOB or CIF terms.'],
        ['Sourcing', 'We source against the agreed specification through our network of suppliers.'],
        ['Inspection', 'Quality inspection is arranged as mutually agreed before the goods move.'],
        ['Packing and shipment', 'Goods are packed as you require and shipment arrangements are coordinated with you.'],
    ];
}

function company_values(): array {
    return [
        ['search', 'Buyer-oriented sourcing', 'We start from what your market needs, not from what is easiest for us to stock.'],
        ['box', 'Customised packing', 'Packing type, weight and presentation follow the requirement you give us.'],
        ['shield', 'Registered and traceable', 'APEDA RCMC, IEC, GST and Udyam registrations, with the numbers shown openly on this site.'],
        ['globe', 'Export coordination', 'Documentation and shipment arrangements are coordinated with you as mutually agreed.'],
    ];
}

function company_faqs(): array {
    return [
        ['What does Bharat Trading supply?', 'Fresh Indian onions, Basmati rice (1121 White Sella, Golden Sella and other available Sella varieties), non-Basmati rice, turmeric, red chilli, cumin seeds, coriander seeds, black pepper and other Indian spices as per buyer requirements.'],
        ['Are you a manufacturer or a trader?', 'We are a merchant exporter and supplier. We do not run our own factory; products are sourced from suppliers against the specification agreed with you, and we coordinate packing and shipment.'],
        ['Which countries do you export to?', 'Qatar, Saudi Arabia and the United Arab Emirates are listed in our APEDA application. If your destination is different, send us the port and we will discuss it with you.'],
        ['What is your minimum order quantity?', 'It depends on the product and your requirement. Tell us the quantity you have in mind and we will confirm what is workable.'],
        ['How is pricing decided?', 'Prices are quoted per enquiry, based on product specification, quantity, packing, destination and the agreed FOB or CIF terms.'],
        ['Can the packing be customised?', 'Yes. Packing is arranged as per customer requirement, subject to product and supplier capability.'],
        ['How do you handle quality?', 'Grades and sizes are agreed with you in advance, and quality inspection is arranged as mutually agreed before shipment.'],
        ['Can I ask for samples?', 'You are welcome to raise this in your enquiry. We will confirm what is possible for the product you are interested in.'],
    ];
}

function product_faqs(string $name): array {
    return [
        ['What is the minimum order for ' . $name . '?', 'As per customer requirement. Share your quantity and we will confirm what is workable.'],
        ['How do I get a price?', 'Price is on enquiry because it depends on specification, quantity, packing, destination and FOB or CIF terms. Send an enquiry and we will respond with a quotation.'],
        ['Can packing be customised?', 'Yes, packing is arranged as per your requirement, subject to product and supplier capability.'],
        ['How is quality checked?', 'Quality inspection is arranged as mutually agreed before shipment.'],
    ];
}

/** Default catalogue, keyed by URL slug. */
function static_catalogue(): array {
    return [
        'fresh-indian-onions' => [
            'name' => 'Fresh Indian Onions', 'cat' => 'Fresh Produce', 'cat_slug' => 'fresh-produce', 'group' => 'onion',
            'desc' => 'Sourced in the size and grade you specify and packed for export.',
            'summary' => 'Fresh Indian onions sourced through our supplier network and prepared to the size, grade and packing you specify.',
            'about' => [
                'Onions are a staple in kitchens and food businesses around the world, and India is among the major onion-producing countries. Bharat Trading is based in Nashik, Maharashtra, a region long associated with the onion trade, and supplies fresh Indian onions to importers, wholesalers and food-service buyers.',
                'Needs differ from one market to another, so we do not work from a fixed catalogue. You give us the size range, grade, quantity and destination; we source against that requirement, arrange quality inspection as mutually agreed, and coordinate packing and shipment.',
            ],
            'facts' => [['Product', 'Fresh Indian onions'], ['Variety', 'As per buyer requirement'], ['Size and grade', 'As per buyer requirement'], ['Packing', 'Customised as per buyer requirement'], ['Origin', 'India'], ['Inspection', 'Arranged as mutually agreed']],
            'uses' => ['Importers and wholesalers', 'Food distributors and retailers', 'Food-service and catering businesses'],
        ],
        'basmati-rice' => [
            'name' => 'Basmati Rice', 'cat' => 'Rice', 'cat_slug' => 'rice', 'group' => 'rice',
            'desc' => '1121 White Sella, Golden Sella and other available Sella varieties.',
            'summary' => 'Basmati rice in 1121 White Sella, Golden Sella and other available Sella varieties, packed to buyer requirements.',
            'about' => [
                'Sella Basmati is parboiled in the paddy stage before milling, which is why the grains cook up firm and separate. It is widely used in biryani and catering kitchens. 1121 is known for its extra-long grain, and Golden Sella carries the golden-amber colour that parboiling gives.',
                'We supply 1121 White Sella, Golden Sella and other available Sella varieties. Grain specification, purity and packing are agreed with you before the order is confirmed, and we coordinate inspection and shipment as mutually agreed.',
            ],
            'facts' => [['Product', 'Basmati rice, Sella (parboiled)'], ['Varieties', '1121 White Sella, Golden Sella, other available Sella varieties'], ['Grain specification', 'As per buyer requirement'], ['Packing', 'Customised as per buyer requirement'], ['Origin', 'India'], ['Inspection', 'Arranged as mutually agreed']],
            'uses' => ['Importers and rice distributors', 'Wholesalers and retail packers', 'Restaurants and catering businesses'],
        ],
        'non-basmati-rice' => [
            'name' => 'Non-Basmati Rice', 'cat' => 'Rice', 'cat_slug' => 'rice', 'group' => 'rice',
            'desc' => 'Supplied to the specification agreed with the buyer.',
            'summary' => 'Non-Basmati rice supplied to the variety, grade and packing agreed with the buyer.',
            'about' => [
                'For buyers who need bulk rice outside the Basmati range, we source non-Basmati rice against your own specification. Tell us the variety, grade and any quality parameters you work to, and we confirm availability before quoting.',
                'Packing is customised to your requirement, and quality inspection and shipment are arranged as mutually agreed.',
            ],
            'facts' => [['Product', 'Non-Basmati rice'], ['Variety and grade', 'As per buyer requirement'], ['Quality parameters', 'As per buyer specification'], ['Packing', 'Customised as per buyer requirement'], ['Origin', 'India'], ['Inspection', 'Arranged as mutually agreed']],
            'uses' => ['Importers and bulk buyers', 'Food distributors', 'Food-processing businesses'],
        ],
        'turmeric' => [
            'name' => 'Turmeric', 'cat' => 'Spices', 'cat_slug' => 'spices', 'group' => 'spice',
            'desc' => 'Sourced to buyer specification with customised packing.',
            'summary' => 'Turmeric sourced to your specification and packed as you require.',
            'about' => [
                'Turmeric is valued for its deep colour and earthy flavour, and is used across cooking and food processing. India is the world\'s leading producer of turmeric.',
                'Buyers usually have a specific form and quality in mind. Share yours with us and we will source against it, with packing customised to your requirement and inspection arranged as mutually agreed.',
            ],
            'facts' => [['Product', 'Turmeric'], ['Form and grade', 'As per buyer requirement'], ['Quality parameters', 'As per buyer specification'], ['Packing', 'Customised as per buyer requirement'], ['Origin', 'India'], ['Inspection', 'Arranged as mutually agreed']],
            'uses' => ['Spice importers and distributors', 'Food manufacturers', 'Retail packers'],
        ],
        'red-chilli' => [
            'name' => 'Red Chilli', 'cat' => 'Spices', 'cat_slug' => 'spices', 'group' => 'spice',
            'desc' => 'Available as per the grade and quality you require.',
            'summary' => 'Red chilli sourced to the grade and quality you require, with customised packing.',
            'about' => [
                'Red chilli is bought for its heat and colour, and buyers differ on what matters most. Tell us the type, heat level, colour and form (whole or otherwise) you need, and we source against it.',
                'We arrange packing as per your requirement and coordinate inspection and shipment as mutually agreed.',
            ],
            'facts' => [['Product', 'Red chilli'], ['Type and form', 'As per buyer requirement'], ['Heat and colour', 'As per buyer specification'], ['Packing', 'Customised as per buyer requirement'], ['Origin', 'India'], ['Inspection', 'Arranged as mutually agreed']],
            'uses' => ['Spice importers and wholesalers', 'Food manufacturers', 'Retail packers'],
        ],
        'cumin-seeds' => [
            'name' => 'Cumin Seeds', 'cat' => 'Spices', 'cat_slug' => 'spices', 'group' => 'spice',
            'desc' => 'Bulk supply with packing arranged to your requirement.',
            'summary' => 'Cumin seeds in bulk, sourced to your specification and packed as required.',
            'about' => [
                'Cumin is one of the most widely used spices in the kitchen and in food manufacturing, and India is a leading producer. We supply cumin seeds in bulk against the purity and cleaning standard you specify.',
                'Packing follows your requirement, and inspection and shipment are arranged as mutually agreed.',
            ],
            'facts' => [['Product', 'Cumin seeds'], ['Purity and cleaning', 'As per buyer specification'], ['Quantity', 'As per customer requirement'], ['Packing', 'Customised as per buyer requirement'], ['Origin', 'India'], ['Inspection', 'Arranged as mutually agreed']],
            'uses' => ['Spice importers and distributors', 'Food manufacturers', 'Retail packers'],
        ],
        'coriander-seeds' => [
            'name' => 'Coriander Seeds', 'cat' => 'Spices', 'cat_slug' => 'spices', 'group' => 'spice',
            'desc' => 'Sourced as per buyer requirements, packed as agreed.',
            'summary' => 'Coriander seeds sourced to buyer requirements and packed as agreed.',
            'about' => [
                'Coriander seeds are used whole and ground in cuisines around the world. We source them to the quality and cleaning standard you give us, and pack them the way your market needs.',
                'Share the specification and quantity with us and we will come back with a quotation; inspection and shipment are arranged as mutually agreed.',
            ],
            'facts' => [['Product', 'Coriander seeds'], ['Quality and cleaning', 'As per buyer specification'], ['Quantity', 'As per customer requirement'], ['Packing', 'Customised as per buyer requirement'], ['Origin', 'India'], ['Inspection', 'Arranged as mutually agreed']],
            'uses' => ['Spice importers and distributors', 'Food manufacturers', 'Retail packers'],
        ],
        'black-pepper' => [
            'name' => 'Black Pepper', 'cat' => 'Spices', 'cat_slug' => 'spices', 'group' => 'spice',
            'desc' => 'Quality inspection and shipment arranged as mutually agreed.',
            'summary' => 'Black pepper sourced to your grade, with inspection and shipment arranged as mutually agreed.',
            'about' => [
                'Black pepper has been traded from India for centuries and remains one of the most requested spices. Buyers usually specify grade and cleaning standard, and we source against what you give us.',
                'Packing is customised to your requirement, with quality inspection and shipment arranged as mutually agreed.',
            ],
            'facts' => [['Product', 'Black pepper'], ['Grade and cleaning', 'As per buyer specification'], ['Quantity', 'As per customer requirement'], ['Packing', 'Customised as per buyer requirement'], ['Origin', 'India'], ['Inspection', 'Arranged as mutually agreed']],
            'uses' => ['Spice importers and distributors', 'Food manufacturers', 'Retail packers'],
        ],
    ];
}
