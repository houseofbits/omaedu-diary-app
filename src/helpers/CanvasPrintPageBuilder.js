import PrintPageBuilder from "./PrintPageBuilder";

export default class CanvasPrintPageBuilder extends PrintPageBuilder {
    constructor(ctx) {
        super();
        this.fontFace = "Corbel";
        this.canvasCtx = ctx;
    }

    measureWidth(text, fontSize) {
        this.canvasCtx.font = fontSize + "pt " + this.fontFace;

        return this.canvasCtx.measureText(text).width;
    }
};