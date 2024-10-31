import PrintPageBuilder from "./PrintPageBuilder";

export default class CanvasPrintPageBuilder extends PrintPageBuilder {
    constructor(ctx) {
        super();

        this.canvasCtx = ctx;
    }

    measureWidth(text, fontSize) {
        this.canvasCtx.font = fontSize + "pt Calibri";

        return this.canvasCtx.measureText(text).width;
    }
};