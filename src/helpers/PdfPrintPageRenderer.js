import { rgb } from "pdf-lib";

export default class PdfPrintPageRenderer {

    renderText(printPage, pdfPage, font) {
        const { width, height } = pdfPage.getSize();
        
        for (let i = 0; i < printPage.textFragments.length; i++) {
            const textFragment = printPage.textFragments[i];
            pdfPage.drawText(textFragment.text, {
                x: textFragment.positionLeft,
                y: height - textFragment.positionTop,
                size: (textFragment.fontSizePt * 1.34),       //px
                font: font,
                color: rgb(0, 0, 0),
            });
        }
    }

};