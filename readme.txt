=== Sheet Music Libary ===
Contributors: celloexpressions
Tags: music, sheet music, library, music library
Requires at least: 6.1
Tested up to: 6.6
Stable tag: 2.0.1
Description: Add a sheet music library, including PDFs, audio, descriptions and more to your site.
License: GPLv2

== Description ==
The sheet music library plugin is a framework that leverages WordPress to post sheet music online in a structured way. Using a sheet music custom post type and taxonomies for composers, genres, difficulties, and orchestrations, you can upload, organize, and share sheet music in a native-feeling interface. In addition to the taxonomies, each "piece" object includes PDF-based score and parts upload (with automatically-generated preview images), and audio upload and/or Youtube/Vimeo embeds to showcase recordings. The native WordPress block editor facilitates additional information, be it a sentence explaining the arrangement or a multi-paragraph essay describing a work complete with multimedia content.

On the front-end, this plugin provides default styling and filtering to display sheet music content in a way that is compatible with most themes. Customizations ranging from visual tweaks with CSS to custom themes that implement the `sheet_music` post type directly with custom templates allow for infinite possibilities. Blocks are also available so that you can optionally build custom layouts with the site editor and block themes.

Whether you're a composer/arranger publishing your works online, a community or school orchestra sharing music with your members, or work with music in any other way, the Sheet Music Library plugin provides an easy, flexible way to manage and share your content.

Note that this plugin was designed with classical music in mind in particular, but it can be used in much broader contexts. It was built for the <a href="https://celloexpressions.com/music/">Cello Expressions Sheet Music Library</a>, which provides a good example of what it can do and how it can look on the front end. The backend UI is just as much of a reason to try this plugin, with its seamless integration with WordPress core features.

== Installation ==
1. Take the easy route and install through the WordPress plugin installer OR
1. Download the .zip file and upload the unzipped folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. The "Sheet Music" post type is now available and can be used to add content. Plan on taking some time to either look at some different themes to find one that works well with the default styling, add some custom CSS to clean up the styling, or build a fully custom theme with custom templates for the sheet music post type. The plugin also includes all of the blocks necessary to create sheet music post templates in the site editor, if you prefer to start from scratch.
1. Install the Front Page Custom Post Type plugin to show sheet music posts on your front page: https://wordpress.org/plugins/front-page-custom-post-type/. Or, add a link to the "Sheet Music" post type archive to your menu via the customizer.
1. The [all_sheet_music] and [latest_sheet_music] shortcodes can be used to add a table-style summary of all music on the site, and you can use the standard WordPress archive/taxonomy views to present music as well.
1. Add the "Sheet Music Library Playlist" widget to your sidebar to facilitate audio-based music discovery on your site. 


== Frequently Asked Questions ==
= Block Editor Support =
Sheet Music Library 2.0 adds support for the block editor. Support for blocks within sheet music posts' content is stable. Most other block related features -- sheet music library template blocks -- are intended for use in block-based theme templates or in custom views with query blocks. The built-in block patterns provide a good start for typical use cases, such as a query loop to feature pieces in a particular taxonomy.

= Site Editor Support =
Sheet Music Library 2.0+ includes blocks and patterns that enable you to create a fully customized theme that supports the Sheet Music Library plugin within the block-based site editor. This is a beta interface, and includes the rough edges that come with the current state of block themes. If you build sheet music single and archive templates in the block editor, you need to declare theme support for `sheet_music_library` to disable the `the_content` filter-based templating that the plugin provides for compatibility with classic themes. There is a UI option in the customizer (which allows you to live-preview the impact of changing this option), so that this is possible without digging into the code. Distributed themes that integrate with this plugin are encourage to include this flag by default.

= Problems with PDF Preview Images =
This plugin has a handy feature that generates images from PDF files that are uploaded. However, it requires a server-side image processing feature called Imagick (or ImageMagick). If image previews of PDFs aren't working, this feature is likely missing. Contact your host to see whether it can be enabled. In some cases, your best option may even be to switch to a host that supports this feature, if image previews of PDFs are a must-have feature.

