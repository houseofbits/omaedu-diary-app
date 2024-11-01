import Rect from "./Rect";

export default class PageLayout {
    constructor() {
        this.columns = [];
        this.imageRegions = [];
    }

    static createDefault() {
        return (new PageLayout()).addColumn(0, 0, 595, 842, 56, 56, 56, 56);
    }

    addColumn(x, y, width, height, ml, mr, mt, mb) {
        this.columns.push(new Rect(x, y, width, height, mr, ml, mt, mb));

        return this;
    }

    addImageRegion(x, y, width, height, ml, mr, mt, mb) {
        this.imageRegions.push(new Rect(x, y, width, height, mr, ml, mt, mb));

        return this;
    }

    getAvailableLineSegments(column, linePosY) {
        const columnMin = column.getInnerX();
        const columnMax = column.getInnerRight();
        const exclusion = [];
        for (let i = 0; i < this.imageRegions.length; i++) {
            if (linePosY >= this.imageRegions[i].getY() &&
                linePosY < this.imageRegions[i].getBottom()
            ) {

                exclusion.push({
                    min: this.imageRegions[i].getX(),
                    max: this.imageRegions[i].getRight(),
                });
            }
        }

        exclusion.sort((a, b) => a.min > b.min);

        let prev = columnMin;
        const segments = [];
        for (let i = 0; i < exclusion.length; i++) {
            const width = exclusion[i].min - prev;

            if (width <= 0) {
                prev = Math.max(exclusion[i].max, columnMin);

                continue;
            }

            segments.push({
                x: prev,
                width,
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
            ctx.strokeStyle = "rgba(0,0,1,0.3)";
            ctx.beginPath();
            ctx.rect(this.columns[i].getX(), this.columns[i].getY(), this.columns[i].getWidth(), this.columns[i].getHeight());
            ctx.setLineDash([6]);
            ctx.stroke();

            ctx.beginPath();
            ctx.rect(this.columns[i].getInnerX(), this.columns[i].getInnerY(), this.columns[i].getInnerWidth(), this.columns[i].getInnerHeight());
            ctx.setLineDash([6]);
            ctx.stroke();            
        }

        for (let i = 0; i < this.imageRegions.length; i++) {
            ctx.strokeStyle = "rgba(1,0,0,0.3)";
            ctx.beginPath();
            ctx.rect(this.imageRegions[i].getX(), this.imageRegions[i].getY(), this.imageRegions[i].getWidth(), this.imageRegions[i].getHeight());
            ctx.setLineDash([6]);
            ctx.stroke();

            ctx.beginPath();
            ctx.rect(this.imageRegions[i].getInnerX(), this.imageRegions[i].getInnerY(), this.imageRegions[i].getInnerWidth(), this.imageRegions[i].getInnerHeight());
            ctx.setLineDash([6]);
            ctx.stroke();            
        }
    }
};