
// import Swiper bundle with all modules installed
import Swiper from 'swiper/bundle';

// import styles bundle
import 'swiper/css/bundle';

// init Swiper:
const swiper = new Swiper('#wallet-swiper', {
    // Optional parameters
    loop: true,
    // autoplay: true,

    // If we need pagination
    pagination: {
        el: '.swiper-pagination',
    },
})
