const path = require('path');
const webpack = require('webpack');
// CSSを別のファイルで出力
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
// 空のJSファイルを出力しない
const FixStyleOnlyEntriesPlugin = require('webpack-fix-style-only-entries');
// 画像をコピーするために利用する
const CopyWebpackPlugin = require('copy-webpack-plugin');
// 圧縮するために利用するプラグイン
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const ImageminMozjpeg = require('imagemin-mozjpeg');
const ImageminPngquant = require('imagemin-pngquant');
const ImageminGifsicle = require('imagemin-gifsicle');
const ImageminSvgo = require('imagemin-svgo');
const ImageminWebp = require('imagemin-webp');

module.exports = (env, argv) => ({
	context: path.join(__dirname),
	// メインとなるJavaScriptファイル（エントリーポイント）
	entry: {
		app: './js/app.js',
		admins: './js/admins.js',
		style: './sass/style.scss',
		admin: './sass/admin.scss',
	},
	output: {
		path: path.join(__dirname, '../src/'),
		publicPath: '/src/',
		filename: 'js/[name].js'
	},
	module: {
		rules: [{
			// 拡張子 .js の場合
			test: /\.js$/,
			use: [{
				// Babel を利用する
				loader: 'babel-loader',
				// Babel のオプションを指定する
				options: {
					presets: [
						// プリセットを指定することで、ES2018 を ES5 に変換
						'@babel/preset-env',
					]
				}
			}],
			// node_modules は除外する
			exclude: /node_modules\/(?!(dom7|swiper)\/).*/,
		},
		{
			test: /\.scss$/,
			use: [{
				loader: MiniCssExtractPlugin.loader,
			},
			{
				loader: 'css-loader',
				options: {
					// オプションでCSS内のurl()メソッドの取り込みを禁止する
					url: false,
					// ソースマップの利用有無
					sourceMap: (argv.mode === 'development') ? true : false,
					// CSSの空白文字を削除する
					//minimize: true,
					// 0 => no loaders (default);
					// 1 => postcss-loader;
					// 2 => postcss-loader, sass-loader
					importLoaders: 2
				}
			},
			{
				loader: "sass-loader",
				options: {
					// ソースマップの利用有無
					sourceMap: (argv.mode === 'development') ? true : false,
					outputStyle: (argv.mode === 'development') ? 'expanded' : 'compressed'
				}
			}
			]
		},
		{
			test: /\.(gif|png|jpg|eot|wof|woff|woff2|ttf|svg)$/,
			use: [{
				loader: 'url-loader'
			}]
		}
		]
	},
	plugins: [
		// bootstrap のコードから jQuery が直接見えるように
		new webpack.ProvidePlugin({
			$: "jquery",
			jQuery: "jquery",
			"window.jQuery": "jquery",
			"window.$": "jquery",
			Popper: ['popper.js', 'default'],
			IScroll: 'iscroll',
		}),
		new FixStyleOnlyEntriesPlugin(),
		new MiniCssExtractPlugin({
			filename: 'css/[name].css'
		}),
		new CopyWebpackPlugin([{
			from: path.join(__dirname, './img'),
			to: path.join(__dirname, '../src/img')
		}]),
		new ImageminPlugin({
			test: /\.(jpe?g|png|gif|svg)$/i,
			plugins: [
				//ImageminWebp({ quality: 80 }),
				ImageminMozjpeg({ quality: 80 }),
				ImageminPngquant({ quality: [.65, .80] }),
				ImageminGifsicle(),
				ImageminSvgo()
			]
		})
	],
	resolve: {
		alias: {
			'jquery': require.resolve('jquery'),
		}
	},
	performance: {
		hints: false
	},
	devServer: {
		//public: '192.168.10.10/coding-rule',
		publicPath: '/src/',
		contentBase: path.join(__dirname, '../'),
		watchContentBase: true,
		index: 'index.html',
		watchOptions: {
			ignored: /node_modules/
		},
		open: true
	},
	devtool: "source-map" // ソースマップツールを有効
});