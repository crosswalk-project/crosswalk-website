# Intel Crosswalk

Prepared by Chloi Inc. for Troy Allison of Intel.

- Brock Whitten, [brock@chloi.io](mailto:brock@chloi.io)
- Kenneth Ormandy, [kenneth@chloi.io](mailto:kenneth@chloi.io)

## Run in production

1. Install NodeJS [download here](http://nodejs.org/download/)

2. Install [Harp](http://harpjs.com) web server...

        npm install --gobal harp

3. Clone the project...

        git clone https://github.com/chloi/intel-crosswalk path/to/project

4. Start the server in production mode

         --port 80

## Running the project

This static site for Crosswalk is built with:

- [Node.js](http://nodejs.org)
- [Harp](http://harpjs.com), the static web server with build-in preprocessing
- [KSS](https://github.com/kss-node/kss-node), to build the styleguide

Once Node.js is installed, just run the following commands:

```sh
# Install Harp. You may need to preface this command with `sudo`
npm install -g harp

# Clone this project from GitHub
git clone https://github.com/chloi/intel-crosswalk

# Install the project’s dependencies
npm install

# Serve the project
harp server

# The project is now available at http://localhost:9000
```

### Run in production

To run Harp in production:

```
NODE_ENV=production harp server
```

You can also use Harp to compile the site down to flat files, which can then be
hosted on any web server. These have been included within the repository already.


```
harp compile
```

Note that this site has been built to take advantage of Harp’s niceties, so the
web server should:

- Create clean URLs by rewriting, for example, `about.html` to `about/`
- Allow absolute paths from`/`

### Build the Styleguide

This site comes with its markup and CSS modules documented in a Styleguide. It’s comparable to a miniature version of the [documentation for Bootstrap](http://getbootstrap.com/css/), where each module has an example and the accompanying code.

To build the Styleguide, run the following commands:

```sh
# Install dependencies
npm install

# Build the Styleguide
npm run styleguide

# Serve the project
harp server

# Site and Styleguide now available at http://localhost:9000/styleguide
```

![](assets/screenshot-styleguide.png)
