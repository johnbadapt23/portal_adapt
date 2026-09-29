# Undefined variable audit - portal_staging

Date: 29 September 2026
Scope: all 246 PHP files in `F:\Will optimize\portal_staging` (excluding `node_modules`, `.git`)

## Method

1. `php -l` syntax check on every file (PHP 8.4). Result: no syntax errors.
2. PHPStan 2.2 at level 1 with WordPress and ACF Pro stubs. It reported 917 "might not be defined" warnings.
3. Most of those warnings are noise, because PHPStan cannot see how WordPress loads templates. A second script traced every `include locate_template()` and `get_template_part()` call site to work out which variables really reach each file. The script knows that:
   - Page templates run in global scope, so WordPress globals, query vars (such as `$taxonomy`) and the theme globals set on the `wp` hook (`$membershipType`, `$advantageType`, `$member`, `$current_user`, `$first_name`, and so on) are available there.
   - Files loaded with `get_template_part()` only get the core WP globals (`$post`, `$wp_query`, ...) and `$args`.
   - Files loaded with `include` inherit the caller's scope. When the caller is a function, that means only the function's own variables.
4. Every remaining finding was read and confirmed by hand.

Line numbers refer to the current working copy, which includes the uncommitted edits to `single-post.php` and `_article-title.php`.

---

## 1. Functional bugs (wrong output, not just a notice)

| # | File : line | Variable | What goes wrong | Suggested fix |
|---|---|---|---|---|
| 1.1 | `templates/template-technology-trends-markets.php:371` | `$topic` | The dropdown's `selected` check compares each term against `$topic`, which is the WP_Term left over from the loop at line 355. It should compare against `$topicFilter` (parsed from `$_GET['topic']` at line 8). A string compared with an object never matches, so the selected topic is never pre-selected when the page is opened from a bookmark or shared URL. This is the same GET-mismatch pattern described in PROJECT-HANDOFF.md. | Replace `$topic` with `$topicFilter` on line 371. |
| 1.2 | `templates/template-insights.php:516-531`, `templates/template-insights-curation-one.php:518+` | `$sortPost` | The sort checkboxes check `$sortPost`, but the file only defines `$sortPosts` (line 17). The Newest, Oldest and A-Z checkboxes are never shown as checked. | Rename to `$sortPosts`. |
| 1.3 | `templates/template-insights.php:274, 326, 414`, `template-insights-curation-one.php:275, 284, 316, 417` | `$filterCat`, `$filterEvent`, `$filterDuration` | These are never assigned anywhere in either file. As a result, the filter form only gets its `active` class from `$filterType`. They look like leftovers from an older filter set. The current file parses `$filterTopics`. | Confirm which filters are still live. Then replace the conditions with `$filterTopics` / `$filterType`, or remove the dead checks. Please verify the intended behaviour before changing. |
| 1.4 | `templates/components/_single-research-text-header-block.php:28-29` | `$advantageType`, `$advantagePlus` | This file is loaded with `get_template_part()` and does not declare `global $advantageType`, so both variables are always undefined. The condition `$advantageType == 'yes'` is therefore always false, and the Advantage download block for sector outlooks and persona profiles never renders. | Add `global $advantageType;` and work out `$advantagePlus` the same way `_video-preview.php` does (lines 1-15). |
| 1.5 | `templates/template-announcement.php:27-37` | `$members` | `$members` is only reset inside `if ( have_rows( 'membership_ids' ) )`. An announcement with no membership rows reuses the previous announcement's membership list for its `current_user_can()` gate. If it is the first item, the variable is undefined. The result is inconsistent audience gating. | Move `$members = '';` above the `have_rows()` check, inside the loop. |
| 1.6 | `templates/template-data-listing.php:27-36, 85-94` | `$buttonLink`, `$target` | These are only set inside the inner `have_rows( 'button' )` loop. A link module without a button shows the previous module's link. | Reset both to `''` at the start of each `link_modules` iteration. |
| 1.7 | `templates/post-components/_highlights-featured-block.php:541-543`, `_resources-featured-block.php:516-518` | `$args` | (a) When a type term is set, the sidebar `WP_Query` runs twice: once inside the `if` and again straight after it. That is a wasted query on every page view. (b) When no term is set, `$args` still holds the value from an earlier section of the same file, so the sidebar shows posts from the wrong query. | Delete the duplicate `new WP_Query( $args )` after the closing brace. Initialise `$sidebar_posts = null;` and only loop when it is set. |
| 1.8 | Loop-carried term variables, about 20 templates (see appendix A) | `$postType`, `$postTopic`, `$postSector`, `$personaTerm`, `$sectorTerm` | This pattern repeats throughout the templates: the term is only assigned inside `if (yoast primary) ... else if (get_the_terms) foreach`. A post with no term shows the previous card's topic or type link, or triggers an undefined-variable warning if it is the first card. | Set the variable to `null` at the start of each loop iteration. The existing `if ($postType)` guards then work as intended. |
| 1.9 | `templates/components/_featured-article-card.php`, `_featured-article-card-sector.php` (lines 38-73) | `$post_id`, `$post`, `$postTopic` | `$post_id` is never set by any caller. Inside `ajax_load_featured_post()` (`functions.php:2313/2315`), `$post` is not in scope either, so `$post->ID` raises "Attempt to read property on null" in every AJAX response. The links still work only because `get_permalink(null)` falls back to the global post. `$postTopic` is undefined, so `$postTopic->name` warns, when a post has no persona or sector term. With `display_errors` on, these warnings are written into the JSON response and break it. | At the top of both cards, add `$post = get_post(); $post_id = get_the_ID(); $postTopic = null;` and guard the label with `if ( $postTopic )`. |
| 1.10 | `templates/components/_keep-watching-slider-portal.php:35` | `$postType` | If the post has no filter-type term, `$postType->slug` reads from null and the related query runs with `terms => null`. | Initialise `$postType = null;` and skip the slider when it is empty. |

