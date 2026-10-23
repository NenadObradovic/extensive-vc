# Forum replies for version 2.0

Replies for the support threads which didn't get an answer. All of these threads are closed on WordPress.org,
so if you can't reply in a thread, post the announcement from the end of this file as a new topic and link it.

---

## 1. Interactive Banner 2 Query

https://wordpress.org/support/topic/interactive-banner-2-query/

Hi,

Sorry for the late reply.

"Interactive Banner 2" with "Style 6" is not an element from the Extensive VC Addons plugin, it comes from another WPBakery addon plugin (Ultimate Addons for WPBakery Page Builder), so please contact their support for that element.

If you are using our "EVC Interactive Banner" element with "Bordered" type, the hover lines use the current text color, and you can change them with a small custom CSS:

```css
.evc-interactive-banner.evc-ib-bordered .evc-ib-content-wrapper:before,
.evc-interactive-banner.evc-ib-bordered .evc-ib-content-wrapper:after {
	border-color: #your-color;
}
```

Kind regards

---

## 2. EVC image gallery not loading anymore

https://wordpress.org/support/topic/evc-image-gallery-not-loading-anymore/

Hi,

Sorry for the late reply.

We released version 2.0 which is tested with the latest WordPress and WPBakery Page Builder versions. In this version we updated the Lightbox script, improved how 3rd party scripts are loaded and fixed an issue where the lightbox script was not loaded in some cases.

Please update the plugin and clear your cache (caching plugin, CDN and browser cache). If the gallery still doesn't load, please send us the page URL and any errors from the browser console, so we can check it.

Kind regards

---

## 3. Some addons are still missing

https://wordpress.org/support/topic/some-addons-are-still-missing/

Hi,

Sorry for the late reply.

"EVC Process" (same as Process 2, Tabs, Icon List, Pricing Table and Charts) is a container element which holds its own items. WPBakery Page Builder doesn't allow container elements inside an Inner Row or inside another container element, so it is hidden from the elements list there. Please add it into a column of a regular row and it will be available.

Also please check that it isn't disabled in Extensive VC -> Settings -> Disable Shortcodes. In version 2.0 we tested that all elements are available in the WPBakery elements list.

Kind regards

---

## 4. interactive banner 2 problem

https://wordpress.org/support/topic/interactive-banner-2-problem/

Hi,

Sorry for the late reply.

"Interactive Banner 2" is not an element from the Extensive VC Addons plugin, it comes from another WPBakery addon plugin (Ultimate Addons for WPBakery Page Builder). WPBakery shows an element as an empty (blue) box when the plugin which registers that element is not active or the element is disabled, so please check that plugin and contact their support.

Kind regards

---

## 5. Iconic list

https://wordpress.org/support/topic/iconic-list/

Hi,

Sorry for the late reply.

In version 2.0 the Icon List item text supports basic HTML tags (strong, em, a, br, span...), so the HTML code is not displayed as plain text anymore.

If you see the whole shortcode code on the page, please make sure that "EVC Icon List Item" elements are placed inside the "EVC Icon List" element and that Icon List is not disabled in Extensive VC -> Settings -> Disable Shortcodes.

Kind regards

---

## 6. Javascript Loading Probelms

https://wordpress.org/support/topic/javascript-loading-probelms/

Hi,

Sorry for the late reply and thank you for the detailed report.

This is fixed in version 2.0. The Progress Bar now has the correct percent value in the HTML (so it is correct even before the animation), the animation starts as soon as the element is visible (we replaced the old jQuery Appear script with a modern IntersectionObserver solution) and the counter always ends with the exact percent value.

Please update the plugin and clear your cache.

Kind regards

---

## 7. Counter – remove icon or image

https://wordpress.org/support/topic/counter-remove-icon-or-image/

Hi,

Sorry for the late reply.

The "EVC Counter" element from our plugin doesn't have icon or image options, it has only digit, title and text, the same as on our demo page. The "Stats Counter" with icon or image which you see in the admin comes from your theme or another addon plugin.

Please add the "EVC Counter" element from the "Extensive VC" tab in the WPBakery elements list and you will get numbers only.

Kind regards

---

## 8. Can We Set image size?

https://wordpress.org/support/topic/can-we-set-image-size/

Hi,

Sorry for the late reply.

Yes, elements with images have the "Image Size" option (Single Image, Image Gallery, Gallery Block, Image With Text, Interactive Banner, Flip Image, Testimonials...). You can enter thumbnail, medium, large or full, or a custom size in pixels, for example 600x400. Blog List, Post Carousel and Product List have the "Image Proportions" option.

In version 2.0 this option also accepts image sizes registered by your theme or other plugins, and we added it to the Icon With Text (custom icon) and Process 2 elements.

Kind regards

---

## 9. Some addons are missing

https://wordpress.org/support/topic/some-addons-are-missing/

Hi,

Sorry for the late reply.

"EVC Tabs" is a container element which holds "EVC Tabs Item" elements. WPBakery Page Builder doesn't allow container elements inside an Inner Row or inside another container element (for example inside WPBakery Tabs), so the element is hidden from the elements list there. Please add it into a column of a regular row.

Also please check that the element isn't disabled in Extensive VC -> Settings -> Disable Shortcodes. If it is still missing with your theme, please update to version 2.0 and let us know, so we can check it.

Kind regards

---

## 10. EVC Text Marquee overlapping under Opera

https://wordpress.org/support/topic/evc-text-marquee-overlapping-under-opera/

Hi,

Sorry for the late reply and thank you for the report.

This is fixed in version 2.0. The text width was calculated before the web font was loaded, so the second copy of the text started too early. Now the width is calculated again after fonts are loaded and on window resize. We also improved the animation performance, it is paused when the element is not visible.

Please update the plugin and clear your cache.

Kind regards

---

## Announcement (new topic)

**Title:** Extensive VC Addons 2.0 – security and compatibility update

Hi everyone,

Extensive VC Addons 2.0 is released. This is an important security update, so please update the plugin as soon as possible.

What's new:

- Security fixes, including the fix for CVE-2025-14475 (Local File Inclusion in the pagination request)
- Compatibility with PHP 8.4, the latest WordPress, WPBakery Page Builder, WooCommerce (HPOS) and Contact Form 7
- Polylang and WPML support for Blog List load more pagination
- Updated Chart.js (v4) and Lightbox scripts, removed the old jQuery Appear script
- Ionicons font replaced with lightweight SVG icons, so the plugin loads less CSS and fonts
- New action hooks in all element templates for easier customization
- Fixes for Progress Bar, Text Marquee, Contact Form 7 widget, Product List and image size options

Please note: version 2.0 requires PHP 8.2+ and WordPress 6.0+. The Ionicons font is removed, so if you used ion-* icon classes in your own content, please load the icon font from your theme.

The full changelog is available on the plugin page. If you notice any issue after the update, please open a new topic and we will help you.

Thank you for using Extensive VC Addons!
