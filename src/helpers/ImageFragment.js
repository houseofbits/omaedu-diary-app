export default class ImageFragment {
    constructor(x, y, width, height, url) {
        this.x = x;
        this.y = y;
        this.width = width;
        this.height = height;
        this.url = url;
    }

    getAspect() {
        return this.height / this.width;
    }
};