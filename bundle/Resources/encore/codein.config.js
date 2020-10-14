const path = require('path');
const addJSEntries = require('./codein.js.config.js');
const addCSSEntries = require('./codein.css.config.js');

module.exports = (Encore) => {
    Encore.reset();
    Encore.setOutputPath('web/assets/build')
        .setPublicPath('/assets/build')
        .addExternals({
            react: 'React',
            'react-dom': 'ReactDOM'
        })
        .copyFiles({
            from: path.resolve(__dirname,'../public/img'),
            to: 'images/[name].[ext]'
        })

    addJSEntries(Encore);
    addCSSEntries(Encore);
    const codeinSeoToolkitConfig = Encore.getWebpackConfig(); 

    return codeinSeoToolkitConfig;
};