## 2. Accessibility and HTML validity issues caused by undefined variables

| # | File : line | Variable | Issue | Fix |
|---|---|---|---|---|
| 2.1 | `templates/partials/_header.php:260` | `$membersNameMenu` | For a free-trial user who matches no `free_trial_memberships` row, the home link renders with no text. Lighthouse flags this as "Links do not have a discernible name". | Default it to a sensible label (for example `'Home'`) before the loop. |
| 2.2 | `templates/template-registration.php:75-88, 164-177` | `$post_id`, `$post_slug` | Neither is ever set. Every card renders `id=""`, which is invalid HTML and repeated on the page, and two warnings are raised per card. | Set `$post_id = get_the_ID(); $post_slug = get_post_field( 'post_name', $post_id );` inside the loop, as `template-events-portal.php:88-89` already does. |
| 2.3 | `template-persona-data-insights.php:22`, `template-persona-market-narratives.php:26`, `template-sectors-data-insights.php:22`, `template-sectors-market-narrative.php:26`, `template-technology-trends.php:22`, `template-technology-trends-markets.php:26` | `$banner_image` | With no banner rows, `$banner_image['url']` reads from undefined and the output is `background-image:url()`, an empty request. | Initialise it to `null` and only print the `style` attribute when an image exists. |

## 3. Notices only (output is correct, but a PHP warning is logged)

These should still be fixed. PHP 8 logs every one as a Warning, which adds noise to `debug.log` and some per-request overhead.

| File : line | Variable | Note |
|---|---|---|
| `templates/components/_contact-block.php:20` | `$first_name` | Undefined for logged-out visitors (used by `template-get-advice.php`, `template-research-flexible.php`). Initialise it to `''`. |
| `templates/single-kyc.php:24` | `$postTopic` | Undefined when the post has no top-level kit-type term. `is_wp_error()` catches the result, but the notice is still raised. |
| `templates/single-registration.php:57-61` | `$preText`, `$buttonText` | Undefined when the ACF fields are empty. The fallback text works. Initialise both to `''`. |
| `templates/post-components/_video-preview.php:105` | `$post_id` | Not passed by `single-post.php:553`. Works through the global post fallback. Use `get_the_ID()`. |
| `templates/components/_single-research-text-header-block.php:95-101` | `$postTopic`, `$postType` | The `null` reset at line 68 only runs on one branch, and line 101 reads `$postType->slug` unguarded. |
| `templates/components/_sector-grid-portal.php:74`, `_persona-grid-portal.php:68`, `_topic-grid-portal-data.php:68`, plus `$imageCounter` in 9 page templates | `$imageCounter` (and `$image`) | Only set when `slider_images` rows exist, and carried over between cards. Reset both per card. |
| `template-insights.php:402`, `template-insights-curation-one.php:405`, `template-insights-new.php:184` | `$pageURL` | `.=` on an undefined variable. The value is never used afterwards, so the line can be deleted. |
| `template-insights-new.php:165` | `$sortValue` | Undefined when `?orderby=` is anything other than `date` or `title`. Default it to `'Sort By:'`. |
| `template-insights.php:125-190, 864-873`, `template-insights-curation-one.php` (same blocks) | `$order`, `$orderBy` | Only undefined for hand-crafted `?sortPost=` values. Default them at the top of the file. |
| `$counter++` in 11 page templates (see appendix B) | `$counter` | Never initialised and never read. Delete the line. |

