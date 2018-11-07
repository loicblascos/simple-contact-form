/**
 * Webpack Config for Simple Contact Form
 *
 * @author Loïc Blascos
 * @since 1.0.0
 */

const glob    = require( 'glob' );
const Extract = require( 'mini-css-extract-plugin' );
const Minify  = require( 'optimize-css-assets-webpack-plugin' );
const Uglify  = require( 'uglifyjs-webpack-plugin' );

module.exports = {
	mode: process.env.NODE_ENV === 'production' ? 'production' : 'development',
	context: __dirname +  '/block/',
	entry: {
		editor: glob.sync( __dirname +  '/block/index.js' ),
		front : glob.sync( __dirname +  '/block/front.js' )
	},
	plugins: [
		new Extract( {
			filename: './[name].build.css',
		} ),
	],
	optimization: {
		minimizer: [
			new Minify(),
			new Uglify()
		]
	},
	output: {
		path: __dirname + '/assets',
		filename: '[name].build.js'
	},
	stats: {
		children: false
	},
	module: {
		rules: [
			{
				test: /\.(js|jsx)$/,
				use: {
					loader: 'babel-loader'
				},
				exclude: /(node_modules|bower_components)/
			},
			{

				test: /editor\.s?css$/,
				use: [
					{
						loader: Extract.loader,
						options: { minimize: true }
					},
					'css-loader',
					'sass-loader'
				],
				exclude: /(node_modules|bower_components)/
			},
			{
				test: /style\.s?css$/,
				use: [
					{
						loader: Extract.loader,
						options: { minimize: true }
					},
					'css-loader',
					'sass-loader'
				],
				exclude: /(node_modules|bower_components)/
			}
		]
	}
};
