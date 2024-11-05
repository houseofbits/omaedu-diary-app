import PageLayout from "../helpers/PageLayout";
import Rect from "../helpers/Rect";


export const PAGE_LAYOUTS = {
    'default': {
        layout: PageLayout.createDefault(),
        image: '/assets/images/DEFAULT.jpg',
    },
    'l1': {
        layout: PageLayout.createDefault()
            .addImageRegion(56, 373, 241, 412, 0, 20, 10, 0)
            .addImageRegion(299, 595, 241, 190, 0, 0, 20, 0),
        image: '/assets/images/L1.jpg',
    },
    'l2': {
        layout: PageLayout.createDefault().addImageRegion(56, 555, 483, 230, 0, 0, 20, 0),
        image: '/assets/images/L2.jpg',
    },
    'l3': {
        layout: PageLayout.createDefault()
            .addImageRegion(56, 406, 241, 379, 0, 10, 20, 0)
            .addImageRegion(299, 406, 241, 379, 10, 0, 20, 0),
        image: '/assets/images/L3.jpg',
    },
    'l4': {
        layout: PageLayout.createDefault()
            .addImageRegion(56, 601, 241, 184, 0, 10, 20, 0)
            .addImageRegion(299, 601, 241, 184, 10, 0, 20, 0),
        image: '/assets/images/L4.jpg',
    },
    'l5': {
        layout: PageLayout.createDefault().addImageRegion(56, 529, 256, 256, 0, 20, 20, 0),
        image: '/assets/images/L5.jpg',
    },
    'l6': {
        layout: PageLayout.createDefault().addImageRegion(56, 420, 241, 364, 0, 20, 20, 0),
        image: '/assets/images/L6.jpg',
    },
    'l7': {
        layout: PageLayout.createDefault().addImageRegion(284, 529, 256, 256, 20, 0, 20, 0),
        image: '/assets/images/L7.jpg',
    },
    'l8': {
        layout: PageLayout.createDefault().addImageRegion(299, 420, 241, 364, 20, 0, 20, 0),
        image: '/assets/images/L8.jpg',
    },
    //----------------------------------------------------------
    //Additional pages
    'l9': {
        layout: PageLayout.createDefault(true)
            .addImageRegion(56, 56, 241, 365, 0, 10, 0, 10)
            .addImageRegion(298, 56, 241, 365, 10, 0, 0, 10)
            .addImageRegion(56, 420, 241, 365, 0, 10, 10, 0)
            .addImageRegion(298, 420, 241, 365, 10, 0, 10, 0),
        image: '/assets/images/L9.jpg',
    },
    'l10': {
        layout: PageLayout.createDefault(true)
            .addImageRegion(56, 56, 241, 243, 0, 10, 0, 10)
            .addImageRegion(56, 299, 241, 243, 0, 10, 10, 10)
            .addImageRegion(56, 542, 241, 243, 0, 10, 10, 0)
            .addImageRegion(298, 56, 241, 365, 10, 0, 0, 10)
            .addImageRegion(298, 420, 241, 365, 10, 0, 10, 0),
        image: '/assets/images/L10.jpg',
    },
    'l11': {
        layout: PageLayout.createDefault(true)
            .addImageRegion(56, 56, 241, 243, 0, 10, 0, 10)
            .addImageRegion(56, 299, 241, 243, 0, 10, 10, 10)
            .addImageRegion(56, 542, 241, 243, 0, 10, 10, 0)
            .addImageRegion(298, 56, 241, 243, 10, 0, 0, 10)
            .addImageRegion(298, 299, 241, 243, 10, 0, 10, 10)
            .addImageRegion(298, 542, 241, 243, 10, 0, 10, 0),
        image: '/assets/images/L11.jpg',
    },
    'l12': {
        layout: PageLayout.createDefault(true)
            .addImageRegion(56, 299, 241, 243, 0, 20, 20, 10)
            .addImageRegion(56, 542, 241, 243, 0, 20, 10, 0)
            .addImageRegion(298, 420, 241, 365, 0, 0, 20, 0),
        image: '/assets/images/L12.jpg',
    },
    'l13': {
        layout: PageLayout.createDefault(true).addImageRegion(56, 56, 483, 219, 0, 0, 0, 20),
        image: '/assets/images/L13.jpg',
    },
    'l14': {
        layout: PageLayout.createDefault(true)
            .addImageRegion(56, 56, 241, 208, 0, 10, 0, 20)
            .addImageRegion(298, 56, 241, 208, 10, 0, 0, 20),
        image: '/assets/images/L14.jpg',
    },
    'l15': {
        layout: PageLayout.createDefault(true)
            .addImageRegion(56, 56, 241, 208, 0, 10, 0, 20)
            .addImageRegion(298, 56, 241, 208, 10, 0, 0, 20)
            .addImageRegion(56, 577, 241, 208, 0, 10, 20, 0)
            .addImageRegion(298, 577, 241, 208, 10, 0, 20, 0),
        image: '/assets/images/L15.jpg',
    },
    'l16': {
        layout: PageLayout.createDefault(true).addImageRegion(284, 56, 256, 256, 20, 0, 0, 20),
        image: '/assets/images/L16.jpg',
    },
    'l17': {
        layout: PageLayout.createDefault(true).addImageRegion(56, 56, 256, 256, 0, 20, 0, 20),
        image: '/assets/images/L17.jpg',
    },
};
