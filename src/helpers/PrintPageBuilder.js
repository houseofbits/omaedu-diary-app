import PrintPage from "./PrintPage";

const LINE_HEIGHT = 21;
const FONT_SIZE = 12;
const PAGE_NUM_FONT_SIZE = 10;
const HEADER_FONT_SIZE = 20;
const SUB_HEADER_FONT_SIZE = 10;

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
        this.imageUrls = [];
    }

    enableHeader(isEnabled) {
        this.isGenerateHeaderEnabled = isEnabled;
        this.textStart = 0;
    }

    setPageNumber(num) {
        this.pageNumber = num;
    }

    setText(text) {
        this.remainingText = text;
    }

    setImages(imageUrls) {
        this.imageUrls = imageUrls;
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

        this.addImages(layout.imageRegions);
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
            }

            posy += LINE_HEIGHT;
        }
    }

    buildLine(x, y, width) {
        if (this.remainingText.length == 0) {
            return;
        }
        
        const { line, remaining } = this.generateTextLine(this.remainingText, width, FONT_SIZE);
        this.remainingText = remaining;
        if (line.length > 0) {
            this.page.addTextFragment(x, y, line, FONT_SIZE);
        }
    }

    addHeader() {
        let posY = 56;
        let title = this.title;
        while (true) {
            const { line, remaining } = this.generateTextLine(title, 483, HEADER_FONT_SIZE);
            if (line.length > 0) {
                title = remaining;
                this.page.addTextFragment(56, posY, line, HEADER_FONT_SIZE);

                posY = posY + (HEADER_FONT_SIZE * 1.3);
            } else {
                break;
            }
        }

        const dateWidth = this.measureWidth(this.date, SUB_HEADER_FONT_SIZE);

        this.page.addTextFragment(56, posY, this.location, SUB_HEADER_FONT_SIZE);
        this.page.addTextFragment(595 - 56 - dateWidth, posY, this.date, SUB_HEADER_FONT_SIZE);

        this.textStart = posY + SUB_HEADER_FONT_SIZE * 2;
    }

    addPageNumber() {
        const numText = this.pageNumber.toString();
        const lineWidth = this.measureWidth(numText, PAGE_NUM_FONT_SIZE);

        this.page.addTextFragment(297 - (lineWidth / 2), 842 - PAGE_NUM_FONT_SIZE * 3, numText, PAGE_NUM_FONT_SIZE);
    }

    measureWidth(text, fontSize) {
        return 0;
    }

    addImages(imageRegions) {
        if (imageRegions.length === 0) {
            return;
        }

        for (let i = 0; i < imageRegions.length; i++) {
            const region = imageRegions[i];
            if (this.imageUrls.length > 0) {
                const imageUrl = this.imageUrls.shift();
                this.page.addImageFragment(region.getInnerX(),
                    region.getInnerY(),
                    region.getInnerWidth(),
                    region.getInnerHeight(),
                    imageUrl);
            }
        }
    }

    generateTextLine(text, width, fontSize) {
        let previousValidBreakIndex = 0;
        for (let i = 0; i < text.length; i++) {
            if (text.at(i) === '\n') {
                let line = "";
                if (i > 0) {
                    line = text.substring(0, i);
                }
                text = text.slice(i + 1);

                return {
                    line,
                    remaining: text
                };
            }

            const lineText = text.substring(0, i);
            const lineWidth = this.measureWidth(lineText, fontSize);

            if (text.at(i) === " " && lineWidth <= width) {
                previousValidBreakIndex = i + 1;
            }
            if (lineWidth > width && previousValidBreakIndex > 0) {
                let line = text.substring(0, previousValidBreakIndex);
                text = text.slice(previousValidBreakIndex);

                return {
                    line,
                    remaining: text
                };
            }
        }

        const lineWidth = this.measureWidth(text, fontSize);
        if (lineWidth <= width) {
            let line = text;
            text = "";

            return {
                line,
                remaining: text
            };
        }

        return {
            line: "",
            remaining: ""
        };
    }
};