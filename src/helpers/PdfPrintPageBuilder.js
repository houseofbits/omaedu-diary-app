import PrintPageBuilder from "./PrintPageBuilder";
import { PDFDocument, StandardFonts, rgb } from "pdf-lib";

export default class PdfPrintPageBuilder extends PrintPageBuilder {
    constructor(document, font) {
        super();

        this.pdfDocument = document;
        this.pdfFont = font;
    }


    measureWidth(text, fontSize) {
        return this.pdfFont.widthOfTextAtSize(text, fontSize * 1.34);
    }
};