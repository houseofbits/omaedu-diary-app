

export default class CanvasPrintPageRenderer {
    renderText(ctx, page) {
        ctx.font = "12pt Calibri";
        ctx.fillStyle = "black";
        for (let i = 0; i < page.textFragments.length; i++) {
            const textFragment = page.textFragments[i];
            ctx.font = textFragment.fontSizePt + "pt Calibri";
            ctx.fillText(
                textFragment.text,
                textFragment.positionLeft,
                textFragment.positionTop
            );
        }
    }

    renderLayout(ctx, layout) {
        layout.drawDebugBorders(ctx);
    }
};