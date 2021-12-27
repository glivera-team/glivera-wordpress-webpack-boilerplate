const path = require('path');

module.exports = {
	paths: {
		/* Path to source files directory */
		source: path.resolve(__dirname, '../src/'),

		/* Path to built files directory */
		output: path.resolve(__dirname, '../dist/'),

		/* Path to built files to wp directory */
		wpOutput: path.resolve(__dirname, '../wp_files/wp-content/themes/mytheme/'),
	},
	server: {
		host: '0.0.0.0',
		open: 'http://localhost:8080',
		port: 8080,
	},
	limits: {
		/* Image files size in bytes. Below this value the image file will be served as DataURL (inline base64). */
		images: 8192,

		/* Font files size in bytes. Below this value the font file will be served as DataURL (inline base64). */
		fonts: 8192,
	},
};
