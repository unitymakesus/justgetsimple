import Macy from 'macy';

export default {
  init() {
  },
  finalize() {
    if (document.querySelector('.grid') !== null) {
      var macyGrid = Macy({   // eslint-disable-line no-unused-vars
        container: '.grid',
        trueOrder: true,
        mobileFirst: true,
        columns: 1,
        margin: {
          x: 20,
          y: 20,
        },
        breakAt: {
          768: {
            columns: 2,
          },
          992: {
            columns: 3,
          },
        },
      });

      // Recalc on image (lazy) load.
      $(document).on('lazyloaded', () => {
        macyGrid.recalculate(true);
      });
    }
  },
};
