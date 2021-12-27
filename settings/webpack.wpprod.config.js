/* eslint-disable import/no-extraneous-dependencies */
const { merge } = require('webpack-merge');

const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const path = require('path');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const webpackConfiguration = require('../webpack.config');
const environment = require('./environment');

module.exports = merge(webpackConfiguration, {
	mode: 'production',

	/* Manage source maps generation process. Refer to https://webpack.js.org/configuration/devtool/#production */
	devtool: false,

	/* Additional plugins configuration */
	plugins: [
		new CleanWebpackPlugin({
			verbose: true,
			cleanOnceBeforeBuildPatterns: [
				path.resolve(environment.paths.wpOutput, 'assets', 'static'),
				path.resolve(environment.paths.wpOutput, 'assets', 'images'),
				path.resolve(environment.paths.wpOutput, 'js'),
				path.resolve(environment.paths.wpOutput, 'styles'),
				path.resolve(environment.paths.wpOutput, 'fonts'),
			],
		}),
		new CopyWebpackPlugin({
			patterns: [
				{
					from: path.resolve(environment.paths.output, 'images'),
					to: path.resolve(environment.paths.wpOutput, 'assets', 'images'),
				},
				{
					from: path.resolve(environment.paths.source, 'static'),
					to: path.resolve(environment.paths.wpOutput, 'assets', 'static'),
				},
				{
					from: path.resolve(environment.paths.output, 'js'),
					to: path.resolve(environment.paths.wpOutput, 'js'),
				},
				{
					from: path.resolve(environment.paths.output, 'css'),
					to: path.resolve(environment.paths.wpOutput, 'styles'),
				},
				{
					from: path.resolve(environment.paths.output, 'fonts'),
					to: path.resolve(environment.paths.wpOutput, 'fonts'),
				},
			],
		}),
	],
});
