// import external dependencies
import 'jquery';
import 'custom-event-polyfill';
import 'modaal';

// Import everything from autoload
import './autoload/*';

// import local dependencies
import Router from './util/Router';
import common from './routes/common';
import archive from './routes/archive';

/** Populate Router instance with DOM routes */
const routes = new Router({
  common,
  archive,
});

// Load Events
jQuery(document).ready(() => routes.loadEvents());
