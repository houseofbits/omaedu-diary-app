import { PDFDocument, StandardFonts, rgb } from "pdf-lib";
import fontKit from '@pdf-lib/fontkit';
import PdfPrintPageBuilder from "./PdfPrintPageBuilder";
import PdfPrintPageRenderer from "./PdfPrintPageRenderer";
import PageLayout from "./PageLayout";
import {
    fetchChapter,
} from "./../api/api.js";

export default class PdfBuilder {

    constructor() {
        this.pdfDocument = null;
        this.pdfFont = null;
        this.pdfBuilder = null;
        this.pdfRenderer = null;
        this.pageNum = 1;
    }

    async build(userCredentials, chapters) {
        this.pageNum = 1;
        this.pdfDocument = await PDFDocument.create();
        this.pdfFont = await this.loadFont('/dist/assets/calibri.ttf');

        this.pdfBuilder = new PdfPrintPageBuilder(this.pdfDocument, this.pdfFont);
        this.pdfRenderer = new PdfPrintPageRenderer();

        const layout = new PageLayout();

        for (let i = 0; i < chapters.length; i++) {
            const chapterData = await fetchChapter(userCredentials, chapters[i].id);

            this.pdfBuilder.setText(chapterData.story);

            //First page
            this.createChapterFirstPage(layout, chapterData);

            while (this.pdfBuilder.hasTextRemaining()) {
                this.createPage(layout);
            }
        }

        return this.pdfDocument;
    }

    async download(pdfDocument, fileName) {
        const pdfBytes = await pdfDocument.save();
        var blob = new Blob([pdfBytes], { type: "application/pdf" });
        var link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        link.download = fileName + ".pdf";
        link.click();
    }

    createChapterFirstPage(layout) {
        const pdfPage = this.createPage(layout);

        return pdfPage;
    }

    createPage(layout) {
        this.pdfBuilder.setPageNumber(this.pageNum);
        this.pdfBuilder.buildPage(layout);
        const pdfPage = this.pdfDocument.addPage();
        this.pdfRenderer.renderText(this.pdfBuilder.page, pdfPage, this.pdfFont);

        //Add images


        this.pageNum++;

        return pdfPage;
    }

    async loadFont(fontUrl) {
        const fontBytes = await fetch(fontUrl).then(res => res.arrayBuffer())
        this.pdfDocument.registerFontkit(fontKit);
        return await this.pdfDocument.embedFont(fontBytes);
    }
};