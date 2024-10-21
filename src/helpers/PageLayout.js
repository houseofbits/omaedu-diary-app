import Rect from "./Rect";

export default class PageLayout {
    constructor(columns, regions) {
        this.columns = columns || [new Rect(56, 56, 483, 730)];
        this.imageRegions = regions || [];
    }

    addColumn(x, y, width, height) {
        this.columns.push(new Rect(x, y, width, height));
    }

    addImageRegion(x, y, width, height) {
        this.imageRegions.push(new Rect(x, y, width, height));
    }

    getAvailableLineSegments(columnIndex, linePosY) {
        const columnMin = this.columns[columnIndex].x;
        const columnMax = columnMin + this.columns[columnIndex].width;

        const exclusion = [];
        for (let i = 0; i < this.imageRegions.length; i++) {
            if (linePosY >= this.imageRegions[i].y &&
                linePosY < (this.imageRegions[i].y + this.imageRegions[i].height)
            ) {

                exclusion.push({
                    min: this.imageRegions[i].x,
                    max: this.imageRegions[i].x + this.imageRegions[i].width,
                });
            }
        }

        exclusion.sort((a, b) => a.min > b.min);

        let prev = columnMin;
        const segments = [];
        for (let i = 0; i < exclusion.length; i++) {
            if ((exclusion[i].min - prev) < 0) {
                prev = Math.max(exclusion[i].max, columnMin);

                continue;
            }

            segments.push({
                x: prev,
                width: exclusion[i].min - prev,
            });

            prev = Math.max(exclusion[i].max, columnMin);
        }

        segments.push({
            x: prev,
            width: columnMax - prev,
        });

        return segments;
    }

    drawDebugBorders(ctx) {
        for (let i = 0; i < this.columns.length; i++) {
            ctx.strokeStyle = "blue";
            ctx.rect(this.columns[i].x, this.columns[i].y, this.columns[i].width, this.columns[i].height);
            ctx.setLineDash([6]);
            ctx.stroke();
        }

        for (let i = 0; i < this.imageRegions.length; i++) {
            ctx.strokeStyle = "red";
            ctx.rect(this.imageRegions[i].x, this.imageRegions[i].y, this.imageRegions[i].width, this.imageRegions[i].height);
            ctx.setLineDash([6]);
            ctx.stroke();
        }
    }
};