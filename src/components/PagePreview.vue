<script setup>
import { computed, onMounted, ref, reactive, watch } from "vue";
import CanvasPrintPageBuilder from "../helpers/CanvasPrintPageBuilder";
import CanvasPrintPageRenderer from "../helpers/CanvasPrintPageRenderer";
import { PAGE_LAYOUTS } from "../constants/pageLayouts";
import LayoutsModal from "./LayoutsModal.vue";
import _ from "lodash";
import useSettings from "../composables/Settings";

const { settings, getDiaryBackgroundImageUrl } = useSettings();
const props = defineProps({
  chapter: Object,
  images: Array,
});

const emit = defineEmits(["change-layouts"]);

let ctx = null;
const canvasRef = ref(null);
const hasNextPage = ref(false);
const hasPrevPage = ref(false);
const previewPageNum = ref(0);
const isLayoutsModalVisible = ref(false);
let layouts = ["default"];

async function renderPage(ctx, text) {
  hasNextPage.value = false;
  hasPrevPage.value = previewPageNum.value > 0;

  const pageBuilder = new CanvasPrintPageBuilder(ctx);
  pageBuilder.isGeneratePageNumberEnabled = settings.isPageNumberingEnabled;
  pageBuilder.isTextJustifyEnabled = settings.isTextJustifyEnabled;
  
  pageBuilder.setText(text);
  pageBuilder.setImages(_.cloneDeep(props.images));
  pageBuilder.setHeader(
    props.chapter.title ?? "",
    props.chapter.period ?? "",
    props.chapter.location ?? ""
  );

  let currentLayout = null;
  for (let i = 0; i < layouts.length; i++) {
    const layoutData = PAGE_LAYOUTS[layouts[i]];
    currentLayout = layoutData.layout;

    pageBuilder.enableHeader(i === 0);
    pageBuilder.setPageNumber(previewPageNum.value + 1);
    pageBuilder.buildPage(layoutData.layout);

    if (i === previewPageNum.value) {
      break;
    }
  }

  hasNextPage.value = previewPageNum.value < layouts.length - 1;

  ctx.reset();
  const canvasPrintPageRenderer = new CanvasPrintPageRenderer();
  await canvasPrintPageRenderer.renderBackground(ctx, getDiaryBackgroundImageUrl());
  await canvasPrintPageRenderer.renderText(ctx, pageBuilder.page);
  await canvasPrintPageRenderer.renderImages(ctx, pageBuilder.page);
  canvasPrintPageRenderer.renderLayout(ctx, currentLayout);
}

function removeTemplate() {
  if (previewPageNum.value > 0) {
    layouts.splice(previewPageNum.value, 1);
    previewPageNum.value = previewPageNum.value - 1;

    if (ctx != null) {
      renderPage(ctx, props.chapter.story);
    }

    emit("change-layouts", layouts);
  }
}

function addPageRight() {
  layouts.splice(previewPageNum.value + 1, 0, "default");
  previewPageNum.value = previewPageNum.value + 1;
  isLayoutsModalVisible.value = true;
  emit("change-layouts", layouts);
}

function addPageLeft() {
  if (previewPageNum.value > 0) {
    layouts.splice(previewPageNum.value, 0, "default");
    previewPageNum.value = previewPageNum.value;
    isLayoutsModalVisible.value = true;
    emit("change-layouts", layouts);
  }
}

function selectLayout(layout) {
  layouts[previewPageNum.value] = layout;
  if (ctx != null) {
    renderPage(ctx, props.chapter.story);
  }
  emit("change-layouts", layouts);
}

watch(
  () => props.chapter.value,
  () => {
    if (ctx != null) {
      renderPage(ctx, props.chapter.story);
    }
  },
  { deep: true }
);

watch(
  () => previewPageNum.value,
  () => {
    if (ctx != null) {
      renderPage(ctx, props.chapter.story);
    }
  }
);

onMounted(async () => {
  layouts = props.chapter.layouts;
  if (!layouts?.length) {
    layouts = ["default"];
  }

  ctx = canvasRef.value.getContext("2d");
  if (ctx != null) {
    await document.fonts.load("12pt Corbel");
    await renderPage(ctx, props.chapter.story);
  }

  emit("change-layouts", layouts);
});
</script>

<template>
  <v-sheet class="preview-sheet mt-4 text-center mx-auto" max-width="800">
    <v-row>
      <v-col class="d-flex justify-center align-start flex-column" cols="1">
        <v-btn
          color="primary"
          class="px-3 py-2 ml-4"
          size="xl"
          min-width="35"
          rounded="xl"
          :disabled="!hasPrevPage"
          @click="previewPageNum--"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="20"
            width="12.5"
            viewBox="0 0 320 512"
          >
            <path
              fill="white"
              d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"
            />
          </svg>
        </v-btn>
      </v-col>
      <v-col class="flex-grow-1">
        <canvas
          id="page-canvas"
          width="595"
          height="842"
          class="elevation-4"
          ref="canvasRef"
        ></canvas>
      </v-col>
      <v-col class="d-flex justify-center align-end flex-column" cols="1">
        <v-btn
          id="menu-activator"
          color="primary"
          class="px-2 py-2 mr-4 mb-4"
          size="xl"
          min-width="35"
          rounded="xl"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="20"
            width="20"
            viewBox="0 0 512 512"
          >
            <path
              fill="white"
              d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160L0 416c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-96c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 64z"
            />
          </svg>
        </v-btn>

        <v-menu activator="#menu-activator">
          <v-list class="pa-0">
            <v-list-item density="compact" class="pa-0 ma-0">
              <v-btn
                variant="text"
                class="pa-2"
                size="small"
                block
                @click="addPageRight"
                >Add layout to the rigt</v-btn
              >
            </v-list-item>
            <v-list-item density="compact" class="pa-0 ma-0">
              <v-btn
                variant="text"
                class="pa-2"
                size="small"
                block
                :disabled="previewPageNum == 0"
                @click="addPageLeft"
                >Add layout to the left</v-btn
              >
            </v-list-item>
            <v-list-item density="compact" class="pa-0 ma-0">
              <v-btn
                variant="text"
                class="pa-2"
                size="small"
                block
                @click="isLayoutsModalVisible = true"
                >Change layout</v-btn
              >
            </v-list-item>
            <v-list-item density="compact" class="pa-0 ma-0">
              <v-btn
                variant="text"
                class="pa-2"
                size="small"
                block
                :disabled="previewPageNum == 0"
                @click="removeTemplate"
                >Delete layout</v-btn
              >
            </v-list-item>
          </v-list>
        </v-menu>

        <v-btn
          color="primary"
          class="px-3 py-2 mr-4"
          size="xl"
          min-width="35"
          rounded="xl"
          :disabled="!hasNextPage"
          @click="previewPageNum++"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="20"
            width="12.5"
            viewBox="0 0 320 512"
          >
            <path
              fill="white"
              d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"
            />
          </svg>
        </v-btn>
      </v-col>
    </v-row>
  </v-sheet>

  <layouts-modal
    v-model="isLayoutsModalVisible"
    :is-first-page="previewPageNum === 0"
    @select="selectLayout"
  />
</template>

<style>
#page-canvas {
  /* border: solid 1px blue; */
  width: 100%;
  border-radius: 8px;
  background-color: white;
}

.preview-sheet {
  background: none !important;
}
</style>