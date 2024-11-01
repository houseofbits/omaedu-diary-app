import PageLayout from "../helpers/PageLayout";
import Rect from "../helpers/Rect";


export const PAGE_LAYOUTS = {
    'default': {
        layout: PageLayout.createDefault(),
        image: '',
    },
    'l1': {
        layout: PageLayout.createDefault().addImageRegion(56, 300, 200, 200, 20, 0, 20, 20),
        image: '',
    }
};
