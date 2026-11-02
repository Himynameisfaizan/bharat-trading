<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/custom-pages.php';

$contact = get_contact();

$m = page_meta('terms-condition.php', [
    'title'    => 'Terms & Conditions | Bharat Trading, Nashik',
    'desc'     => 'Terms that apply to enquiries, quotations and orders placed with Bharat Trading, merchant exporter of Indian onions, rice and spices.',
    'keywords' => 'terms and conditions, Bharat Trading, export terms, FOB CIF, merchant exporter terms',
]);
$page_title = $m['title']; $page_desc = $m['desc']; $page_keys = $m['keywords'];

$legal_key     = 'terms-condition';
$legal_title   = 'Terms & Conditions';
$legal_lead    = 'The ground rules for using this website and for doing business with Bharat Trading.';
$legal_updated = date('F j, Y', (int)filemtime(__FILE__));
$legal_glance  = [
    'Prices are quoted per enquiry. An order is binding only when it is confirmed in writing.',
    'Grade, size, packing and trade terms are agreed with you before each order.',
    'Destination customs duties and taxes are the buyer\'s responsibility unless agreed otherwise.',
    'Indian law applies to all dealings with us.',
];

$legal_sections = [
    ['About these terms',
        '<p>These terms apply to your use of this website and to enquiries, quotations and orders placed with <strong>' . e(COMPANY['legal']) . '</strong>, a proprietorship based in Nashik, Maharashtra, India. We are a merchant exporter and supplier: we source agricultural commodities and food products from suppliers and coordinate packing, inspection and shipment for our buyers.</p>'
      . '<p>By using this website or placing an enquiry or order with us, you agree to these terms. Where a written quotation, proforma invoice or order confirmation contains different terms, that document prevails for that order.</p>'],

    ['Using this website',
        '<p>The website is provided to give information about our products and to let you send us an enquiry. Product descriptions and images are indicative. Nothing on the website is an offer to sell. You agree not to:</p>' . legal_ul([
            'send false or misleading enquiries,',
            'use automated tools to copy the website or overload it,',
            'try to gain unauthorised access to the website or its data,',
            'use the website for any unlawful purpose.',
        ])],

    ['Enquiries, quotations and orders',
        '<p>Price is on enquiry because it depends on the product specification, quantity, packing, destination and the trade terms you choose. A quotation is valid for the period stated in it. An order is binding only when both parties have confirmed it in writing, and it then covers the product, specification, quantity, packing, price, payment terms, trade terms and shipment details stated in that confirmation.</p>'],

    ['Prices, taxes and trade terms',
        '<p>Trade terms such as FOB or CIF are agreed for each order. Unless the order confirmation says otherwise:</p>' . legal_ul([
            'applicable Indian taxes, where they apply, are shown in the quotation or invoice as the law requires;',
            'customs duties, import taxes, levies and clearance charges at the destination are the buyer\'s responsibility;',
            'risk in the goods passes as set out in the agreed trade term.',
        ])],

    ['Quality and natural variation',
        '<p>Our products are agricultural commodities. Small differences in colour, size, aroma or texture between lots are natural and are not defects. Grade, size, quality parameters and packing are agreed with you before the order, and quality inspection is arranged as mutually agreed. Goods are measured against the agreed specification.</p>'],

    ['Packing, shipment and delivery',
        '<p>Packing is customised to your requirement, subject to product and supplier capability. Shipment is coordinated as mutually agreed. Dates we give are estimates, not guarantees. See our <a href="' . e(url('shipping-return')) . '">Shipping &amp; Delivery Policy</a> for more.</p>'],

    ['Payment',
        '<p>Payment terms, mode of payment and any advance are agreed for each order and written in the quotation or proforma invoice. We do not take payments through this website.</p>'],

    ['Cancellations, claims and refunds',
        '<p>Cancellation of a confirmed order, claims for goods that do not match the agreed specification, and refunds are covered in our <a href="' . e(url('refund-policy')) . '">Refund &amp; Cancellation Policy</a>.</p>'],

    ['Events beyond our control',
        '<p>We are not responsible for delay or failure caused by events beyond our reasonable control, including bad weather or crop failure, government orders or changes in export or import rules, strikes, port or shipping disruption, transport breakdown, fire, flood or epidemic. We will inform you as soon as reasonably possible and work with you on a practical solution.</p>'],

    ['Intellectual property',
        '<p>The website text, design, logo and other material belong to ' . e(COMPANY['legal']) . ' or are used with permission. You may not copy or reuse them without our written consent.</p>'],

    ['Information on this website',
        '<p>Information about the uses of spices and other products is general and for culinary and trade information only. It is not medical advice. We try to keep the website accurate and up to date but do not promise that it is free of errors.</p>'],

    ['Limit of liability',
        '<p>To the extent the law allows, we are not liable for indirect or consequential loss, loss of profit or loss of business. Our liability for any claim relating to an order is limited to the value of the affected goods. Nothing in these terms limits liability that cannot be limited by law.</p>'],

    ['Governing law',
        '<p>These terms are governed by the laws of India. Any dispute will be subject to the jurisdiction of the courts at Nashik, Maharashtra, unless the parties agree otherwise in writing.</p>'],

    ['Changes to these terms',
        '<p>We may update these terms from time to time. The date at the top of this page shows when they were last changed. Terms agreed in writing for a confirmed order are not changed by later updates to this page.</p>'],

    ['Contact us',
        '<p>For any question about these terms, contact:</p>' . legal_contact_html($contact)],
];

require __DIR__ . '/includes/legal-layout.php';