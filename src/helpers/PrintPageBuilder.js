import PrintPage from "./PrintPage";

const LINE_HEIGHT = 21;
const FONT_SIZE = 12;
const PAGE_NUM_FONT_SIZE = 10;

export default class PrintPageBuilder {
    constructor() {
        this.remainingText = "";
        this.page = null;
        this.textStart = 0;
        this.isGenerateHeaderEnabled = false;
        this.isGeneratePageNumberEnabled = true;
        this.pageNumber = 0;
        this.title = "";
        this.date = "";
        this.location = "";
    }

    enableHeader(isEnabled) {
        this.isGenerateHeaderEnabled = isEnabled;
    }

    setPageNumber(num) {
        this.pageNumber = num;
    }

    setText(text) {
        this.remainingText = text;
    }

    setHeader(title, date, location) {
        this.title = title;
        this.date = date;
        this.location = location;
    }

    hasTextRemaining() {
        return this.remainingText.length > 0;
    }

    /**
     * @param {PageLayout} layout 
     */
    buildPage(layout) {
        this.page = new PrintPage();

        if (this.isGenerateHeaderEnabled) {
            this.addHeader();
        }

        for (let i = 0; i < layout.columns.length; i++) {
            this.buildColumn(layout, layout.columns[i]);
        }

        if (this.isGeneratePageNumberEnabled) {
            this.addPageNumber();
        }
    }

    buildColumn(layout, column) {
        let posy = LINE_HEIGHT + Math.max(column.getInnerY(), this.textStart);
        const columnBottom = column.getInnerBottom();

        while (posy < columnBottom) {
            const lineSegments = layout.getAvailableLineSegments(column, posy);
            if (lineSegments.length > 0) {
                for (let i = 0; i < lineSegments.length; i++) {
                    const segment = lineSegments[i];
                    this.buildLine(segment.x, posy, segment.width);
                }
            } else {
                this.buildLine(column.getInnerX(), posy, column.getInnerWidth());
            }

            posy += LINE_HEIGHT;
        }
    }

    buildLine(x, y, width) {
        if (this.remainingText.length == 0) {
            return;
        }

        let previousValidBreakIndex = 0;
        for (let i = 0; i < this.remainingText.length; i++) {
            if (this.remainingText.at(i) === '\n') {
                if (i > 0) {
                    this.page.addTextFragment(x, y, this.remainingText.substring(0, i), FONT_SIZE);
                }
                this.remainingText = this.remainingText.slice(i + 1);

                return;
            }

            const lineText = this.remainingText.substring(0, i);
            const lineWidth = this.measureWidth(lineText, FONT_SIZE);

            if (this.remainingText.at(i) === " " && lineWidth <= width) {
                previousValidBreakIndex = i + 1;
            }
            if (lineWidth > width && previousValidBreakIndex > 0) {
                this.page.addTextFragment(x, y, this.remainingText.substring(0, previousValidBreakIndex), FONT_SIZE);
                this.remainingText = this.remainingText.slice(previousValidBreakIndex);

                return;
            }
        }

        const lineWidth = this.measureWidth(this.remainingText, FONT_SIZE);
        if (lineWidth <= width) {
            this.page.addTextFragment(x, y, this.remainingText, FONT_SIZE);
            this.remainingText = "";
        }
    }

    addHeader() {

    }

    addPageNumber() {
        const numText = this.pageNumber.toString();
        const lineWidth = this.measureWidth(numText, PAGE_NUM_FONT_SIZE);

        this.page.addTextFragment(297 - (lineWidth / 2), 842 - PAGE_NUM_FONT_SIZE * 3, numText, PAGE_NUM_FONT_SIZE);
    }

    measureWidth(text, fontSize) {
        return 0;
    }
};