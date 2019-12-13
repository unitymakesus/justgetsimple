import Macy from 'macy';

export default {
  init() {
  },
  finalize() {
    if (document.querySelector('.grid') !== null) {
      var macyGrid = Macy({   // eslint-disable-line no-unused-vars
        container: '.grid',
        trueOrder: true,
        columns: 2,
        margin: {
          x: 20,
          y: 30,
        },
        breakAt: {
          767: 1,
        },
      });

      // Recalc layout from WP Smush lazyload event (uses lazysizes lib)
      // https://github.com/aFarkas/lazysizes
      $(document).on('lazyloaded', () => {
        macyGrid.recalculate(true);
      });
    }
  },
};
