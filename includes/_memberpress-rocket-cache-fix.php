<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * MemberPress + WP Rocket "Cache Logged-in Users" stale-access fix
 * ------------------------------------------------------------------
 * WP Rocket's "Cache Logged-in Users" option (enabled on this site) caches
 * a separate copy of every page per logged-in user, not just per URL. Any
 * page whose content depends on the visitor's MemberPress membership level
 * - e.g. a /topic/* or /type/* listing page rendered via
 * adapt_render_filter_posts() - gets that member's access decision frozen
 * into their personal cache entry at whatever moment it was last built.
 *
 * If MemberPress's view of that member's access changes afterwards (a
 * renewal completes, a subscription is upgraded, a payment that was
 * temporarily failing goes through, etc.), WP Rocket has no way to know -
 * the member keeps being served their own stale cached page, showing
 * whatever access state was true when it was cached, until that cache
 * entry's lifespan expires (currently 6 hours - Settings -> WP Rocket ->
 * Advanced Rules -> Cache Lifespan) or the whole cache is purged manually.
 * Reported symptom: a member seeing a login/no-access prompt on a listing
 * page despite being logged in with a valid membership, which clearing
 * their own browser cache couldn't fix (browser cache was never the
 * issue - this is a server-side cache keyed to their user ID).
 *
 * Fix: whenever MemberPress records a membership-relevant event for a
 * user, immediately purge that one user's WP Rocket cache via
 * rocket_clean_user() so their next page load is rendered live against
 * their current access, instead of waiting out the cache lifespan.
 *
 * Hooked on the single catch-all 'mepr-event-create' action (fires for
 * every event MemberPress saves to wp_mepr_events - transactions,
 * subscriptions, signups, logins, etc: see
 * https://memberpress.com/docs/action-hooks-in-memberpress/) rather than
 * the many individually-named mepr-event-* hooks for specific transaction/
 * subscription transitions, so a membership-affecting event type MemberPress
 * adds later is covered automatically instead of silently falling through
 * an incomplete hook list. The extra cache-purge calls this causes for
 * events that don't actually change access (e.g. a plain login) are cheap
 * and harmless - correctness here matters far more than shaving off a
 * handful of unnecessary purges.
 *
 * To disable this fix, remove the require() for this file in functions.php
 * (or delete this file) - nothing else in the theme depends on it.
 */
function adapt_get_mepr_event_user_id( $event ) {
	if ( ! is_object( $event ) || ! method_exists( $event, 'get_data' ) ) {
		return null;
	}

	$data = $event->get_data();
	if ( ! is_object( $data ) ) {
		return null;
	}

	// MeprTransaction / MeprSubscription both expose a user() method
	// returning a MeprUser (which wraps/extends the WP user and has ->ID).
	if ( method_exists( $data, 'user' ) ) {
		$user = $data->user();
		if ( is_object( $user ) && isset( $user->ID ) && $user->ID ) {
			return (int) $user->ID;
		}
	}

	// Some event payloads (e.g. member-added, signup-completed) are the WP
	// user object itself.
	if ( isset( $data->ID ) && $data->ID ) {
		return (int) $data->ID;
	}

	// Transaction-style objects also carry user_id directly.
	if ( isset( $data->user_id ) && $data->user_id ) {
		return (int) $data->user_id;
	}

	return null;
}

add_action( 'mepr-event-create', function ( $event ) {
	if ( ! function_exists( 'rocket_clean_user' ) ) {
		return;
	}

	$user_id = adapt_get_mepr_event_user_id( $event );
	if ( $user_id ) {
		rocket_clean_user( $user_id );
	}
} );
