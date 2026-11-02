<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/custom-pages.php';

$contact = get_contact();

$m = page_meta('refund-policy.php', [
    'title'    => 'Refund & Cancellation Policy | Bharat Trading, Nashik',
    'desc'     => 'How cancellations, quality or quantity claims and refunds are handled for orders placed with Bharat Trading.',
    'keywords' => 'refund policy, cancellation policy, export order claims, Bharat Trading',
]);
$page_title = $m['title']; $page_desc = $m['desc']; $page_keys = $m['keywords'];

$legal_key     = 'refund-policy';
$legal_title   = 'Refund & Cancellation Policy';
$legal_lead    = 'What happens if an order changes, is cancelled, or does not match the agreed specification.';
$legal_updated = date('F j, Y', (int)filemtime(__FILE__));
$legal_glance  = [
    'Our products are food commodities sourced and packed to your specification, so standard returns are not possible.',
    'Once an order is confirmed, changes or cancellation need written agreement from both sides.',
    'If goods do not match the agreed specification, send us evidence and we will settle it by agreement.',
    'An order cannot be cancelled once it has been shipped.',
];

$legal_sections = [
    ['How this policy works',
        '<p>' . e(COMPANY['legal']) . ' is a merchant exporter. Each order is for food commodities sourced, packed and shipped to the specification agreed with the buyer. For that reason, the terms of the order confirmation or proforma invoice come first. This policy applies to everything those documents do not cover.</p>'],

    ['Before an order is confirmed',
        '<p>An enquiry or a quotation creates no obligation. You can withdraw an enquiry at any time without any charge.</p>'],

    ['Cancelling or changing a confirmed order',
        '<p>After an order is confirmed in writing, cancellation or any change to quantity, specification, packing or destination needs written agreement from both parties. If sourcing, processing, custom packing, inspection, documentation or shipment booking has already started, the buyer bears the costs already incurred. Any advance paid is adjusted against those costs, and the balance, if any, is refunded as agreed.</p>'],

    ['After shipment',
        '<p>Once goods have been handed over for shipment, the order cannot be cancelled, because the consignment is already in transit.</p>'],

    ['Claims for goods that do not match the order',
        '<p>You may raise a claim if the goods received:</p>' . legal_ul([
            'do not match the specification agreed in the order confirmation,',
            'are short in quantity compared with the invoice or packing list, or',
            'arrive with packing damage that can be traced to our packing or handling.',
        ]) . '<p>Please write to us as soon as you receive the goods, within the claim period stated in the order terms and before the goods are used, processed or resold. Include the order or invoice number and supporting evidence: clear photos or video, the packing list, any inspection or laboratory report, and the shipping documents.</p>'],

    ['How a claim is settled',
        '<p>We review the claim with you and, where needed, with our supplier and the inspection agency. If the claim is accepted, the remedy is agreed with you. It may be a replacement, a credit note, a price adjustment or a refund for the affected portion.</p>'],

    ['What is not covered',
        legal_ul([
            'Natural variation in colour, size, aroma or texture within the agreed tolerance.',
            'Damage or deterioration after the risk has passed to the buyer, caused by storage, handling or delay in clearance.',
            'Delay, duty, rejection or other action by customs or authorities at the destination.',
            'A change of mind, or goods that have already been used, processed or resold.',
        ])],

    ['Returns',
        '<p>Because these are consumable food products, we do not accept returns as a rule. Please do not send goods back without our written approval.</p>'],

    ['Refunds',
        '<p>An approved refund is paid back to the account the payment came from. We will tell you the expected timing when the refund is approved.</p>'],

    ['Contact us',
        '<p>To cancel an order or raise a claim, contact:</p>' . legal_contact_html($contact)],
];

require __DIR__ . '/includes/legal-layout.php';
