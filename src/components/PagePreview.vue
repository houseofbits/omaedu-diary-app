<script setup>
import { computed, onMounted, ref, reactive, watch } from "vue";
import CanvasTextFormatter from "../helpers/CanvasTextFormatter";
import { DEFAULT_LAYOUT } from "../constants/pageLayouts";
import PageLayout from "../helpers/PageLayout";
import Rect from "../helpers/Rect";

const props = defineProps({
  chapter: Object,
  page: Number,
});

const emit = defineEmits(["render"]);

let ctx = null;
const canvasRef = ref(null);
const hasNextPage = ref(false);
const hasPrevPage = ref(false);
const formatter = new CanvasTextFormatter();

const layouts = [
  new PageLayout(null, [new Rect(0, 400, 400, 400)]),
  new PageLayout(),
  new PageLayout(),
  new PageLayout(),
  new PageLayout(),
  new PageLayout(),
];

function renderPage(ctx, text, layouts) {
  hasNextPage.value = false;
  hasPrevPage.value = props.page > 0;

  let textIn = text;
  for (let i = 0; i < props.page + 1; i++) {
    ctx.reset();
    if (formatter.draw(ctx, textIn, layouts[i], true)) {
      textIn = formatter.remainingText;
      hasNextPage.value = true;
    } else {
      hasNextPage.value = false;

      break;
    }
  }

  emit("render", hasNextPage.value, hasPrevPage.value);
}

watch(
  () => props.chapter.value,
  () => {
    if (ctx != null) {
      renderPage(ctx, props.chapter.story, layouts);
    }
  },
  { deep: true }
);

watch(
  () => props.page,
  () => {
    if (ctx != null) {
      renderPage(ctx, props.chapter.story, layouts);
    }
  }
);

onMounted(() => {
  ctx = canvasRef.value.getContext("2d");
  if (ctx != null) {
    renderPage(ctx, props.chapter.story, layouts);
  }
});
</script>

<template>
  <canvas
    id="page-canvas"
    width="595"
    height="842"
    class="elevation-4"
    ref="canvasRef"
  ></canvas>
</template>

<style>
#page-canvas {
  /* border: solid 1px blue; */
  width: 100%;
}
</style>