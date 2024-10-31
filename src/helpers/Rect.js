

export default class Rect {
    #x = 0;
    #y = 0;
    #width = 0;
    #height = 0;
    #marginTop = 0;
    #marginBottom = 0;
    #marginLeft = 0;
    #marginRight = 0;

    constructor(x, y, width, height, ml, mr, mt, mb) {
        this.#x = x ?? 0;
        this.#y = y ?? 0;
        this.#width = width ?? 0;
        this.#height = height ?? 0;
        this.#marginRight = mr ?? 0;
        this.#marginLeft = ml ?? 0;
        this.#marginTop = mt ?? 0;
        this.#marginBottom = mb ?? 0;
    }

    getX() {
        return this.#x;
    }

    getY() {
        return this.#y;
    }

    getHeight() {
        return this.#height;
    }

    getWidth() {
        return this.#width;
    }

    getRight() {
        return this.getX() + this.getWidth();
    }

    getBottom() {
        return this.getY() + this.getHeight();
    }
    
    getInnerX() {
        return this.#x + this.#marginLeft;
    }

    getInnerY() {
        return this.#y + this.#marginTop;
    }

    getInnerHeight() {
        return this.#height - this.#marginTop - this.#marginBottom;
    }

    getInnerWidth() {
        return this.#width - this.#marginLeft - this.#marginRight;
    }

    getInnerRight() {
        return this.getInnerX() + this.getInnerWidth();
    }

    getInnerBottom() {
        return this.getInnerY() + this.getInnerHeight();
    }
}