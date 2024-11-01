import TextFragment from "./TextFragment";
import ImageFragment from "./ImageFragment";

export default class PrintPage {
    constructor() {
        this.textFragments = [];
        this.imageFragments = [];
    }

    addTextFragment(x, y, text, fontSize) {
        const l = this.textFragments.push(new TextFragment(x, y, text, fontSize));

        return this.textFragments[l - 1];
    }

    addImageFragment(x, y, width, height, url) {
        const l = this.imageFragments.push(new ImageFragment(x, y, width, height, url));

        return this.imageFragments[l - 1];
    }
};