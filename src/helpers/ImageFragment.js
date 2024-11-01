export default class ImageFragment {
    constructor(x, y, width, height, url) {
        this.y = y;
        this.x = x;
        this.width = width;
        this.height = height;
        this.url = url;
    }

    getAspect() {
        return this.height / this.width;
    }
};