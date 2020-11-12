import Macy from 'macy';

export default {
  finalize() {
    if (document.querySelector('.grid') !== null) {
      let macyGrid = Macy({ // eslint-disable-line no-unused-vars
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

      // Recalculate the layout for lazyloaded images (via Smush).
      document.addEventListener('lazyloaded', () => {
        macyGrid.recalculate(true);
      });
    }
  },
};
