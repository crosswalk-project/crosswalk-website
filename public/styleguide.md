# Styleguide

All CSS is documented using KSS—specifically, the [Node implantation](https://github.com/hughsk/kss-node/). This creates a reliable way to build a style guide, and a consistent method of documenting CSS within the modules themselves.

## Structure

All CSS is written using the SCSS flavour of [Sass](http://sass-lang.com/), and is preprocessed automatically using [Harp](http://harpjs.com).

The CSS is contained within the following partials, which is based on conventions from [SMACSS](https://smacss.com/).

1. __Base__ Base styles are stored here
2. __Layout__ Site-wide layout styles are stored here, like main containers
3. __Module__ The majority of the CSS is imported here. Modules make up the majority of the site and are contained within the `module/` directory.
4. __State__ When working with JavaScript, store all state specific classes here. These should be prefixed with `.is-` or `.js-`.
5. __Shame__ If you’re unsure what to do, or need to fix something quickly, store it here. Just make sure to document the reason and locations it is used thoroughly, so it can be tidied up later.
6. __Vendor__ Unmodified dependencies are stored here. For example, Bourbon and Normalize.css are stored here.

## Generating

Once you have the project cloned locally, you can rebuild the style guide with the following commands:

```bash
npm install
npm run styleguide
harp server
# Visit http://localhost:9000/styleguide
```
