<script setup>
import { useTheme } from "vuetify";

const theme = useTheme();
const props = defineProps({
  chapters: Array,
});

const emit = defineEmits(["select"]);

const primaryColor = theme.current.value.colors.primary;
const backgroundColor = theme.current.value.colors.background;

function selectChapter(index) {
  emit("select", index);
}
</script>

<template>
  <v-container v-if="chapters.length > 0" fluid class="pt-2">
    <v-list density="compact">
      <v-list-subheader>Chapters</v-list-subheader>

      <v-list-item
        v-for="(chapter, i) in chapters"
        :key="i"
        :value="chapter"
        color="primary"
        @click="selectChapter(i)"
      >
        <template v-slot:prepend>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="14"
            width="14"
            viewBox="0 0 512 512"
            class="mr-3"
          >
            <path
              :fill="primaryColor"
              d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM64 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L96 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z"
            />
          </svg>
        </template>

        <v-list-item-title
          ><span class="mr-3 text-overline font-weight-thin">{{
            chapter.period
          }}</span
          >{{ chapter.title }}
        </v-list-item-title>

        <template v-slot:append>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24"
            width="15"
            viewBox="0 0 320 512"
          >
            <path
              :fill="primaryColor"
              d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"
            />
          </svg>
        </template>
      </v-list-item>
    </v-list>
  </v-container>
</template>

<style>
.v-list-subheader,
.v-list {
  background: none !important;
}
.v-list-item {
  background: white;
  margin-top: 6px;
  border-radius: 6px !important;
  padding-right: 12px !important;
}
</style>