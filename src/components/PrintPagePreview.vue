<script setup>
import { computed, onMounted, ref, reactive, watch, inject } from "vue";
import CanvasPrintPageRenderer from "../helpers/CanvasPrintPageRenderer";
import useSettings from "../composables/Settings";

const { getDiaryBackgroundImageUrl } = useSettings();
const props = defineProps({
  printPage: Object,
});

let ctx = null;
const canvasRef = ref(null);
const scale = 3;
const canvasWidth = ref(595 * scale);
const canvasHeight = ref(842 * scale);

onMounted(async () => {
  ctx = canvasRef.value.getContext("2d");
  if (ctx != null) {
    ctx.reset();
    const canvasPrintPageRenderer = new CanvasPrintPageRenderer(scale);
    await canvasPrintPageRenderer.renderBackground(
      ctx,
      getDiaryBackgroundImageUrl()
    );
    await canvasPrintPageRenderer.renderText(ctx, props.printPage);
    await canvasPrintPageRenderer.renderImages(ctx, props.printPage);
  }
});
</script>

<template>
  <canvas
    class="print-canvas elevation-4"
    :width="canvasWidth"
    :height="canvasHeight"
    ref="canvasRef"
  ></canvas>
</template>

<style>
.print-canvas {
  width: 100%;
  background-color: white;
}
</style>