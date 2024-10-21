<script setup>
import { ref, nextTick, watch } from "vue";
import { clone, set } from "lodash";
import EditChapterHeader from "./EditChapterHeader.vue";
import PagePreview from "./PagePreview.vue";

const props = defineProps({
  chapter: Object,
});
const emit = defineEmits(["close", "update"]);
const tab = ref(null);
const titleText = ref(props.chapter.title);
const locationText = ref(props.chapter.location);
const dateText = ref(props.chapter.date);
const storyText = ref(props.chapter.story);
const previewPageNum = ref(0);
const isNextPageButtonEnabled = ref(false);
const isPrevPageButtonEnabled = ref(false);

function emitUpdatedChapter() {
  const chapter = clone(props.chapter);

  set(chapter, "title", titleText.value);
  set(chapter, "date", dateText.value);
  set(chapter, "location", locationText.value);
  set(chapter, "story", storyText.value);

  emit("update", chapter);
}

function onPreviewRender(hasNextPage, hasPrevPage) {
  isNextPageButtonEnabled.value = hasNextPage;
  isPrevPageButtonEnabled.value = hasPrevPage;
}

watch(
  () => titleText.value,
  () => {
    emitUpdatedChapter();
  }
);
watch(
  () => locationText.value,
  () => {
    emitUpdatedChapter();
  }
);
watch(
  () => dateText.value,
  () => {
    emitUpdatedChapter();
  }
);
watch(
  () => storyText.value,
  () => {
    emitUpdatedChapter();
  }
);
</script>

<template>
  <edit-chapter-header
    :title="chapter.title"
    @close="emit('close')"
    @change-tab="(t) => (tab = t)"
  ></edit-chapter-header>

  <v-tabs-window v-model="tab" class="h-100">
    <v-tabs-window-item :key="1" :value="1">
      <v-sheet
        class="mt-4 pa-4 text-center mx-auto"
        elevation="4"
        max-width="800"
        width="100%"
        height="100%"
      >
        <v-text-field
          v-model="titleText"
          label="Title of this chapter"
          variant="underlined"
        />
        <v-text-field
          v-model="locationText"
          label="Location"
          variant="underlined"
        />
        <v-text-field v-model="dateText" label="Period" variant="underlined" />
        <v-textarea
          v-model="storyText"
          label="Your story"
          row-height="30"
          rows="4"
          variant="underlined"
          auto-grow
          hide-details
        />
      </v-sheet>
    </v-tabs-window-item>

    <v-tabs-window-item :key="2" :value="2">
      <v-sheet
        class="pa-4 text-center mx-auto"
        elevation="3"
        max-width="800"
        width="100%"
        height="100%"
      >
      </v-sheet>
    </v-tabs-window-item>

    <v-tabs-window-item :key="3" :value="3">
      <v-sheet class="mt-4 text-center mx-auto" max-width="800">
        <v-row>
          <v-col class="d-flex justify-start align-center" cols="1">
            <v-btn
              color="primary"
              class="px-2 py-1 ml-4"
              size="xl"
              rounded="xl"
              :disabled="!isPrevPageButtonEnabled"
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
            <page-preview
              :chapter="chapter"
              :page="previewPageNum"
              @render="onPreviewRender"
            ></page-preview>
          </v-col>
          <v-col class="d-flex justify-center align-center" cols="1">
            <v-btn
              color="primary"
              class="px-2 py-1 mr-4"
              size="xl"
              rounded="xl"
              :disabled="!isNextPageButtonEnabled"
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
    </v-tabs-window-item>
  </v-tabs-window>
</template>

<style>
.editable-text-frame {
  width: 100%;
  height: 100%;
}
.img {
  position: inline;
  width: 100px;
  height: 100px;
  border: 1px solid red;
  bottom: 0px;
}
</style>