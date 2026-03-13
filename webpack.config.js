const path = require('path');

module.exports = {
  entry: './app/resources/js/app.js',
  output: {
    filename: 'app.js',
    path: path.resolve(__dirname, 'public/js'),
  },
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
        use: [
          {
            // Creates `style` nodes from JS strings
            loader: 'style-loader',
            options: {
              // Insert styles BEFORE the marker, so theme CSS loads after and can override
              insert: function(styleElement) {
                const marker = document.querySelector('meta[name="webpack-styles-end"]');
                if (marker) {
                  marker.parentNode.insertBefore(styleElement, marker);
                } else {
                  // Fallback: insert at end of head
                  document.head.appendChild(styleElement);
                }
              },
            },
          },
          // Translates CSS into CommonJS
          'css-loader',
          // Compiles Sass to CSS
          'sass-loader',
        ],
      },
    ],
  },
};
