<?php
/**
 * wp_helper.php — no-WP-CLI helper for the figma-to-wpbakery-page skill.
 *
 * This project's local install has no WP-CLI, so this script boots WordPress
 * directly (via wp-load.php) and exposes just the handful of operations the
 * skill needs: list pages, read/write a page's post_content, sideload an
 * image into the media library, and inspect a WPBakery shortcode's real
 * registered params (so generated shortcodes use attributes that actually
 * exist in the installed js_composer + keydesign-addon version, not
 * remembered/guessed ones).
 *
 * Usage (run from anywhere — it locates wp-load.php relative to this file):
 *   php wp_helper.php list-pages
 *   php wp_helper.php get-content <page_id>
 *   php wp_helper.php set-content <page_id> <path-to-shortcode-file>
 *   php wp_helper.php import-image <path-to-image> [attachment-title]
 *   php wp_helper.php inspect-shortcode <base-name>   (e.g. tek_iconbox, vc_row)
 *
 * Every command prints a single JSON object to stdout. Errors go to stderr
 * with a non-zero exit code.
 */

error_reporting(E_ERROR | E_PARSE); // WP core/plugin notices are noisy on CLI and irrelevant here.

// This file lives at <project-root>/.claude/skills/figma-to-wpbakery-page/scripts/wp_helper.php
$project_root = dirname(__DIR__, 4);
$wp_load = $project_root . '/wp-load.php';
if ( ! file_exists( $wp_load ) ) {
	fwrite(STDERR, "Could not find wp-load.php at $wp_load — has this skill folder moved? Adjust \$project_root in wp_helper.php.\n");
	exit(1);
}

define('WP_USE_THEMES', false); // CLI boot: skip theme/template loading, we only need core + active plugins.
require $wp_load;

function out($data) {
	echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
}
function fail($msg) {
	fwrite(STDERR, $msg . "\n");
	exit(1);
}

$cmd = $argv[1] ?? '';

switch ($cmd) {

	case 'list-pages':
		$pages = get_pages(['sort_column' => 'post_title']);
		$rows = array_map(function($p) {
			return [
				'id'       => $p->ID,
				'title'    => $p->post_title,
				'slug'     => $p->post_name,
				'status'   => $p->post_status,
				'template' => get_page_template_slug($p->ID) ?: '(default)',
				'url'      => get_permalink($p->ID),
			];
		}, $pages);
		out($rows);
		break;

	case 'get-content':
		$id = (int) ($argv[2] ?? 0);
		if (!$id) fail('Usage: get-content <page_id>');
		$post = get_post($id);
		if (!$post) fail("No post with ID $id");
		out(['id' => $id, 'title' => $post->post_title, 'content' => $post->post_content]);
		break;

	case 'set-content':
		$id = (int) ($argv[2] ?? 0);
		$file = $argv[3] ?? '';
		if (!$id || !$file || !file_exists($file)) fail('Usage: set-content <page_id> <path-to-shortcode-file>');
		$post = get_post($id);
		if (!$post) fail("No post with ID $id");

		// Always back up the previous content before overwriting — WP's own
		// revision system covers this too, but a plain file backup is easier
		// for the skill (and the user) to diff/restore from without touching wp-admin.
		$backup_dir = __DIR__ . '/../backups';
		if (!is_dir($backup_dir)) mkdir($backup_dir, 0777, true);
		$backup_file = $backup_dir . "/page-{$id}-" . date('Ymd-His') . '.txt';
		file_put_contents($backup_file, $post->post_content);

		$new_content = file_get_contents($file);
		$result = wp_update_post(['ID' => $id, 'post_content' => $new_content], true);
		if (is_wp_error($result)) fail('wp_update_post failed: ' . $result->get_error_message());

		out(['id' => $id, 'updated' => true, 'backup' => $backup_file, 'url' => get_permalink($id)]);
		break;

	case 'import-image':
		$path = $argv[2] ?? '';
		$title = $argv[3] ?? pathinfo($path, PATHINFO_FILENAME);
		if (!$path || !file_exists($path)) fail('Usage: import-image <path-to-image> [title]');

		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$filename = wp_unique_filename(wp_upload_dir()['path'], basename($path));
		$bits = wp_upload_bits($filename, null, file_get_contents($path));
		if ($bits['error']) fail('wp_upload_bits failed: ' . $bits['error']);

		$filetype = wp_check_filetype($bits['file']);
		$attachment_id = wp_insert_attachment([
			'post_mime_type' => $filetype['type'],
			'post_title'     => sanitize_text_field($title),
			'post_status'    => 'inherit',
		], $bits['file']);
		if (is_wp_error($attachment_id)) fail('wp_insert_attachment failed: ' . $attachment_id->get_error_message());

		$metadata = wp_generate_attachment_metadata($attachment_id, $bits['file']);
		wp_update_attachment_metadata($attachment_id, $metadata);

		out(['attachment_id' => $attachment_id, 'url' => wp_get_attachment_url($attachment_id)]);
		break;

	case 'inspect-shortcode':
		$base = $argv[2] ?? '';
		if (!$base) fail('Usage: inspect-shortcode <base-name>');
		if (!class_exists('WPBMap')) fail('WPBakery (js_composer) is not active — WPBMap class not found.');
		$def = WPBMap::getShortCode($base);
		if (!$def) fail("No shortcode registered with base '$base'. Check spelling, or run list-tek-shortcodes.");
		out($def);
		break;

	case 'list-tek-shortcodes':
		// Known keydesign-addon (Ekko theme) shortcode bases, captured from the
		// plugin source at skill-creation time. Confirm exact params for any of
		// these with `inspect-shortcode <base>` before using — this list is just
		// for discovery, not a source of truth for params.
		out([
			'tek_alertbox','tek_appgallery','tek_bookpreview','tek_button','tek_calltoaction',
			'tek_clients','tek_color_swtich','tek_contactform','tek_contentbox','tek_countdown',
			'tek_counter','tek_divider','tek_eventsession','tek_extended_tabs','tek_featuresections',
			'tek_iconbox','tek_image_comparison','tek_list','tek_loginform','tek_map',
			'tek_masonrygallery_elem','tek_photobox','tek_photocarousel','tek_photogallery',
			'tek_piechart','tek_priceblock','tek_priceswitcher','tek_pricing','tek_process',
			'tek_progress_bar','tek_reviewcarousel','tek_reviews','tek_sectiontitle','tek_shape',
			'tek_sliding_box','tek_socialbuttons','tek_socialicons','tek_team','tek_teamcarousel',
			'tek_testimonial_card','tek_testimonialcards','tek_testimonials','tek_textrotator','tek_timeline',
		]);
		break;

	default:
		fail("Unknown command '$cmd'. See the header comment in wp_helper.php for usage.");
}
