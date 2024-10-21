
export default class CanvasTextFormatter {
    constructor() {
        this.remainingText = "";
    }

    draw(ctx, text, pageLayout, isDebugBoxesVisible) {
        const words = text.split(" ");

        ctx.font = "12pt Arial";
        ctx.fillStyle = "black";

        for (let i = 0; i < pageLayout.columns.length; i++) {
            this.drawColumn(ctx, words, i, pageLayout);
        }

        if (isDebugBoxesVisible) {
            pageLayout.drawDebugBorders(ctx);
        }

        this.remainingText = words.join(" ");

        return words.length > 0;
    }

    drawColumn(ctx, words, columnIndex, pageLayout) {
        const lineHeight = 21;

        let posy = lineHeight + pageLayout.columns[columnIndex].y;
        const columnRight = pageLayout.columns[columnIndex].x + pageLayout.columns[columnIndex].width;
        const columnBottom = pageLayout.columns[columnIndex].y + pageLayout.columns[columnIndex].height;

        while (posy < columnBottom) {
            const lineSegments = pageLayout.getAvailableLineSegments(columnIndex, posy);
            if (lineSegments.length > 0) {
                for (let i = 0; i < lineSegments.length; i++) {
                    const segment = lineSegments[i];
                    this.drawLine(ctx, words, segment.x, posy, segment.width);
                }
            } else {
                this.drawLine(ctx, words, pageLayout.columns[columnIndex].x, posy, pageLayout.columns[columnIndex].width);
            }

            posy += lineHeight;
        }
    }

    drawLine(ctx, words, posX, posY, maxWidth) {
        var currentLine = "";
        while (words.length > 0) {
            let width;
            if (currentLine.length > 0) {
                width = ctx.measureText(currentLine + " " + words[0]).width;
            } else {
                width = ctx.measureText(words[0]).width;
            }

            if (width < maxWidth) {
                const word = words.shift();
                currentLine += word + " ";
            } else {
                ctx.fillText(currentLine.trim(), posX, posY);

                return;
            }
        }
    }
};