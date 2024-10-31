import PageLayout from "../helpers/PageLayout";
import Rect from "../helpers/Rect";


export const PAGE_LAYOUTS = {
    'default': {
        layout: new PageLayout(),
        image: '',
    },
    'l1': {
        layout: new PageLayout(null, [new Rect(56, 300, 200, 200)]),
        image: '',
    }    
};
