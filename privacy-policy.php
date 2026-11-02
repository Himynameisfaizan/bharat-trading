<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/custom-pages.php';

$contact = get_contact();                                   // email, phone, address, hours: from the database

$m = page_meta('privacy-policy.php', [
    'title'    => 'Privacy Policy | Bharat Trading, Nashik',
    'desc'     => 'How Bharat Trading collects, uses and protects the information you share through enquiries and business communication.',
    'keywords' => 'privacy policy, Bharat Trading, data protection, export enquiry privacy',
]);
$page_title = $m['title']; $page_desc = $m['desc']; $page_keys = $m['keywords'];

$legal_key     = 'privacy-policy';
$legal_title   = 'Privacy Policy';
$legal_lead    = 'What we collect when you contact us, why we need it, who sees it and the choices you have.';
$legal_updated = date('F j, Y', (int)filemtime(__FILE__));
$legal_glance  = [
    'We collect only what you send us in an enquiry and what is needed to quote, ship and document an order.',
    'We do not sell your personal information.',
    'No online payments are taken on this website, so we never see your card or bank login details here.',
    'You can ask us to correct or delete your information at any time.',
];

$legal_sections = [
    ['Who we are',
        '<p>This website is operated by <strong>' . e(COMPANY['legal']) . '</strong>, a proprietorship based in Nashik, Maharashtra, India (proprietor: ' . e(COMPANY['owner']) . '). We are a merchant exporter and supplier of Indian agricultural commodities and food products, including fresh onions, rice and spices.</p>'
      . '<p>This policy explains what personal information we collect through this website and through our business communication, how we use it, and the choices you have. By using the website or contacting us you accept this policy.</p>'],

    ['Information we collect',
        '<p>We collect the following kinds of information:</p>' . legal_ul([
            '<strong>Enquiry details:</strong> your name, email address, phone or WhatsApp number, country, the product you are interested in and the message you write in our enquiry form.',
            '<strong>Business and shipment details:</strong> company name, address, consignee, destination port and, where needed for a quotation or export documents, import licence or tax registration numbers. We ask for these only when they are required.',
            '<strong>Communication records:</strong> emails, calls and WhatsApp messages exchanged with us.',
            '<strong>Technical data:</strong> IP address, browser and device type, and the pages visited, recorded by our web server in the normal way.',
        ]) . '<p>We do not run an online checkout and we do not collect card or bank details on this website.</p>'],

    ['Cookies and third-party content',
        '<p>Our website uses a small <strong>session cookie</strong> to keep the enquiry form secure. It is not used for advertising and expires when you close your browser. If you block cookies, the enquiry form may not work, and you can contact us by email, phone or WhatsApp instead.</p>'
      . '<p>Some content is loaded from other companies: fonts from Google Fonts and the location map from Google Maps. When your browser loads these, the provider receives your IP address and is governed by its own privacy policy. If we use analytics tools to understand how the website is used, they may set their own cookies.</p>'],

    ['How we use your information',
        legal_ul([
            'To reply to your enquiry and prepare quotations.',
            'To arrange sourcing, packing, quality inspection and shipment of an agreed order.',
            'To prepare export documents and meet tax, customs and other legal obligations.',
            'To keep business records and handle claims or disputes.',
            'To keep the website secure and prevent spam or misuse.',
            'To contact you about your order or, where you have agreed, about our products.',
        ])],

    ['When we share your information',
        '<p>We do not sell or rent personal information. We share it only when needed to carry out your order or comply with the law:</p>' . legal_ul([
            'Suppliers, packers and quality inspection agencies, limited to the details needed for the order.',
            'Freight forwarders, shipping lines, transporters, customs agents and insurers.',
            'Banks and payment intermediaries, when a payment is made for an agreed order.',
            'Government and regulatory authorities, where the law requires it.',
            'Hosting and email service providers who keep our systems running and are bound to keep the data confidential.',
        ])],

    ['How long we keep it',
        '<p>We keep enquiry and order information for as long as it is needed to deal with your request and for the period required for business, tax and legal record keeping. After that we delete it or make it anonymous.</p>'],

    ['Security',
        '<p>We use reasonable technical and organisational measures to protect the information we hold, including restricted access to our systems and an encrypted (HTTPS) connection on the live website. No method of transmission or storage is completely secure, so we cannot guarantee absolute security.</p>'],

    ['Your rights and choices',
        '<p>You may at any time ask us to:</p>' . legal_ul([
            'tell you what personal information we hold about you,',
            'correct information that is wrong or out of date,',
            'delete your information, except where we must keep it by law,',
            'stop sending you marketing messages.',
        ]) . '<p>Write to us using the contact details below and we will respond within a reasonable time.</p>'],

    ['Links to other websites',
        '<p>This website links to services such as WhatsApp, Google Maps and social media. We do not control those services and are not responsible for their privacy practices.</p>'],

    ['Children',
        '<p>This website is meant for business buyers. We do not knowingly collect information from anyone under 18.</p>'],

    ['Changes to this policy',
        '<p>We may update this policy from time to time. The date at the top of this page shows when it was last changed.</p>'],

    ['Contact us',
        '<p>For any question about this policy or your information, contact:</p>' . legal_contact_html($contact)],
];

require __DIR__ . '/includes/legal-layout.php';