Note that WordPress core added a similar feature for all PDF uploads, inspired by this plugin, in version 4.7. The core feature will show PDF thumbnails in the media library for files uploaded after 4.7 was installed. This plugin maintains a separate version of the PDF image, but may utilize the core feature in the future.

= E-Commerce Integration =
Out of the box, the Sheet Music Library doesn't include E-Commerce functionalities, as it was built for a free sheet music library. However, this functionality could be added on to the plugin with either an add-on plugin or by restricting access to the actual PDF file downloads or by using custom front-end handling with a custom theme or child theme.

= Shortcodes =
To display all sheet music in a table view, write the following on its own line in a post/page content area: `[all_sheet_music]`. If you have a lot of sheet music, you could also use `[latest_sheet_music number="10"]` and specify the number of pieces to display. If you want to get a playlist of the audio files associated with sheet music, use `[sheet_music_audio_playlist]`. This shortcode also supports a genre argument - give it the slug (URL extension) for the genre you want to display: `[sheet_music_audio_playlist genre="classical"]`. Generally speaking, it is better to use post type archives and taxonomy views where possible (see the installation instructions for details). In version 2.0 and newer, you can also use Query Loop blocks (with sheet music library post template patterns) to achieve a similar result (minus the table view).

== Screenshots ==
1. Table view display with the Twenty Fifteen theme (as of version 1.1, this includes audio players as well).
2. Archive (search, taxonomy, author, date, etc.) view with the Twenty Fifteen theme.
3. Single piece view with the Twenty Fifteen theme.
4. Taxonomy administration for sheet music posts (classic editor).
5. Custom field administration for sheet music posts (block editor).
6. Automatic, contextual sheet music audio playlist widget on a taxonomy page (showing an "orchestration" term).

== Changelog ==
= 2.1 - [future] =
* Improve sheet music date modified logic (intended to represent changes to sheet music PDFs, not the piece display) to work when there is only one PDF attachment and to not report a date before the piece publish date.
* 

= 2.0 - 4/1/2023 =
* Enable the Block Editor for the Sheet Music post type. Sheet music data remains managed within a meta box below the post content, matching the 1.2 interface.
* Update minimum WP version to 6.1 for custom taxonomy compatibility with the core query and terms blocks.
* Add editor blocks for each component of the default sheet music template filtering display.
* Refactor post template filtering to use the same rendering functions as the new sheet music library blocks. Default front-end output display should not change.
* Add block patterns for use in sheet music query loop blocks.
* Add block patterns for use in theme templates, for sheet music post type single and archive views.
* Add collections taxonomy, not displayed by default, for use in query blocks and optional display in custom templates.
* Add a playlist block pattern to improve the workflow of adding playlists to posts in the block editor, until core provides feature parity with the classic editor playlist functionality.
* Add UI options in the customizer to disable content filtering and to disable default styling, for use when sheet music templates are set up via block UI and the site editor.
* Add file date modified display to all file download buttons.
* Minor adjustments to the direct download unavailable message box.
* Add styling to support small audio players, which can be enabled through the `.condensed-audio` CSS class and is included in some block patterns.

= 1.2 =
* Add the "Sheet Music Audio Playlist" widget, which automatically compiles the sheet music audio present on a given page into a playlist. This is particularly helpful on taxonomy and search views.
* Add the "Sheet Music Recent Audio Playlist" widget, which displays a playlist of the most recent audio posted. The sheet music associated with these posts is not directly available, so this widget is best used alongside a recent posts widget.
* Add a way to display a message in place of the PDF download buttons on a piece-by-piece basis, where some music is not freely and publicly available.
* Introduce the ability to remove attachments from a piece in the post meta box.
* Minor keyboard accessibility improvements in the piece files meta box.

= 1.1.1 =
* Fix enqueuing admin assets for custom post meta UI.
* Add missing localized strings for JS.

= 1.1 =
* Add audio players to sheet music in the table views from the [all_sheet_music] and [latest_sheet_music] shortcodes.
* Add support for translations via plugin language packs.

= 1.0 =
* Initial public release.

== Upgrade Notice ==
= 2.0 =
* Major update introducing support for the block editor and optional blocks and patterns for use with block themes.

= 1.1 =
* Adds audio players to table views and support for plugin language packs.

= 1.0 =
* Initial public release.