## 4. Other findings from the same run

- `memberpress/memberpress-corporate/controllers/class-mpca-account-controller.php:184` (and its duplicate one folder up): `if(empty($errors))` should be `$results['errors']`. As written, CSV import validation errors are ignored. The theme does not load these controller copies (MemberPress Corporate uses its own plugin copy), so there is no current impact. The copies can probably be deleted.
- Orphaned components with no load site anywhere in the theme: `templates/components/_related-articles-taxonomies-locked.php` and `_related-articles-portal-market-narratives.php`. They are candidates for removal.
- `templates/components/_customer-video.php:73`, `_customer-video-landing.php:86`: whitespace after the closing `?>`. Remove the closing tag.
- `includes/_hooks.php:28-34`: `remove_action()` is called with 4 arguments. It only accepts 3, so the extra argument is ignored. Harmless, but worth tidying.
- `template-filter-types.php:316, 379, 404, 426`, `template-topic.php:234, 287`, `template-whats-new.php:143`: `empty( $allowed_*_slugs )` is always true at that point. PHPStan flags these as dead branches. Worth a look, but no undefined variable is involved.

## 5. Checked and not a problem (false positives)

- `index.php:22` and `template-fundamentals-lever.php` `$taxonomy`: WordPress sets this global from the query vars on taxonomy archives.
- `$membershipType`, `$advantageType`, `$member`, `$first_name` in page templates: set as globals on the `wp` hook in `functions.php:115+`.
- `$allowed_persona_slugs`, `$allowed_sector_slugs`, `$allowed_events_slugs`, `$persona_from_get`, `$sector_from_get` in the filter templates: only read inside `!empty( $*_terms )` blocks that are assigned in the same branch.
- `single-post.php:589` `$previewContent`: always assigned on the path that reaches it.
- `template-insights.php:741` `$topic`: only read when `$filterTopics` is non-empty, so the loop has already assigned it.
- `templates/components/_event-card.php` `$extra_classes`: set by its only caller (`template-events-portal.php:90`).
- About 92 warnings in the `memberpress/` template overrides (`$mepr_options`, `$product`, `$mepr_current_user`, `$ca`, and so on): MemberPress injects these through `MeprView::render()` / `extract()`. They cannot be verified without the plugin source, but they match MemberPress's standard view variables.

---

## Appendix A - loop-carried term variables (item 1.8)

- `single-post.php` - `$postTopic` 118, 1615, 1636, 1675; `$postType` 1681; `$postFilterType` 219
- `template-conversation.php` - `$postType`, `$postTopic`, `$postSector` (4 loops)
- `template-conversation-persona.php` - `$postType` 115, `$postTopic` 118
- `template-conversation-sector.php` - `$postSector` 117, `$postTopic` 120
- `template-evr-maturity-stage.php` - `$postTopic`, `$postType`
- `template-fundamentals-lever.php` - `$postTopic`, `$postType`
- `template-persona.php`, `template-persona-mapping.php`, `template-persona-data-insights.php`, `template-persona-market-narratives.php` (also `$personaTerm` 273)
- `template-sector.php`, `template-sector-markets.php`, `template-sector-analysis.php`, `template-sectors-data-insights.php`, `template-sectors-market-narrative.php` (also `$sectorTerm` 246)
- `template-technology-trends.php`, `template-technology-trends-markets.php`
- `template-portal-flexible.php` - `$postTopic` 121, 240
- `template-tnc.php` - `$postType`, `$postTopic`, `$postSector`
- `components/_featured-article-card*.php` - `$postTopic`

## Appendix B - `$counter++` with no initialiser

`template-persona.php:283`, `template-persona-mapping.php:527`, `template-persona-data-insights.php:142, 444`, `template-persona-market-narratives.php:288, 696`, `template-sector.php:307`, `template-sector-markets.php:307`, `template-sector-analysis.php:129`, `template-sectors-data-insights.php:142, 444`, `template-sectors-market-narrative.php:261, 643`, `template-technology-trends.php:128, 424`, `template-technology-trends-markets.php:260, 657`

Also in `template-persona-market-narratives.php:132, 422`, `template-sectors-market-narrative.php:107, 369` and `template-technology-trends-markets.php:110, 378`: `$filterType` and `$filterSubTopic` are never assigned. These templates only parse `$topicFilter` / `$keyword`, so the "clear filters" condition only ever reacts to `$keyword`. This belongs to the same GET-mismatch pattern as item 1.1.
