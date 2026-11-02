<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/custom-pages.php';

$contact = get_contact();

$m = page_meta('shipping-return.php', [
    'title'    => 'Shipping & Delivery Policy | Bharat Trading, Nashik',
    'desc'     => 'How Bharat Trading packs, documents and ships export and domestic consignments of onions, rice and spices.',
    'keywords' => 'shipping policy, delivery policy, export shipment, FOB CIF, Bharat Trading',
]);
$page_title = $m['title']; $page_desc = $m['desc']; $page_keys = $m['keywords'];

$legal_key     = 'shipping-return';
$legal_title   = 'Shipping & Delivery Policy';
$legal_lead    = 'How your consignment is packed, documented and shipped, and who is responsible for what.';
$legal_updated = date('F j, Y', (int)filemtime(__FILE__));
$legal_glance  = [
    'Packing, inspection and shipment are agreed with you before the order.',
    'Trade terms such as FOB or CIF are confirmed in the quotation.',
    'Dates are estimates and depend on product, quantity, packing and documentation.',
    'Destination customs duties and taxes are the buyer\'s responsibility unless agreed otherwise.',
];

$legal_sections = [
    ['Who this applies to',
        '<p>This policy covers export and domestic consignments supplied by ' . e(COMPANY['legal']) . ', Nashik, Maharashtra. We are a merchant exporter: products are sourced from suppliers against the agreed specification and we coordinate the packing, inspection and shipment.</p>'],

    ['Order processing and dispatch',
        '<p>The time needed depends on the product, quantity, sourcing, custom packing, inspection and documentation. We confirm the expected dispatch period when the order is confirmed. These dates are estimates, not guarantees.</p>'],

    ['Shipping terms',
        '<p>Consignments are shipped on FOB, CIF or other trade terms agreed for the order, by sea, air or road as suits the consignment. We coordinate with freight forwarders, shipping lines, transporters and customs agents, and keep you informed of the booking and movement of the cargo.</p>'],

    ['Packing and marking',
        '<p>Packing is customised to your requirement, subject to product and supplier capability. Packing type, weight, labels and shipping marks are agreed with you before the goods are packed.</p>'],

    ['Inspection before shipment',
        '<p>Quality inspection is arranged as mutually agreed. Please tell us at the time of order if you want inspection by a particular agency or against a particular standard.</p>'],

    ['Export documents',
        '<p>We are registered with APEDA (RCMC) and hold an Importer-Exporter Code. Documents such as the commercial invoice and packing list are prepared for each shipment, and other documents or certificates that your destination requires are arranged as agreed and as the authorities issue them. Please tell us in advance what your country requires.</p>'],

    ['Duties, taxes and clearance at destination',
        '<p>Unless the order confirmation says otherwise, import duties, taxes, levies, port and clearance charges at the destination are the buyer\'s responsibility.</p>'],

    ['Risk and insurance',
        '<p>Risk passes to the buyer as set out in the agreed trade term. Insurance is arranged according to that term, or as agreed in writing.</p>'],

    ['Delays',
        '<p>Delays can be caused by weather, port or shipping congestion, customs inspection, changes in rules or other events beyond our control. We will inform you promptly and work with you on a practical solution. See our <a href="' . e(url('terms-condition')) . '">Terms &amp; Conditions</a>.</p>'],

    ['Damaged or short consignments',
        '<p>If goods arrive damaged or short, tell us at once and keep the packing, photos and shipping documents. Claims are handled under our <a href="' . e(url('refund-policy')) . '">Refund &amp; Cancellation Policy</a>.</p>'],

    ['Contact us',
        '<p>For shipment questions or to track a consignment, contact:</p>' . legal_contact_html($contact)],
];

require __DIR__ . '/includes/legal-layout.php';