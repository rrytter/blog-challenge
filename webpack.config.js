const composer = require("./composer.json");
const path = require("path");
const glob = require("glob");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const UglifyJsPlugin = require("uglifyjs-webpack-plugin");
const ImageminPlugin = require("imagemin-webpack-plugin").default;
const NotifierPlugin = require("webpack-notifier");
const WebpackBar = require("webpackbar");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const { WebpackManifestPlugin } = require("webpack-manifest-plugin");
const IconfontPlugin = require('iconfont-plugin-webpack');

// const src = "src/Resources/";
const src = "assets/";
const dest = "public/build/";

var config = {
  devServer: { stats: { chunks: false } },
  mode: "production",
  devtool: "source-map",
  entry: {
    main: [
        path.resolve(__dirname, src, "js/main.js"),
        path.resolve(__dirname, src, "scss/main.scss")
    ],
    // admin: [
    //   path.resolve(__dirname, src, "js/admin.js"),
    //   path.resolve(__dirname, src, "css/admin.scss")
    // ]
  },
  output: {
    path: path.resolve(__dirname, dest),
    filename: "[name].[contenthash].bundle.js"
  },
  module: {
    rules: [
      {
        test: /\.js/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ["@babel/preset-env"]
          }
        }
      },
      {
        test: /\.(sa|sc|c)ss$/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: "css-loader",
            options: { sourceMap: true }
          },
          {
            loader: "sass-loader",
            options: { sourceMap: true }
          },
          {
            loader: "postcss-loader",
            options: {
              sourceMap: true,
              postcssOptions: {
                plugins: () => [
                  require("autoprefixer")
                ]
              }
              // ident: "postcss",
            }
          }
        ],
      },
      {
        test: /.(woff(2)?|eot|ttf)(\?[a-z0-9=\.]+)?$/,
        use: [
          {
            loader: "file-loader",
            options: {
              name: "fonts/[name].[contenthash].[ext]"
            }
          }
        ]
      },
      {
        test: /.(png|jpg|svg|gif|ico)(\?[a-z0-9=\.]+)?$/,
        use: [
          {
            loader: "file-loader",
            options: {
              name: "img/[name].[contenthash].[ext]"
            }
          }
        ]
      },
      {
        test : /\.css$/,
        use: [{
          loader: "style-loader",
          options: { sourceMap: true },
        }, 
        {
          loader: "css-loader",
          options: { sourceMap: true },
        },
        {
          loader: "postcss-loader",
          options: {
            sourceMap: true,
            ident: "postcss",
            plugins: () => [
              require("autoprefixer")
            ]
          }
        }]
      },
      {
        test: /\.font\.js/,
        use: [
          MiniCssExtractPlugin.loader,
          "css-loader",
          {
            loader: "webfonts-loader",
            options: {
              name: "icons/[name].[contenthash].[ext]",
              publicPath: dest
            }
          }
        ]
      }
    ]
  },
  externals: {
    // $: "$",
    // jquery: "jQuery",
  },
  plugins: [
    new CleanWebpackPlugin({
      cleanOnceBeforeBuildPatterns: ["**/*", "!index.php", "!.gitignore", "!.gitkeep"],
      cleanAfterEveryBuildPatterns: ["!index.php", "!.gitignore", "!.gitkeep"]
    }),
    new WebpackManifestPlugin({
        basePath: src,
        publicPath: 'build/',
        map: function (file) {
            if (file.name.match(/main\.css/g)) {
                file.name = file.name.replace(/(main\.css)/, 'css/$1');
            }
            if (file.name.match(/main\.js/g)) {
                file.name = file.name.replace(/(main\.js)/, 'js/$1');
            }
            return file;
        }
    }),
    // new IconfontPlugin({
    //   src: 'src/Resources/icons',
    //   family: 'icons',
    //   dest: {
    //     font: 'src/Resources/fonts/[family].[type]',
    //     css: 'src/Resources/icons/_icon_font_[family].scss'
    //   },
    //   watch: {
    //     pattern: 'icons/*',
    //     cwd: undefined
    //   },
    // }),
    // new WebpackBar({
    //   profile: true,
    //   name: composer.description
    // }),
    new NotifierPlugin({
      title: composer.description,
      alwaysNotify: true,
      // contentImage: "resources/logo.png"
    }),
    new MiniCssExtractPlugin({
      filename: "[name].[contenthash].bundle.css"
    }),
    new ImageminPlugin({
      test: /\.(jpe?g|png|gif)$/i,
      pngquant: {
        quality: "95-100"
      }
    }),
  ],
  optimization: {
    minimizer: [
      new UglifyJsPlugin({
        cache: true,
        parallel: true,
        sourceMap: true,
        uglifyOptions: {
          compress: true,
          ecma: 6,
          mangle: true,
          comments: false
        },
      })
    ]
  },
  resolve: {
    alias: {}
  },
  //node: { fs: "empty" },
  target: "web"
};

// glob.sync(path.resolve(__dirname, src, "img/**/*.*")).forEach((file) => {
//   config.entry.main.push(file);
// });

module.exports = config;